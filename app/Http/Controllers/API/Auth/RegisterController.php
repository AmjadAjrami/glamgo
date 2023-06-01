<?php

namespace App\Http\Controllers\API\Auth;

use App\Http\Controllers\Controller;
use App\Models\MobileToken;
use App\Models\MobileVerification;
use App\Models\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    use RegistersUsers;

    /**
     * @param array $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => 'required|string|max:255',
            'email' => 'required|unique:users,email',
            'mobile' => 'required|unique:users,mobile',
            'gender' => 'nullable',
            'country_id' => 'required|exists:countries,id',
            'city_id' => 'required|exists:cities,id',
            'password' => 'required|min:6|max:12',
            'token' => 'required',
            'device' => 'required',
        ],
            [
                'name.required' => __('common.name_required'),
                'email.required' => __('common.email_required'),
                'email.unique' => __('common.email_unique'),
                'mobile.required' => __('common.mobile_required'),
                'mobile.unique' => __('common.mobile_unique'),
                'country_id.required' => __('common.country_required'),
                'country_id.exists' => __('common.country_exists'),
                'city_id.required' => __('common.city_required'),
                'city_id.exists' => __('common.city_exists'),
                'password.required' => __('common.password_required'),
            ]
        );
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(Request $request)
    {
        $data = $request->all();

        $validator = $this->validator($data);
        if ($validator->fails()) {
            return mainResponse(false, $validator->errors()->first(), (object)[], $validator->errors()->messages(), 200);
        }

        $mobile = $data['mobile'];
        if (substr($mobile, 0, 1) == 0) {
            $new_mobile = substr($data['mobile'], 1);
        } else {
            $new_mobile = $data['mobile'];
        }

        $mobile_exists = User::query()->where('mobile', $new_mobile)->exists();
        if ($mobile_exists){
            return mainResponse_2(false, __('common.mobile_unique'), (object)[], [__('common.mobile_unique')], 200);
        }

        $user = $this->create($data);

        MobileToken::query()->updateOrCreate(
            ['user_id' => $user->id, 'token' => $request->token, 'device' => $request->device],
            ['user_id' => $user->id, 'token' => $request->token, 'device' => $request->device]
        );

        //        $code = str_replace('0', '', \Carbon\Carbon::now()->timestamp);
        //        $code = str_shuffle($code);
        //        $code = substr($code, 0, 6);
        $code = 1111;

        $mobile = $request->mobile;
        if (substr($mobile, 0, 1) == 0) {
            $new_mobile = substr($request->mobile, 1);
        } else {
            $new_mobile = $request->mobile;
        }

        MobileVerification::query()->where('mobile', $new_mobile)->delete();
        MobileVerification::query()->insert(['mobile' => $new_mobile, 'code' => bcrypt($code), 'type' => 1]);

        return mainResponse(true, 'We sent verification code to your mobile', [], [], 200);
    }

    /**
     * @param array $data
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model|\Illuminate\Http\JsonResponse
     */
    protected function create(array $data)
    {
        $mobile = $data['mobile'];
        if (substr($mobile, 0, 1) == 0) {
            $new_mobile = substr($data['mobile'], 1);
        } else {
            $new_mobile = $data['mobile'];
        }

        return User::query()->create([
            'name' => $data['name'],
            'email' => $data['email'],
            'mobile' => $new_mobile,
            'gender' => isset($data['gender']) ? $data['gender'] : null,
            'country_id' => $data['country_id'],
            'city_id' => $data['city_id'],
            'password' => Hash::make($data['password'])
        ]);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function verifyCode(Request $request)
    {
        $mobile = $request->mobile;
        if (substr($mobile, 0, 1) == 0) {
            $new_mobile = substr($request->mobile, 1);
        } else {
            $new_mobile = $request->mobile;
        }

        $mobile_code = MobileVerification::query()->where('mobile', $new_mobile)->where('type', 1)->first();
        if ($mobile_code == null) {
            return mainResponse_2(false, __('Not Found'), (object)[], [], 404);
        }

        $rules = [
            'mobile' => 'required|digits_between:8,14',
            'code' => 'required|hash_check:' . @$mobile_code->code,
            'token' => 'required|string|max:255',
            'device' => 'required|in:android,ios',
        ];

        $validation = [
            'code.hash_check' => __('common.hash_check'),
            'mobile.required' => __('common.mobile_required'),
        ];

        $validator = Validator::make($request->all(), $rules, $validation);
        if ($validator->fails()) {
            return mainResponse_2(false, $validator->errors()->first(), [], $validator->errors()->messages());
        }

        $user = User::query()->where('mobile', $new_mobile)->first();
        $user->update([
            'status' => 1
        ]);

        MobileVerification::query()->where('mobile', $new_mobile)->delete();

        MobileToken::query()->updateOrCreate(
            ['user_id' => $user->id, 'token' => $request->token, 'device' => $request->device],
            ['user_id' => $user->id, 'token' => $request->token, 'device' => $request->device]
        );
        $user->setAttribute('token', $user->createToken('LaravelSanctumAuth')->plainTextToken);

        return mainResponse_2(true, __('ok'), $user, [], 200);
    }
}
