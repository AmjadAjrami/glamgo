<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MakeupArtist;
use App\Models\MobileToken;
use App\Models\Notification;
use App\Models\NotificationTranslation;
use App\Models\NotificationUser;
use App\Models\Reservation;
use App\Models\Salon;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class ReservationController extends Controller
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
        $salons = Salon::query()->get();
        $artists = MakeupArtist::query()->get();
        return view('admin.reservations.index', compact('salons', 'artists'));
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
     * @throws \Illuminate\Validation\ValidationException
     */
    public function update_reservation_status(Request $request)
    {
        $rules = [
            'reservation_id' => 'required',
            'status' => 'required',
        ];

        $this->validate($request, $rules);

        $reservation = Reservation::query()->find($request->reservation_id);
        if ($request->status == 5){
            $reservation->update([
                'status' => $request->status,
                'reject_reason' => $request->reject_reason,
            ]);
        }else{
            $reservation->update([
                'status' => $request->status,
            ]);
        }

        if ($reservation->salon_id != null){
            $provider = Salon::query()->find($reservation->salon_id);
        }else{
            $provider = MakeupArtist::query()->find($reservation->makeup_artist_id);
        }

        $title = app()->getLocale() == 'ar' ? 'حالة الحجز' : 'Update Reservation Status';
        $notification_title_ar = 'حالة الحجز';
        $notification_title_en = 'Reservation Status';
        if ($request->status == 2){
            $notification_message_ar = 'تم تأكيد حجزك من قبل ' . $provider->translate('ar')->name . ' يمكنك زيارتها في موعد حجزك ';
            $notification_message_en = 'Your reservation has been confirmed by ' . $provider->translate('en')->name . ' that you can visit at the time of your reservation';
        }elseif ($request->status == 3){
            $notification_message_ar = 'اصبح طلبك مكتمل الآن ، شكرا لك لاستخدام تطبيق جلامجو';
            $notification_message_en = 'Your order is now complete, thank you for using my Glamgo';
        }elseif ($request->status == 5){
            $notification_message_ar = 'لقد تم رفض حجزك من قبل '  . $provider->translate('ar')->name .  ' انقر للاطلاع على سبب الرفض ';
            $notification_message_en = 'Your reservation has been rejected by ' . $provider->translate('en')->name . ' Click to see the reason for rejection';
        }else{
            $notification_message_ar = 'تم تعديل حالة الحجز رقم #' . $reservation->reservation_number . ' الي ' . $reservation->status_text;
            $notification_message_en = 'The status of reservation #' . $reservation->reservation_number . ' has been updated to ' . $reservation->status_text;
        }

        $user = User::query()->find($reservation->user_id);

        $message = app()->getLocale() == 'ar' ? $notification_message_ar : $notification_message_en;

        $notification = Notification::query()->create([
            'image' => @$provider->image,
            'type' => 'reservation_status',
            'send_date' => Carbon::parse(Carbon::now())->format('Y-m-d'),
            'reference_id' => $reservation->id,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        foreach (locales() as $key => $value){
            NotificationTranslation::query()->insert([
                'notification_id' => $notification->id,
                'title' => $key == 'ar' ? $notification_title_ar : $notification_title_en,
                'message' => $key == 'ar' ? $notification_message_ar : $notification_message_en,
                'locale' => $key,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
        }

        NotificationUser::query()->insert([
            'notification_id' => $notification->id,
            'user_id' => $reservation->user_id,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        if ($user->notification_active == 1){
            $android_tokens_user = MobileToken::query()->where('user_id', $reservation->user_id)->where('device', 'android')->pluck('token');
            $ios_tokens_user = MobileToken::query()->where('user_id', $reservation->user_id)->where('device', 'ios')->pluck('token');

            fcmNotification($android_tokens_user, $notification->id, $title, $message, $message, 'reservation_status', 'android', $reservation->id, $reservation->id);
            fcmNotification($ios_tokens_user, $notification->id, $title, $message, $message, 'reservation_status', 'ios', $reservation->id, $reservation->id);
        }

        return ['status' => true];
    }


    /**
     * @param Request $request
     * @return mixed
     * @throws \Exception
     */
    public function indexTable(Request $request)
    {
        $reservations = Reservation::query()->whereNotNull('date')->orderByDesc('created_at');
        return DataTables::of($reservations)->filter(function ($query) use ($request) {
            if ($request->name) {
                $ids = User::query()->where('name', 'like', '%' . $request->name . '%')->pluck('id')->toArray();
                $query->whereIn('user_id', $ids);
            }
            if ($request->email) {
                $ids = User::query()->where('email', 'like', '%' . $request->email . '%')->pluck('id')->toArray();
                $query->whereIn('user_id', $ids);
            }
            if ($request->mobile) {
                $ids = User::query()->where('mobile', 'like', '%' . $request->mobile . '%')->pluck('id')->toArray();
                $query->whereIn('user_id', $ids);
            }
            if ($request->salon_id) {
                $query->where('salon_id', $request->salon_id);
            }
            if ($request->artist_id) {
                $query->where('makeup_artist_id', $request->artist_id);
            }
            if ($request->date) {
                $date = Carbon::parse($request->date)->format('Y-m-d');
                $query->where('date', $date);
            }
            if ($request->status) {
                $query->where('status', $request->status);
            }
        })->make(true);
    }

    /**
     * @param Request $request
     * @return array
     * @throws \Illuminate\Validation\ValidationException
     */
    public function reservation_details(Request $request)
    {
        $rules = [
            'reservation_id' => 'required',
        ];

        $this->validate($request, $rules);

        $reservation = Reservation::query()->find($request->reservation_id);

        return ['status' => true, 'reservation' => $reservation, 'items' => $reservation->items];
    }

    /**
     * @param $id
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function ticket_template($id)
    {
        $reservation = Reservation::query()->find($id);

        return view('ticket_template', compact('reservation'));
    }
}
