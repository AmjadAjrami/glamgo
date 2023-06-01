<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\City;
use App\Models\MobileToken;
use App\Models\Notification;
use App\Models\NotificationTranslation;
use App\Models\NotificationUser;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class NotificationController extends Controller
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
        $cities = City::query()->where('status', 1)->get();
        $users = User::query()->get();
        return view('admin.notifications.index', compact('cities', 'users'));
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
            'device_type' => 'nullable',
        ];

        foreach (locales() as $key => $value) {
            $rules['title_' . $key] = 'required|string';
            $rules['message_' . $key] = 'required|string';
        }

        $this->validate($request, $rules);

        if ($request->notification_according_to == 1) {
            if ($request->all_cities) {
                $users = User::query();
            } else {
                $users = User::query()->whereIn('city_id', $request->city_id);
            }
        } elseif ($request->notification_according_to == 2) {
            if ($request->all_users) {
                $users = User::query();
            } else {
                $users = User::query()->whereIn('id', $request->user_id);
            }
        } else {
            $users = User::query();
        }

        $image = url('/icons/public.png');

        $notification = Notification::query()->create([
            'image' => $image,
            'type' => 'admin_notification',
            'send_date' => Carbon::now()->format('Y-m-d'),
            'reference_id' => null,
        ]);

        foreach ($users->pluck('id') as $user) {
            NotificationUser::query()->insert([
                'user_id' => $user,
                'notification_id' => $notification->id,
                'is_seen' => 0,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
        }

        foreach (locales() as $key => $value) {
            NotificationTranslation::query()->insert([
                'notification_id' => $notification->id,
                'title' => $request->get('title_' . $key),
                'message' => $request->get('message_' . $key),
                'locale' => $key,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
        }

        $notified_users = $users->where('notification_active', 1)->pluck('id');

        if ($request->device_type == 1) {
            $android_users = MobileToken::query()->where('device', 'android')->whereIn('user_id', $notified_users)->pluck('token');
            fcmNotification($android_users, 1, $request->get('title_' . app()->getLocale()), $request->get('message_' . app()->getLocale()),
                $request->get('message_' . app()->getLocale()), 'admin_notification', 'android', null, $image);
        } elseif ($request->device_type == 2) {
            $ios_users = MobileToken::query()->where('device', 'ios')->whereIn('user_id', $notified_users)->pluck('token');
            fcmNotification($ios_users, 1, $request->get('title_' . app()->getLocale()), $request->get('message_' . app()->getLocale()),
                $request->get('message_' . app()->getLocale()), 'admin_notification', 'ios', null, $image);
        } else {
            $android_users = MobileToken::query()->where('device', 'android')->whereIn('user_id', $notified_users)->pluck('token');
            $ios_users = MobileToken::query()->where('device', 'ios')->whereIn('user_id', $notified_users)->pluck('token');

            fcmNotification($android_users, 1, $request->get('title_' . app()->getLocale()), $request->get('message_' . app()->getLocale()),
                $request->get('message_' . app()->getLocale()), 'admin_notification', 'android', null, $image);
            fcmNotification($ios_users, 1, $request->get('title_' . app()->getLocale()), $request->get('message_' . app()->getLocale()),
                $request->get('message_' . app()->getLocale()), 'admin_notification', 'ios', null, $image);
        }

        if ($request->ajax()) {
            return response()->json(['status' => true]);
        }

        flash()->success(__('common.updated_successfully'));
        return redirect(route('notifications.index'));
    }

    /**
     * @param $id
     * @return array
     */
    public function show($id)
    {
        $notification = Notification::query()->find($id);
        return ['status' => true, 'notification' => $notification];
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
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        try {
            NotificationUser::query()->whereIn('notification_id', explode(',', $id))->delete();
            NotificationTranslation::query()->whereIn('notification_id', explode(',', $id))->delete();
            Notification::query()->whereIn('id', explode(',', $id))->delete();
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
        $notifications = Notification::query()->where('type', 'admin_notification')->orderByDesc('created_at');
        return DataTables::of($notifications)->filter(function ($query) use ($request) {

        })->addColumn('action', function ($notification) {
            $data_attr = '';
            foreach (locales() as $key => $value) {
                $data_attr .= 'data-title_' . $key . '="' . $notification->translate($key)->title . '" ';
                $data_attr .= 'data-message_' . $key . '="' . $notification->translate($key)->message . '" ';
            }
            $data_attr .= 'data-clinic_id="' . $notification->clinic_id . '" ';

            $string = '';

            $string .= '<button type="button" class="delete-btn bs-tooltip" data-id="' . $notification->id . '" title="" data-original-title="Delete" data-placement="top">
                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trash me-50"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg>
                           </button>';

            $string .= '<button class="btn btn-info edit_btn" data-bs-placement="top" title="" data-bs-original-title="Edit" data-bs-toggle="modal" data-bs-target="#editUser"
                                data-id="' . $notification->id . '" ' . $data_attr . '>
                                '. __('common.details') .'
                            </button>';

            return $string;
        })->make(true);
    }
}
