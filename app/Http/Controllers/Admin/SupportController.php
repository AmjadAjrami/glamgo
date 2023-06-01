<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\Chat;
use App\Models\Clinic;
use App\Models\MobileToken;
use App\Models\Notification;
use App\Models\NotificationTranslation;
use App\Models\NotificationUser;
use App\Models\Salon;
use App\Models\Support;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class SupportController extends Controller
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
        $supports = Support::query()->whereHas('user', function ($q){
            $q->whereNull('deleted_at');
        })->get();

        $supports = $supports->sortByDesc('created_at')->sortByDesc('last_message_date');

        return view('admin.support.index', compact('supports'));
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
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * @param $id
     * @return array
     */
    public function user_details($id)
    {
        $user = User::query()->find($id);
        return ['status' => true, 'user' => $user];
    }

    /**
     * @param $key
     * @return bool[]
     */
    public function update_message_read($key)
    {
        Support::query()->where('firebase_id', $key)->update([
            'admin_unread_messages' => 0,
        ]);

        return ['status' => true];
    }

    /**
     * @param Request $request
     * @return bool[]
     * @throws \Illuminate\Validation\ValidationException
     */
    public function send_message_notification(Request $request)
    {
        $rules = [
            'user_id' => 'required',
            'message' => 'required',
            'firebase_id' => 'required',
        ];

        $this->validate($request, $rules);

        $admin = Admin::query()->find(auth('admin')->id());

        $title = app()->getLocale() == 'ar' ? 'رسالة دعم فني جديدة' : 'New Support Message';
        $message = app()->getLocale() == 'ar' ? 'رسالة دعم فني جديدة' : 'New Support Message';

        $image = url('/icons/support.png');

        $support = Support::query()->where('firebase_id', $request->firebase_id)->first();
        if (!$support){
            Support::query()->create([
                'admin_id' => auth('admin')->id(),
                'user_id' => $request->user_id,
                'firebase_id' => $request->firebase_id,
                'last_message' => $request->message,
                'last_message_date' => Carbon::now()->format('Y-m-d A g:i'),
                'user_unread_messages' => 1,
            ]);
        }else{
            $support->update([
                'last_message' => $request->message,
                'last_message_date' => Carbon::now()->format('Y-m-d A g:i'),
                'user_unread_messages' => $support->user_unread_messages + 1,
            ]);
        }

        $notification = Notification::query()->create([
            'image' => $image,
            'type' => 'new_support_message',
            'send_date' => Carbon::parse(Carbon::now())->format('Y-m-d'),
            'is_seen' => 0,
            'reference_id' => 0,
            'user_id' => $support->user_id,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        NotificationUser::query()->insert([
            'user_id' => $support->user_id,
            'notification_id' => $notification->id,
            'is_seen' => 0,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        foreach (locales() as $key => $value){
            NotificationTranslation::query()->insert([
                'notification_id' => $notification->id,
                'title' => $key == 'ar' ? 'رسالة دعم فني جديدة' : 'New Support Message',
                'message' => $key == 'ar' ? 'رسالة دعم فني جديدة' : 'New Support Message',
                'locale' => $key,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
        }

        $user = User::query()->find($request->user_id);

        if ($user->notification_active == 1){
            $android_tokens_user = MobileToken::query()->where('user_id', $request->user_id)->where('device', 'android')->pluck('token');
            $ios_tokens_user = MobileToken::query()->where('user_id', $request->user_id)->where('device', 'ios')->pluck('token');

            fcmNotification($android_tokens_user, $notification->id, $title, $request->message, $request->message, 'new_support_message', 'android',
                null, $image, $support->firebase_id, null, null, null, null,
                null, null, null, null, 1);
            fcmNotification($ios_tokens_user, $notification->id, $title, $request->message, $request->message, 'new_support_message', 'ios',
                null, $image, $support->firebase_id, null, null, null, null,
                null, null, null, null, 1);
        }

        return ['status' => true];
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    /**
     * @param Request $request
     * @return array
     * @throws \ErrorException
     */
    public function translate_message(Request $request)
    {
        $tr = new \Stichoza\GoogleTranslate\GoogleTranslate();
        $tr->setSource('en');
        $tr->setSource();
        $tr->setTarget('ar');

        $translated_message_ar = $tr->translate($request->message);

        $tr->setSource('ar');
        $tr->setSource();
        $tr->setTarget('en');

        $translated_message_en = $tr->translate($request->message);

        return ['translated_message_ar' => $translated_message_ar, 'translated_message_en' => $translated_message_en];
    }

    /**
     * @param Request $request
     * @return array[]
     * @throws \ErrorException
     */
    public function translation_messages(Request $request)
    {
        $tr = new \Stichoza\GoogleTranslate\GoogleTranslate();
        $messages = [];
        if ($request->translation_type == 1){
            for ($i = 0; $i < count($request->messages); $i++){
                $tr->setSource('en');
                $tr->setSource();
                $tr->setTarget('ar');

                $messages[] = [
                    'message' => $tr->translate($request->messages[$i]),
                    'position' => $request->messages_positions[$i],
                ];
            }
        }else{
            for ($i = 0; $i < count($request->messages); $i++){
                $tr->setSource('ar');
                $tr->setSource();
                $tr->setTarget('en');

                $messages[] = [
                    'message' => $tr->translate($request->messages[$i]),
                    'position' => $request->messages_positions[$i],
                ];
            }
        }

        return ['messages' => $messages];
    }
}
