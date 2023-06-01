<?php

namespace App\Http\Controllers\Artist;

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
        $this->middleware('auth:artist');
    }

    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function index()
    {
        $artist = MakeupArtist::query()->find(auth('artist')->id());

        $app_percentage_setting = Setting::query()->where('key', 'app_percentage')->first();
        $reservations_price = Reservation::query()->where('makeup_artist_id', $artist->id)->where('payment_method_id', '!=', 1)->where('status', 3)->whereNull('total_price_after_code')->sum('total_price');
        $reservations_price_with_discount = Reservation::query()->where('makeup_artist_id', $artist->id)->where('payment_method_id', '!=', 1)->where('status', 3)->whereNotNull('total_price_after_code')->sum('total_price_after_code');

        $total_amount = $reservations_price + $reservations_price_with_discount;

        $withdraws = Withdraw::query()->where('makeup_artist_id', $artist->id)->where('type', 1)->where('status', 1)->sum('amount');
        $cash_withdraws = Withdraw::query()->where('makeup_artist_id', $artist->id)->where('type', 2)->where('status', 1)->sum('amount');

        if ($app_percentage_setting){
            $app_percentage = ($total_amount * ($app_percentage_setting->value / 100));
        }else{
            $app_percentage = 0;
        }

        $reservations_cash = Reservation::query()->where('makeup_artist_id', auth('artist')->id())
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

        $details = [
            'reservations_price' => $reservations_price + $reservations_price_with_discount,
            'total_amount' => $total_amount,
            'app_percentage' => $app_percentage,
            'total_amount_after_app_percentage' => $total_amount - $app_percentage,
            'withdrawn_amount' => $withdraws,
            'remaining_amount' => ($total_amount - $app_percentage) - $withdraws,
            'cash_total' => $cash_total,
            'app_percentage_from_cash' => $app_percentage_cash,
            'remaining_cash' => ($app_percentage_cash - $cash_withdraws) <= 0 ? 0 : $app_percentage_cash - $cash_withdraws,
        ];

        $artist_withdraws = Withdraw::query()->where('makeup_artist_id', $artist->id)->where('type', 1)->get();
        $artist_cash_transfers = Withdraw::query()->where('makeup_artist_id', $artist->id)->where('type', 2)->get();

        return view('artist.financial_accounts.index', compact('details', 'artist_withdraws', 'artist_cash_transfers'));
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
        $data['makeup_artist_id'] = auth('artist')->id();
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
