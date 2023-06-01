<?php

namespace App\Http\Controllers\Admin;

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
        $this->middleware('auth:admin');
    }

    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function index()
    {

    }

    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function men_salons()
    {
        $salons = Salon::query()->where('type', 1)->get();

        $app_percentage_reservation_setting = Setting::query()->where('key', 'app_percentage')->first();
        $app_percentage_store_setting = Setting::query()->where('key', 'app_percentage_store')->first();

        $users = [];

        foreach ($salons as $salon){
            $carts_ids = Order::query()->where('status', 2)->pluck('cart_id');
            $salon_products = Product::query()->where('salon_id', $salon->id)->pluck('id');
            $orders_price = CartItem::query()->whereIn('cart_id', $carts_ids)->whereIn('product_id', $salon_products)->sum('total_price');

            $reservations_price = Reservation::query()->where('salon_id', $salon->id)->where('payment_method_id', '!=', 1)->where('status', 3)->whereNull('total_price_after_code')->sum('total_price');
            $reservations_price_with_discount = Reservation::query()->where('salon_id', $salon->id)->where('payment_method_id', '!=', 1)->where('status', 3)->whereNotNull('total_price_after_code')->sum('total_price_after_code');

            $reservations_total_amount = $reservations_price + $reservations_price_with_discount;
            $total_amount = $orders_price + $reservations_total_amount;

            $withdraws = Withdraw::query()->where('salon_id', $salon->id)->where('type', 1)->sum('amount');

            $reservations_cash = Reservation::query()->where('salon_id', $salon->id)
                ->where('payment_method_id', 1)->where('status', 3)->get();

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

            $users[] = [
                'id' => $salon->id,
                'name' => $salon->name,
                'email' => $salon->email,
                'mobile' => $salon->mobile,
                'type_text' => __('common.salon'),
                'type' => 'salon',
                'orders_price' => $orders_price,
                'reservations_price' => $reservations_price + $reservations_price_with_discount,
                'total_amount' => $total_amount,
                'app_percentage_reservations' => $app_percentage_reservation,
                'app_percentage_store' => $app_percentage_store,
                'total_amount_after_app_percentage' => $total_amount - $total_app_percentage,
                'cash_total' => $cash_total,
                'app_percentage_from_cash' => $app_percentage_cash,
                'withdrawn_amount' => $withdraws,
                'remaining_amount' => ($total_amount - $total_app_percentage) - $withdraws,
                'bank_name' => $salon->bank_name,
                'bank_account_name' => $salon->bank_account_name,
                'iban' => $salon->iban,
                'bank_account_number' => $salon->bank_account_number,
            ];
        }

        return view('admin.financial_accounts.men_salons', compact('users'));
    }

    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function women_salons()
    {
        $salons = Salon::query()->where('type', 2)->get();

        $app_percentage_reservation_setting = Setting::query()->where('key', 'app_percentage')->first();
        $app_percentage_store_setting = Setting::query()->where('key', 'app_percentage_store')->first();

        $users = [];

        foreach ($salons as $salon){
            $carts_ids = Order::query()->where('status', 2)->pluck('cart_id');
            $salon_products = Product::query()->where('salon_id', $salon->id)->pluck('id');
            $orders_price = CartItem::query()->whereIn('cart_id', $carts_ids)->whereIn('product_id', $salon_products)->sum('total_price');

            $reservations_price = Reservation::query()->where('salon_id', $salon->id)->where('payment_method_id', '!=', 1)->where('status', 3)->whereNull('total_price_after_code')->sum('total_price');
            $reservations_price_with_discount = Reservation::query()->where('salon_id', $salon->id)->where('payment_method_id', '!=', 1)->where('status', 3)->whereNotNull('total_price_after_code')->sum('total_price_after_code');

            $reservations_total_amount = $reservations_price + $reservations_price_with_discount;
            $total_amount = $orders_price + $reservations_total_amount;

            $withdraws = Withdraw::query()->where('salon_id', $salon->id)->where('type', 1)->where('status', 1)->sum('amount');

            $reservations_cash = Reservation::query()->where('salon_id', $salon->id)
                ->where('payment_method_id', 1)->where('status', 3)->get();

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

            $users[] = [
                'id' => $salon->id,
                'name' => $salon->name,
                'email' => $salon->email,
                'mobile' => $salon->mobile,
                'type_text' => __('common.salon'),
                'type' => 'salon',
                'orders_price' => $orders_price,
                'reservations_price' => $reservations_price + $reservations_price_with_discount,
                'total_amount' => $total_amount,
                'app_percentage_reservations' => $app_percentage_reservation,
                'app_percentage_store' => $app_percentage_store,
                'total_amount_after_app_percentage' => $total_amount - $total_app_percentage,
                'cash_total' => $cash_total,
                'app_percentage_from_cash' => $app_percentage_cash,
                'withdrawn_amount' => $withdraws,
                'remaining_amount' => ($total_amount - $total_app_percentage) - $withdraws,
                'bank_name' => $salon->bank_name,
                'bank_account_name' => $salon->bank_account_name,
                'iban' => $salon->iban,
                'bank_account_number' => $salon->bank_account_number,
            ];
        }

        return view('admin.financial_accounts.women_salons', compact('users'));
    }

    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function makeup_artists()
    {
        $artists = MakeupArtist::query()->get();

        $app_percentage_setting = Setting::query()->where('key', 'app_percentage')->first();

        $users = [];

        foreach ($artists as $artist){
            $reservations_price = Reservation::query()->where('makeup_artist_id', $artist->id)->where('status', 3)->whereNull('total_price_after_code')->sum('total_price');
            $reservations_price_with_discount = Reservation::query()->where('makeup_artist_id', $artist->id)->where('status', 3)->whereNotNull('total_price_after_code')->sum('total_price_after_code');

            $total_amount = $reservations_price + $reservations_price_with_discount;

            $withdraws = Withdraw::query()->where('makeup_artist_id', $artist->id)->where('type', 1)->sum('amount');

            $reservations_cash = Reservation::query()->where('makeup_artist_id', $artist->id)
                ->where('payment_method_id', 1)->where('status', 3)->get();
            $cash_total = 0;
            foreach ($reservations_cash as $cash){
                $cash_total += $cash->total_price_after_code != null ? $cash->total_price_after_code : $cash->total_price;
            }

            if ($app_percentage_setting){
                $app_percentage = ($total_amount * ($app_percentage_setting->value / 100));
                $app_percentage_cash = ($cash_total * ($app_percentage_setting->value / 100));
            }else{
                $app_percentage = 0;
                $app_percentage_cash = 0;
            }

            $users[] = [
                'id' => $artist->id,
                'name' => $artist->name,
                'email' => $artist->email,
                'mobile' => $artist->mobile,
                'type_text' => __('common.artist'),
                'type' => 'artist',
                'orders_price' => 0,
                'reservations_price' => $reservations_price + $reservations_price_with_discount,
                'total_amount' => $total_amount,
                'app_percentage' => $app_percentage,
                'total_amount_after_app_percentage' => $total_amount - $app_percentage,
                'withdrawn_amount' => $withdraws,
                'remaining_amount' => ($total_amount - $app_percentage) - $withdraws,
                'cash_total' => $cash_total,
                'app_percentage_from_cash' => $app_percentage_cash,
                'bank_name' => $artist->bank_name,
                'bank_account_name' => $artist->bank_account_name,
                'iban' => $artist->iban,
                'bank_account_number' => $artist->bank_account_number,
            ];
        }

        return view('admin.financial_accounts.makeup_artists', compact('users'));
    }

    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function get_cash_transfers()
    {
        $transfers = Withdraw::query()->where('type', 2)->get();
        return view('admin.financial_accounts.transfered_cash', compact('transfers'));
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function make_withdraw(Request $request)
    {
        $rules = [
            'salon_id' => 'nullable',
            'artist_id' => 'nullable',
            'amount' => 'required',
            'image' => 'required',
        ];

        $this->validate($request, $rules);

        if ($request->amount > $request->remaining_amount){
            flash()->warning(__('common.amount_more'));
            return redirect()->back();
        }

        $data = [];
        $data['salon_id'] = $request->salon_id ?? null;
        $data['makeup_artist_id'] = $request->artist_id ?? null;
        $data['amount'] = $request->amount;
        $data['type'] = 1;

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

    /**
     * @param Request $request
     * @return array
     */
    public function withdrawals(Request $request)
    {
        if ($request->type == 'salon'){
            $withdrawals = Withdraw::query()->where('salon_id', $request->id)->where('type', 1)->get();
        }elseif ($request->type == 'artist'){
            $withdrawals = Withdraw::query()->where('makeup_artist_id', $request->id)->where('type', 1)->get();
        }

        return ['status' => true, 'withdrawals' => $withdrawals];
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
}
