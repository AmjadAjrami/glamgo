<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Address;
use App\Models\Transaction;
use App\Models\User;
use App\Rules\MatchOldPassword;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

/**
 *
 */
class ProfileController extends Controller
{
    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function get_profile()
    {
        $user = User::query()->find(auth('sanctum')->id());
        $user->setAttribute('token', \request()->bearerToken());
        return mainResponse_2(true, __('ok'), $user, [], 200);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function update_profile(Request $request)
    {
        $user = User::query()->find(auth('sanctum')->id());

        $rules = [
            'name' => 'required',
            'email' => 'required|unique:users,email,' . $user->id,
            'mobile' => 'required|unique:users,mobile,' . $user->id,
            'country_id' => 'required|exists:countries,id',
            'city_id' => 'required|exists:cities,id',
            'image' => 'nullable',
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return mainResponse(false, $validator->errors()->first(), (object)[], $validator->errors()->messages(), 200);
        }

        $data = $request->except('image');

        if ($request->image){
            $filename = rand(111111, 999999) . '_' . \Carbon\Carbon::now()->timestamp;
            $filename .= substr($request->image->getClientOriginalName(), strrpos($request->image->getClientOriginalName(), '.'));
            $filename = $request->image->move(public_path('images'), $filename);
            $data['image'] = $filename->getBasename();
        }

        $user->update($data);

        $user->setAttribute('token', request()->bearerToken());

        return mainResponse_2(true, __('common.updated_successfully'), $user, [], 200);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function update_password(Request $request)
    {
        $user = User::query()->find(auth('sanctum')->id());

        $rules = [
            'old_password' => ['required', new MatchOldPassword()],
            'new_password' => 'required|different:old_password',
            'confirm_password' => 'required|same:new_password',
        ];

        $validation = [
            'old_password.required' => __('common.old_password_required'),
            'new_password.required' => __('common.new_password_required'),
            'new_password.different' => __('common.new_password_different'),
            'confirm_password.required' => __('common.confirm_password_required'),
            'confirm_password.same' => __('common.confirm_password_same'),
        ];

        $validator = Validator::make($request->all(), $rules, $validation);
        if ($validator->fails()) {
            return mainResponse(false, $validator->errors()->first(), (object)[], $validator->errors()->messages(), 200);
        }

        $user->password = Hash::make($request->new_password);
        $user->save();

        $user->setAttribute('token', request()->bearerToken());

        return mainResponse_2(true, __('common.updated_successfully'), $user, [], 200);
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function delete_account()
    {
        User::query()->find(auth('sanctum')->id())->delete();
        return mainResponse_2(true, __('common.deleted_successfully'), (object)[], [], 200);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function update_notification_status(Request $request)
    {
        $rules = [
            'status' => 'required|in:1,0',
        ];

        $validation = [
            'status.required' => __('common.status_required'),
        ];

        $validator = Validator::make($request->all(), $rules, $validation);
        if ($validator->fails()) {
            return mainResponse(false, $validator->errors()->first(), (object)[], $validator->errors()->messages(), 200);
        }

        $user = User::query()->find(auth('sanctum')->id());

        $user->update(['notification_active' => $request->status]);

        return mainResponse_2(true, __('common.updated_successfully'), (object)[], [], 200);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function add_address(Request $request)
    {
        $rules = [
            'address_name' => 'required',
            'mobile' => 'required',
            'country_id' => 'required|exists:countries,id',
            'city_id' => 'required|exists:cities,id',
            'detailed_address' => 'required',
            'postal_number' => 'required',
            'lat' => 'required',
            'lng' => 'required',
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return mainResponse(false, $validator->errors()->first(), (object)[], $validator->errors()->messages(), 200);
        }

        $data = $request->all();
        $data['user_id'] = auth('sanctum')->id();

        Address::query()->create($data);

        return mainResponse_2(true, __('common.created_successfully'), (object)[], [], 200);
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function addresses()
    {
        $addresses = Address::query()->where('user_id', auth('sanctum')->id())->get();
        return mainResponse_2(true, __('ok'), $addresses, [], 200);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function delete_address(Request $request)
    {
        $rules = [
            'address_id' => 'required|exists:addresses,id',
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return mainResponse(false, $validator->errors()->first(), (object)[], $validator->errors()->messages(), 200);
        }

        Address::query()->find($request->address_id)->delete();

        return mainResponse_2(true, __('common.deleted_successfully'), (object)[], [], 200);
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function wallet()
    {
        $transactions = Transaction::query()->where('user_id', auth('sanctum')->id())->get();
        $balance = auth('sanctum')->user()->balance;

        $data = [
            'balance' => $balance,
            'transactions' => $transactions,
        ];

        return mainResponse(true, __('ok'), $data, [], 200);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function add_balance(Request $request)
    {
        $rules = [
            'balance' => 'required',
            'payment_method_id' => 'required|exists:payment_methods,id',
            'transaction_no' => 'required',
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return mainResponse(false, $validator->errors()->first(), (object)[], $validator->errors()->messages(), 200);
        }

        $user = User::query()->find(auth('sanctum')->id());

        $user = User::query()->find($request->user_id);
        $user->balance += $request->amount;
        $user->update();

        Transaction::query()->insert([
            'user_id' => $user->id,
            'type' => 2,
            'price' => $request->amount,
            'payment_method_id' => $request->payment_method_id,
            'transaction_no' => $request->transaction_no,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        return mainResponse_2(true, __('تم الشحن بمجاح'), (object)[], [], 200);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function add_balance_webview(Request $request)
    {
        $order_id = $request->id;
        $amount = $request->amount;
        $mobile = $request->mobile;
        $email = $request->email;
        $callback_url = url('/api/add_balance_callback?id=' . $request->id . '&amount=' . $amount  . '&payment_method_id=' . $request->payment_method_id . '&user_id=' . $request->user_id);
        $items = [
            [
                'order_id' => $order_id,
                'amount' => $amount,
                'quantity' => 1,
                'itemname' => 'Add Balance',
            ]
        ];
        $lang = $request->lang;

        return view('payment_webview', compact('order_id', 'amount', 'mobile', 'email', 'items', 'callback_url', 'lang'));
    }

    public function add_balance_callback(Request $request)
    {
        if ($request->transaction_status != 3){
            return redirect(route('fail'));
        }

        $user = User::query()->find($request->user_id);
        $user->balance += $request->amount;
        $user->update();

        Transaction::query()->insert([
            'user_id' => $user->id,
            'type' => 2,
            'price' => $request->amount,
            'payment_method_id' => $request->payment_method_id,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        return redirect(route('success'));
    }
}
