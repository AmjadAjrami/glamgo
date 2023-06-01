<?php

namespace App\Http\Controllers\API\Auth;

use App\Http\Controllers\Controller;
use App\Models\MobileVerification;
use App\Models\User;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ForgotPasswordController extends Controller
{
    /*
      |--------------------------------------------------------------------------
      | Password Reset Controller
      |--------------------------------------------------------------------------
      |
      | This controller is responsible for handling password reset emails and
      | includes a trait which assists in sending these notifications from
      | your application to your users. Feel free to explore this trait.
      |
     */

    use SendsPasswordResetEmails;


    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function forget_password(Request $request)
    {
        $rules = [
            'mobile' => 'required|numeric|digits_between:7,10',
        ];

        $validation = [
            'mobile.required' => __('common.mobile_required'),
            'mobile.unique' => __('common.mobile_unique'),
            'mobile.exists' => __('common.mobile_exists'),
            'mobile.digits_between' => __('common.mobile_digits_between'),
        ];

        $validator = \Illuminate\Support\Facades\Validator::make($request->all(), $rules, $validation);
        if ($validator->fails()) {
            return mainResponse(false, $validator->errors()->first(), [], $validator->errors()->messages(), 501);
        }

        $mobile = $request->mobile;
        if (substr($mobile, 0, 1) == 0) {
            $new_mobile = substr($request->mobile, 1);
        } else {
            $new_mobile = $request->mobile;
        }

        $user = User::query()->where('mobile', $new_mobile)->get()->first();

        if (!$user){
            return mainResponse(true, __('common.mobile_not_found'), (object)[], [], 501);
        }

//        $code = str_replace('0', '1', \Carbon\Carbon::now()->timestamp);
//        $code = str_shuffle($code);
//        $code = substr($code, 0, 4);
//        $code = (int)$code;
        $code = 1111;

        MobileVerification::query()->where('mobile', $new_mobile)->delete();
        MobileVerification::query()->insert(['mobile' => $new_mobile, 'code' => bcrypt($code), 'type' => 1]);

        return mainResponse(true, __('common.verification_sent'), [], [], 200);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function verify_code(Request $request)
    {
        $mobile = $request->mobile;
        if (substr($mobile, 0, 1) == 0) {
            $new_mobile = substr($request->mobile, 1);
        } else {
            $new_mobile = $request->mobile;
        }

        $mobile_code = MobileVerification::query()->where('mobile', $new_mobile)->where('type', 1)->first();

        if (!$mobile_code){
            return mainResponse(false, __('common.mobile_not_found'), (object)[], [], 501);
        }

        $rules = [
            'mobile' => 'required',
            'code' => 'required|hash_check:' . @$mobile_code->code,
        ];

        $validation = [
            'mobile.required' => __('common.mobile_required'),
            'code.required' => __('common.code_required'),
            'mobile.exists' => __('common.mobile_exists'),
            'code.hash_check' => __('common.hash_check'),
        ];

        $validator = Validator::make($request->all(), $rules, $validation);
        if ($validator->fails()) {
            return mainResponse_2(false, $validator->errors()->first(), (object)[], $validator->errors()->messages());
        }

        $code = str_replace('0', '1', \Carbon\Carbon::now()->timestamp);
        $code = str_shuffle($code);
        $code = substr($code, 0, 4);
        $code = (int)$code;
//        $code = 1111;

        MobileVerification::query()->where('mobile', $new_mobile)->update(['code' => bcrypt($code), 'type' => 2]);

        return mainResponse_2(true, __('ok'), $code, [], 200);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function reset_password(Request $request)
    {
        $mobile = $request->mobile;
        if (substr($mobile, 0, 1) == 0) {
            $new_mobile = substr($request->mobile, 1);
        } else {
            $new_mobile = $request->mobile;
        }

        $code = MobileVerification::query()->where('mobile', $new_mobile)->where('type', 2)->first();

        if (!$code){
            return mainResponse(true, __('common.mobile_not_found'), (object)[], [], 501);
        }

        $rules = [
            'mobile' => 'required',
            'code' => 'required|hash_check:' . @$code->code,
            'password' => 'required|string|min:6',
            'confirm_password' => 'required|same:password',
        ];

        $validation = [
            'mobile.required' => __('common.mobile_required'),
            'mobile.exists' => __('common.mobile_exists'),
            'password.required' => __('common.password_required'),
            'confirm_password.required' => __('common.confirm_password_required'),
            'confirm_password.same' => __('common.confirm_password_same'),
            'code.hash_check' => __('common.hash_check'),
        ];

        $validator = \Illuminate\Support\Facades\Validator::make($request->all(), $rules, $validation);
        if ($validator->fails()) {
            return mainResponse(true, $validator->errors()->first(), [], $validator->errors()->messages());
        }

        if ($request->mobile) {
            \App\Models\User::query()->where('mobile', $new_mobile)
                ->update(['password' => bcrypt($request->password)]);
            MobileVerification::query()->where('mobile', $new_mobile)->delete();
        }

        return mainResponse(true, __('common.reset_successfully'), [], [], 200);
    }
}
