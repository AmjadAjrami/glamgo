<?php

namespace App\Http\Controllers\Salon;

use App\Http\Controllers\Controller;
use App\Models\CartItem;
use App\Models\MakeupArtist;
use App\Models\Order;
use App\Models\Product;
use App\Models\Reservation;
use App\Models\Salon;
use App\Models\Setting;
use App\Models\Withdraw;
use Illuminate\Http\Request;

class FinancialAccountsController extends Controller
{

    /**
     *
     */
    public function __construct()
    {
        $this->middleware('auth:salon');
    }

    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function index()
    {
        $salon = Salon::query()->find(auth('salon')->id());

        $app_percentage_reservation_setting = Setting::query()->where('key', 'app_percentage')->first();
        $app_percentage_store_setting = Setting::query()->where('key', 'app_percentage_store')->first();

        $carts_ids = Order::query()->where('status', 2)->pluck('cart_id');
        $salon_products = Product::query()->where('salon_id', $salon->id)->pluck('id');
        $orders_price = CartItem::query()->whereIn('cart_id', $carts_ids)->whereIn('product_id', $salon_products)->sum('total_price');

        $reservations_price = Reservation::query()->where('salon_id', $salon->id)->where('payment_method_id', '!=', 1)->where('status', 3)->whereNull('total_price_after_code')->sum('total_price');
        $reservations_price_with_discount = Reservation::query()->where('salon_id', $salon->id)->where('payment_method_id', '!=', 1)->where('status', 3)->whereNotNull('total_price_after_code')->sum('total_price_after_code');

        $reservations_total_amount = $reservations_price + $reservations_price_with_discount;
        $total_amount = $orders_price + $reservations_total_amount;

        $withdraws = Withdraw::query()->where('salon_id', $salon->id)->where('type', 1)->where('status', 1)->sum('amount');
        $cash_withdraws = Withdraw::query()->where('salon_id', $salon->id)->where('type', 2)->where('status', 1)->sum('amount');

        $reservations_cash = Reservation::query()->where('salon_id', auth('salon')->id())
            ->where('payment_method_id', 1)->where('status', 3)->get();

//        dd($reservations_cash);

        $cash_total = 0;
        foreach ($reservations_cash as $cash){
            $cash_total += $cash->total_price_after_code != null ? $cash->total_price_after_code : $cash->total_price;
        }

        if ($app_percentage_reservation_setting){
            $app_percentage_reservation = ($reservations_total_amount * ($app_percentage_reservation_setting->value / 100));
            $app_percentage_cash = ($cash_total * ($app_percentage_reservation_setting->value / 100));
        }else{
            $app_percentage_reservation = 0;
            $app_percentage_cash = 0;
        }

        if ($app_percentage_store_setting){
            $app_percentage_store = ($orders_price * ($app_percentage_store_setting->value / 100));
        }else{
            $app_percentage_store = 0;
        }

        $total_app_percentage = $app_percentage_reservation + $app_percentage_store;

        $details = [
            'orders_price' => $orders_price,
            'reservations_price' => $reservations_price + $reservations_price_with_discount,
            'total_amount' => $total_amount,
            'app_percentage_reservations' => $app_percentage_reservation,
            'app_percentage_store' => $app_percentage_store,
            'total_amount_after_app_percentage' => $total_amount - $total_app_percentage,
            'withdrawn_amount' => $withdraws,
            'remaining_amount' => ($total_amount - $total_app_percentage) - $withdraws <= 0 ? 0 : ($total_amount - $total_app_percentage) - $withdraws,
            'cash_total' => $cash_total,
            'app_percentage_from_cash' => $app_percentage_cash,
            'remaining_cash' => ($app_percentage_cash - $cash_withdraws) <= 0 ? 0 : $app_percentage_cash - $cash_withdraws,
        ];

        $salon_withdraws = Withdraw::query()->where('salon_id', $salon->id)->where('type', 1)->get();
        $salon_cash_transfers = Withdraw::query()->where('salon_id', $salon->id)->where('type', 2)->get();

        return view('salon.financial_accounts.index', compact('details', 'salon_withdraws', 'salon_cash_transfers'));
    }

    /**
     * @param Request $request
     * @return true[]
     */
    public function update_status(Request $request)
    {
        $withdraw = Withdraw::query()->find($request->id);
        $withdraw->update([
            'status' => $request->type,
        ]);

        return ['status' => true];
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function make_withdraw(Request $request)
    {
        $rules = [
            'amount' => 'required',
            'image' => 'required',
        ];

        $this->validate($request, $rules);

        if ($request->amount <= 0){
            flash()->warning(__('common.zero_or_less'));
            return redirect()->back();
        }

        if ($request->amount > $request->remaining_amount){
            flash()->warning(__('common.amount_more'));
            return redirect()->back();
        }

        $data = [];
        $data['salon_id'] = auth('salon')->id();
        $data['amount'] = $request->amount;
        $data['type'] = 2;

        if ($request->image){
            $filename = rand(111111, 999999) . '_' . \Carbon\Carbon::now()->timestamp;
            $filename .= substr($request->image->getClientOriginalName(), strrpos($request->image->getClientOriginalName(), '.'));
            $filename = $request->image->move(public_path('images'), $filename);
            $data['image'] = $filename->getBasename();
        }

        Withdraw::query()->create($data);

        flash()->success(__('common.withdraw_done'));
        return redirect()->back();
    }
}
