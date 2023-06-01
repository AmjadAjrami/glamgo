<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Country;
use App\Models\MobileToken;
use App\Models\Notification;
use App\Models\NotificationTranslation;
use App\Models\NotificationUser;
use App\Models\Transaction;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class UsersController extends Controller
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
        $countries = Country::query()->where('status', 1)->get();
        return view('admin.users.index', compact('countries'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {
        $rules = [
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'mobile' => 'required|unique:users,mobile',
            'password' => 'required',
            'gender' => 'required|in:1,2',
            'dob' => 'required',
            'country_id' => 'required|exists:countries,id',
            'city_id' => 'required|exists:cities,id',
            'image' => 'nullable',
        ];

        $this->validate($request, $rules);

        $data = $request->except('image', 'password');

        if ($request->image) {
            $filename = rand(111111, 999999) . '_' . \Carbon\Carbon::now()->timestamp;
            $filename .= substr($request->image->getClientOriginalName(), strrpos($request->image->getClientOriginalName(), '.'));
            $filename = $request->image->move(public_path('images'), $filename);
            $data['image'] = $filename->getBasename();
        }

        $data['password'] = Hash::make($request->password);

        User::query()->create($data);

        if ($request->ajax()) {
            return response()->json(['status' => true]);
        }

        flash()->success(__('common.created_successfully'));
        return redirect(route('users.index'));
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     * @throws \Illuminate\Validation\ValidationException
     */
    public function update(Request $request, $id)
    {
        $user = User::query()->find($id);

        $rules = [
            'name' => 'required',
            'email' => 'required|email|unique:users,email,' . $id,
            'mobile' => 'required|unique:users,mobile,' . $id,
            'password' => 'nullable',
            'gender' => 'required|in:1,2',
            'dob' => 'required',
            'country_id' => 'required|exists:countries,id',
            'city_id' => 'required|exists:cities,id',
            'image' => 'nullable',
        ];

        $this->validate($request, $rules);

        $data = $request->except('image', 'password');

        if ($request->image) {
            $filename = rand(111111, 999999) . '_' . \Carbon\Carbon::now()->timestamp;
            $filename .= substr($request->image->getClientOriginalName(), strrpos($request->image->getClientOriginalName(), '.'));
            $filename = $request->image->move(public_path('images'), $filename);
            $data['image'] = $filename->getBasename();
        }

        if ($request->password) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        if ($request->ajax()) {
            return response()->json(['status' => true]);
        }

        flash()->success(__('common.updated_successfully'));
        return redirect(route('users.index'));
    }

    /**
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        try {
            $ids = explode(',', $id);
            User::query()->whereIn('id', $ids)->delete();

            $title_ar = 'حالة الحساب';
            $title_en = 'Account Status';
            $message_ar = 'لقد تم حظر حسابك بشكل نهائي من قبل الإدارة';
            $message_en = 'Your account has been permanently banned by the administration';

            $title = app()->getLocale() == 'ar' ? $title_ar : $title_en;
            $message = app()->getLocale() == 'ar' ? $message_ar : $message_en;

            foreach ($ids as $id) {
                $notification = Notification::query()->create([
                    'image' => '',
                    'type' => 'user_activation',
                    'send_date' => Carbon::parse(Carbon::now())->format('Y-m-d'),
                    'reference_id' => $id,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ]);

                foreach (locales() as $key => $value) {
                    NotificationTranslation::query()->insert([
                        'notification_id' => $notification->id,
                        'title' => $key == 'ar' ? $title_ar : $title_en,
                        'message' => $key == 'ar' ? $message_ar : $message_en,
                        'locale' => $key,
                        'created_at' => Carbon::now(),
                        'updated_at' => Carbon::now(),
                    ]);
                }

                NotificationUser::query()->insert([
                    'notification_id' => $notification->id,
                    'user_id' => $id,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ]);
            }

            $android_tokens_user = MobileToken::query()->whereIn('user_id', $ids)->where('device', 'android')->pluck('token');
            $ios_tokens_user = MobileToken::query()->whereIn('user_id', $ids)->where('device', 'ios')->pluck('token');

            fcmNotification($android_tokens_user, 1, $title, $message, $message, 'user_destroy', 'android', 0, null, null, null);
            fcmNotification($ios_tokens_user, 1, $title, $message, $message, 'user_destroy', 'ios', 0, null, null, null);

        } catch (\Exception $e) {
            return response()->json(['status' => false]);
        }
        return response()->json(['status' => true]);
    }

    /**
     * @param $id
     * @return array
     */
    public function update_status($id)
    {
        $user = User::query()->find($id);
        if ($user->status == 1) {
            $user->status = 0;
            $user->update();

            $title_ar = 'حالة الحساب';
            $title_en = 'Account Status';
            $message_ar = 'تم حظر حسابك من قبل الإدارة';
            $message_en = 'Your account has been suspended by the admin';

            $title = app()->getLocale() == 'ar' ? $title_ar : $title_en;
            $message = app()->getLocale() == 'ar' ? $message_ar : $message_en;

            $notification = Notification::query()->create([
                'image' => '',
                'type' => 'user_activation',
                'send_date' => Carbon::parse(Carbon::now())->format('Y-m-d'),
                'reference_id' => $user->id,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);

            foreach (locales() as $key => $value) {
                NotificationTranslation::query()->insert([
                    'notification_id' => $notification->id,
                    'title' => $key == 'ar' ? $title_ar : $title_en,
                    'message' => $key == 'ar' ? $message_ar : $message_en,
                    'locale' => $key,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ]);
            }

            NotificationUser::query()->insert([
                'notification_id' => $notification->id,
                'user_id' => $user->id,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);

            if ($user->notification_active == 1) {
                $android_tokens_user = MobileToken::query()->where('user_id', $user->id)->where('device', 'android')->pluck('token');
                $ios_tokens_user = MobileToken::query()->where('user_id', $user->id)->where('device', 'ios')->pluck('token');

                fcmNotification($android_tokens_user, $notification->id, $title, $message, $message, 'user_activation', 'android', $user->id, null, null, 0);
                fcmNotification($ios_tokens_user, $notification->id, $title, $message, $message, 'user_activation', 'ios', $user->id, null, null, 0);
            }

            return ['success' => true, 'message' => __('common.deactivated_successfully'), 'text' => __('common.activate'),
                'remove' => 'btn btn-warning', 'add' => 'btn btn-dark'];
        } else {
            $user->status = 1;
            $user->update();

            $title_ar = 'حالة الحساب';
            $title_en = 'Account Status';
            $message_ar = 'تم تفعيل حسابك من قبل الإدارة';
            $message_en = 'Your account has been activated by the admin';

            $title = app()->getLocale() == 'ar' ? $title_ar : $title_en;
            $message = app()->getLocale() == 'ar' ? $message_ar : $message_en;

            $notification = Notification::query()->create([
                'image' => '',
                'type' => 'user_activation',
                'send_date' => Carbon::parse(Carbon::now())->format('Y-m-d'),
                'reference_id' => $user->id,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);

            foreach (locales() as $key => $value) {
                NotificationTranslation::query()->insert([
                    'notification_id' => $notification->id,
                    'title' => $key == 'ar' ? $title_ar : $title_en,
                    'message' => $key == 'ar' ? $message_ar : $message_en,
                    'locale' => $key,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ]);
            }

            NotificationUser::query()->insert([
                'notification_id' => $notification->id,
                'user_id' => $user->id,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);

            if ($user->notification_active == 1) {
                $android_tokens_user = MobileToken::query()->where('user_id', $user->id)->where('device', 'android')->pluck('token');
                $ios_tokens_user = MobileToken::query()->where('user_id', $user->id)->where('device', 'ios')->pluck('token');

                fcmNotification($android_tokens_user, $notification->id, $title, $message, $message, 'user_activation', 'android', $user->id, null, null, 1);
                fcmNotification($ios_tokens_user, $notification->id, $title, $message, $message, 'user_activation', 'ios', $user->id, null, null, 1);
            }

            return ['success' => true, 'message' => __('common.activated_successfully'), 'text' => __('common.deactivate'),
                'remove' => 'btn btn-dark', 'add' => 'btn btn-warning'];
        }
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function update_all_status(Request $request)
    {
        $rules = [
            'ids' => 'required',
            'status' => 'required|in:0,1',
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json(['status' => false]);
        }
        try {
            $ids = explode(',', $request->ids);
            User::query()->whereIn('id', $ids)->update(['status' => $request->status]);

            if ($request->status == 1) {
                $title_ar = 'حالة الحساب';
                $title_en = 'Account Status';
                $message_ar = 'تم تفعيل حسابك من قبل الأدمن';
                $message_en = 'Your account has been activated by the admin';

                $title = app()->getLocale() == 'ar' ? $title_ar : $title_en;
                $message = app()->getLocale() == 'ar' ? $message_ar : $message_en;
            } else {
                $title_ar = 'حالة الحساب';
                $title_en = 'Account Status';
                $message_ar = 'تم توقيف حسابك من قبل الأدمن';
                $message_en = 'Your account has been suspended by the admin';

                $title = app()->getLocale() == 'ar' ? $title_ar : $title_en;
                $message = app()->getLocale() == 'ar' ? $message_ar : $message_en;
            }

            foreach ($ids as $id) {
                $notification = Notification::query()->create([
                    'image' => '',
                    'type' => 'user_activation',
                    'send_date' => Carbon::parse(Carbon::now())->format('Y-m-d'),
                    'reference_id' => $id,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ]);

                foreach (locales() as $key => $value) {
                    NotificationTranslation::query()->insert([
                        'notification_id' => $notification->id,
                        'title' => $key == 'ar' ? $title_ar : $title_en,
                        'message' => $key == 'ar' ? $message_ar : $message_en,
                        'locale' => $key,
                        'created_at' => Carbon::now(),
                        'updated_at' => Carbon::now(),
                    ]);
                }

                NotificationUser::query()->insert([
                    'notification_id' => $notification->id,
                    'user_id' => $id,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ]);
            }

            $notified_users = User::query()->where('notification_active', 1)->whereIn('id', $ids)->pluck('id');

            $android_tokens_user = MobileToken::query()->whereIn('user_id', $notified_users)->where('device', 'android')->pluck('token');
            $ios_tokens_user = MobileToken::query()->whereIn('user_id', $notified_users)->where('device', 'ios')->pluck('token');

            fcmNotification($android_tokens_user, $notification->id, $title, $message, $message, 'user_activation', 'android', $notified_users, null, null, $request->status);
            fcmNotification($ios_tokens_user, $notification->id, $title, $message, $message, 'user_activation', 'ios', $notified_users, null, null, $request->status);
        } catch (\Exception $e) {
            return response()->json(['status' => false]);
        }
        return response()->json(['status' => true]);
    }

    /**
     * @param Request $request
     * @return mixed
     * @throws \Exception
     */
    public function indexTable(Request $request)
    {
        $users = User::query()->orderByDesc('created_at');
        return DataTables::of($users)->filter(function ($query) use ($request) {
            if ($request->name) {
                $query->where('name', 'like', '%' . $request->name . '%');
            }
            if ($request->email) {
                $query->where('email', 'like', '%' . $request->email . '%');
            }
            if ($request->mobile) {
                $query->where('mobile', 'like', '%' . $request->mobile . '%');
            }
            if ($request->country_id) {
                $query->where('country_id', $request->country_id);
            }
            if ($request->city_id) {
                $query->where('city_id', $request->city_id);
            }
            if ($request->status) {
                $status = $request->status == 2 ? 0 : 1;
                $query->where('status', $status);
            }
        })->addColumn('action', function ($user) {
            $data_attr = '';
            $data_attr .= 'data-name="' . $user->name . '" ';
            $data_attr .= 'data-email="' . $user->email . '" ';
            $data_attr .= 'data-country_id="' . $user->country_id . '" ';
            $data_attr .= 'data-city_id="' . $user->city_id . '" ';
            $data_attr .= 'data-mobile="' . $user->mobile . '" ';
            $data_attr .= 'data-image="' . $user->image . '" ';
            $data_attr .= 'data-gender="' . $user->gender . '" ';
            $data_attr .= 'data-dob="' . $user->dob . '" ';

            $string = "";

            $string .= '<a class="btn btn-dark" href="' . route('users.wallet', $user->id) . '">' . __('common.wallet') . '</a> ';


            if (auth()->user()->hasAnyPermission(['edit_user_status'])) {
                $string .= '<button onclick="add_remove(' . $user->id . ')" id="btn_' . $user->id . '" class="btn ' . ($user->status == 1 ? 'btn-warning' : 'btn-dark') . '">
                                   ' . ($user->status == 1 ? __('common.deactivate') : __('common.activate')) . '
                            </button>';
            }

            if (auth()->user()->hasAnyPermission(['edit_user'])) {
                $string .= '<button class="bs-tooltip edit_btn" data-bs-placement="top" title="" data-bs-original-title="Edit" data-bs-toggle="modal" data-bs-target="#editUser"
                                data-id="' . $user->id . '" ' . $data_attr . '>
                             <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-edit-2 me-50"><path d="M17 3a2.828 2.828 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5L17 3z"></path></svg>
                            </button>';
            }

            if (auth()->user()->hasAnyPermission(['delete_user'])) {
                $string .= '<button type="button" class="delete-btn bs-tooltip" data-id="' . $user->id . '" title="" data-original-title="Delete" data-placement="top">
                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trash me-50"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg>
                           </button>';
            }

            return $string;
        })->make(true);
    }

    /**
     * @param $id
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function wallet($id)
    {
        $user = User::query()->find($id);
        $transactions = Transaction::query()->where('user_id', $id)->get();
        return view('admin.users.wallet', compact('user', 'transactions'));
    }

    public function update_balance(Request $request, $id)
    {
        $rules = [
            'balance' => 'required',
        ];

        $this->validate($request, $rules);

        $user = User::query()->find($id);

        if ($request->type == 1) {
            $user->balance += $request->balance;

            $title_ar = 'رصيد جديد';
            $title_en = 'New Balance';
            $message_ar = 'تم إضافة رصيد جديد لمحفظتك من قبل الإدارة';
            $message_en = 'A new balance has been added to your wallet by the administration';
        } else {
            if ($request->balance > $user->balance) {
                flash()->error(__('common.less_balance'));
                return redirect()->back();
            }

            $user->balance -= $request->balance;

            $title_ar = 'إزالة رصيد';
            $title_en = 'Remove Balance';
            $message_ar = 'تم إزالة رصيد من محفظتك من قبل الإدارة';
            $message_en = 'A balance has been removed from your wallet by the administration';
        }

        $user->update();

        $title = app()->getLocale() == 'ar' ? $title_ar : $title_en;
        $message = app()->getLocale() == 'ar' ? $message_ar : $message_en;

        $notification = Notification::query()->create([
            'image' => '',
            'type' => 'user_balance',
            'send_date' => Carbon::parse(Carbon::now())->format('Y-m-d'),
            'reference_id' => $user->id,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        foreach (locales() as $key => $value) {
            NotificationTranslation::query()->insert([
                'notification_id' => $notification->id,
                'title' => $key == 'ar' ? $title_ar : $title_en,
                'message' => $key == 'ar' ? $message_ar : $message_en,
                'locale' => $key,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
        }

        NotificationUser::query()->insert([
            'notification_id' => $notification->id,
            'user_id' => $user->id,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        if ($user->notification_active == 1) {
            $android_tokens_user = MobileToken::query()->where('user_id', $user->id)->where('device', 'android')->pluck('token');
            $ios_tokens_user = MobileToken::query()->where('user_id', $user->id)->where('device', 'ios')->pluck('token');

            fcmNotification($android_tokens_user, $notification->id, $title, $message, $message, 'user_balance', 'android', $user->id, null, null, 1);
            fcmNotification($ios_tokens_user, $notification->id, $title, $message, $message, 'user_balance', 'ios', $user->id, null, null, 1);
        }

        flash()->success(__('common.done_successfully'));
        return redirect()->back();
    }
}
