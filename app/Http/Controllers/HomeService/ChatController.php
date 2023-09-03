<?php

namespace App\Http\Controllers\HomeService;

use App\Http\Controllers\Controller;
use App\Models\Chat;
use App\Models\HomeService;
use App\Models\MobileToken;
use App\Models\Notification;
use App\Models\NotificationTranslation;
use App\Models\NotificationUser;
use App\Models\Salon;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ChatController extends Controller
{
    /**
     *
     */
    public function __construct()
    {
        $this->middleware('auth:home_service');
    }

    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function index()
    {
        $chats = Chat::query()->where('provider_id', auth('home_service')->id())->where('provider_type', 3)->whereHas('user', function ($q){
            $q->whereNull('deleted_at');
        })->get();

        $chats = $chats->sortByDesc('created_at')->sortByDesc('last_message_date');

        return view('home_service.chats.index', compact('chats'));
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
        Chat::query()->where('firebase_id', $key)->update([
            'provider_unread_messages' => 0,
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

        $home_service = HomeService::query()->find(auth('home_service')->id());

        $title = app()->getLocale() == 'ar' ? 'رسالة جديدة' : 'New Message';
        $message = app()->getLocale() == 'ar' ? 'رسالة جديدة من قبل ' . $home_service->translate('ar')->name : 'New Message From ' . $home_service->translate('en')->name;

        $chat = Chat::query()->where('firebase_id', $request->firebase_id)->first();
        if (!$chat){
            Chat::query()->create([
                'provider_id' => auth('home_service')->id(),
                'provider_type' => 3,
                'user_id' => $request->user_id,
                'firebase_id' => $request->firebase_id,
                'last_message' => $request->message,
                'last_message_date' => Carbon::now()->format('Y-m-d g:i A'),
                'user_unread_messages' => 1,
            ]);
        }else{
            $chat->update([
                'last_message' => $request->message,
                'last_message_date' => Carbon::now()->format('Y-m-d g:i A'),
                'user_unread_messages' => $chat->user_unread_messages + 1,
            ]);
        }

        $notification = Notification::query()->create([
            'image' => $chat->provider->image,
            'type' => 'new_home_service_message',
            'send_date' => Carbon::parse(Carbon::now())->format('Y-m-d'),
            'is_seen' => 0,
            'reference_id' => $chat->provider->id,
            'user_id' => $chat->user_id,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        NotificationUser::query()->insert([
            'user_id' => $chat->user_id,
            'notification_id' => $notification->id,
            'is_seen' => 0,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        foreach (locales() as $key => $value){
            NotificationTranslation::query()->insert([
                'notification_id' => $notification->id,
                'title' => $key == 'ar' ? 'رسالة جديدة' : 'New Message',
                'message' => $key == 'ar' ? 'رسالة جديدة من قبل ' . $home_service->translate('ar')->name : 'New Message From ' . $home_service->translate('en')->name,
                'locale' => $key,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
        }

        $user = User::query()->find($request->user_id);

        if ($user->notification_active == 1){
            $android_tokens_user = MobileToken::query()->where('user_id', $request->user_id)->where('device', 'android')->pluck('token');
            $ios_tokens_user = MobileToken::query()->where('user_id', $request->user_id)->where('device', 'ios')->pluck('token');

            fcmNotification($android_tokens_user, $notification->id, $title, $message, $message, 'new_message', 'android',
                $chat->id, null, $chat->firebase_id, null, null, null, null,
                $home_service->id, $home_service->name, 'home_service', $home_service->image);
            fcmNotification($ios_tokens_user, $notification->id, $title, $message, $message, 'new_message', 'ios',
                $chat->id, null, $chat->firebase_id, null, null, null, null,
                $home_service->id, $home_service->name, 'home_service', $home_service->image);
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
