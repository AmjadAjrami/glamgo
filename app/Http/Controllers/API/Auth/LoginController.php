<?php

namespace App\Http\Controllers\API\Auth;

use App\Http\Controllers\Controller;
use App\Models\MobileToken;
use App\Models\MobileVerification;
use App\Models\User;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class LoginController extends Controller
{
    /*
  |--------------------------------------------------------------------------
  | Login Controller
  |--------------------------------------------------------------------------
  |
  | This controller handles authenticating users for the application and
  | redirecting them to your home screen. The controller uses a trait
  | to conveniently provide its functionality to your applications.
  |
  */

    use AuthenticatesUsers;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest:sanctum')->except('logout');
    }

    /**
     * @return mixed
     */
    protected function guard()
    {
        return Auth::guard('sanctum');
    }

    /**
     * @param array $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        $rules = [
            'mobile' => 'required',
            'password' => 'required|string|max:255',
            'token' => 'required|string|max:255',
            'device' => 'required|string|max:255|in:android,ios',
        ];

        $validation = [
            'mobile.required' => __('common.mobile_required'),
            'mobile.exists' => __('common.mobile_exists'),
            'password.required' => __('common.password_required'),
        ];

        return Validator::make($data, $rules, $validation);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {
        $validator = $this->validator($request->all());

        if ($validator->fails()) {
            return mainResponse(false, $validator->errors()->first(), [], $validator->errors()->messages());
        }

        if ($this->hasTooManyLoginAttempts($request)) {
            $this->fireLockoutEvent($request);
            return $this->sendLockoutResponse($request);
        }

        $mobile = $request->mobile;
        if (substr($mobile, 0, 1) == 0) {
            $new_mobile = substr($request->mobile, 1);
        } else {
            $new_mobile = $request->mobile;
        }

        $mobile_code = MobileVerification::query()->where('mobile', $new_mobile)->where('type', 1)->first();

        if ($mobile_code){
            return mainResponse_2(false, __('common.account_not_verified'), (object)[], [], 200);
        }

        if ($this->attemptLogin($request)) {
            $this->clearLoginAttempts($request);
            $user = User::query()->find(auth()->guard()->user()->id);

            if ($user->status == 0){
                return mainResponse_2(false, __('common.not_verified'), (object)[], [], 200);
            }

            MobileToken::query()->where('user_id', $user->id)->delete();

            MobileToken::query()->updateOrCreate(
                ['user_id' => $user->id, 'token' => $request->token, 'device' => $request->device],
                ['user_id' => $user->id, 'token' => $request->token, 'device' => $request->device]
            );

            $user->setAttribute('token', $user->createToken('LaravelSanctumAuth')->plainTextToken);

            return mainResponse_2(true, __('ok'), $user, [], 200);
        }

        $this->incrementLoginAttempts($request);

        return $this->sendFailedLoginResponse($request);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    protected function sendLockoutResponse(Request $request)
    {
        $seconds = $this->limiter()->availableIn(
            $this->throttleKey($request)
        );
        return mainResponse_2(false, __('auth.throttle', ['seconds' => $seconds]), (object)[], [], 200);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    protected function sendFailedLoginResponse(Request $request)
    {
        return mainResponse_2(false, __('common.login_failed'), (object)[], [], 401);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout(Request $request)
    {
        $user = auth('sanctum')->user();
        if ($user == null) {
            return mainResponse(true, __('common.invalid_token'), [], [], 200);
        }
        $user->tokens()->where('id', $user->currentAccessToken()->id)->delete();
        MobileToken::query()->where('user_id', $user->id)->delete();
        return mainResponse(true, __('common.logout_successfully'), [], [], 200);
    }

    /**
     * @param Request $request
     * @return mixed
     */
    protected function attemptLogin(Request $request)
    {
        return auth()->guard()->attempt(
            $this->credentials($request), $request->filled('remember')
        );
    }

    /**
     * @param Request $request
     * @return array|\Illuminate\Http\JsonResponse
     */
    protected function credentials(\Illuminate\Http\Request $request)
    {
        $mobile = $request->mobile;
        if (substr($mobile, 0, 1) == 0) {
            $new_mobile = substr($request->mobile, 1);
        } else {
            $new_mobile = $request->mobile;
        }

        return ['mobile' => $new_mobile, 'password' => $request->password];
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function social_login(Request $request)
    {
        $rules = [
            'social_provider' => 'required',
            'social_token' => 'required',
            'name' => 'nullable',
            'email' => 'nullable',
            'mobile' => 'nullable',
            'image' => 'nullable',
            'token' => 'required',
            'device' => 'required',
        ];

        $validation = [
            'name.required' => __('common.name_required'),
            'email.required' => __('common.email_required'),
            'email.unique' => __('common.email_unique'),
        ];

        $validator = Validator::make($request->all(), $rules, $validation);
        if ($validator->fails()) {
            return mainResponse(false, $validator->errors()->first(), (object)[], $validator->errors()->messages(), 200);
        }

        $user = User::query()->where('social_token', $request->social_token)->get()->first();

        if (!$user) {
            if ($request->email){
                $email_exists = User::query()->where('email', $request->email)->exists();
                if ($email_exists){
                    return mainResponse_2(false, __('common.email_unique'), (object)[], [], 200);
                }
            }
            if ($request->mobile){
                $mobile_exists = User::query()->where('mobile', $request->mobile)->exists();
                if ($mobile_exists){
                    return mainResponse_2(false, __('common.mobile_unique'), (object)[], [], 200);
                }
            }

            $user = User::query()->create($request->except('token', 'device'));

            MobileToken::query()->updateOrCreate(
                ['user_id' => $user->id, 'token' => $request->token, 'device' => $request->device],
                ['user_id' => $user->id, 'token' => $request->token, 'device' => $request->device]
            );

            $user->setAttribute('token', $user->createToken('LaravelSanctumAuth')->plainTextToken);
        } else {
            if ($request->email){
                if ($request->email != $user->email){
                    $email_exists = User::query()->where('email', $request->email)->exists();
                    if ($email_exists){
                        return mainResponse_2(false, __('common.email_unique'), (object)[], [], 200);
                    }
                }
            }

            if ($request->mobile){
                if ($request->mobile != $user->mobile){
                    $mobile_exists = User::query()->where('mobile', $request->mobile)->exists();
                    if ($mobile_exists){
                        return mainResponse_2(false, __('common.mobile_unique'), (object)[], [], 200);
                    }
                }
            }

            $user->update($request->except('token', 'device'));

            MobileToken::query()->updateOrCreate(
                ['user_id' => $user->id, 'token' => $request->token, 'device' => $request->device],
                ['user_id' => $user->id, 'token' => $request->token, 'device' => $request->device]
            );

            $user->setAttribute('token', $user->createToken('LaravelSanctumAuth')->plainTextToken);
        }

        return mainResponse_2(true, __('ok'), $user, [], 200);
    }
}
