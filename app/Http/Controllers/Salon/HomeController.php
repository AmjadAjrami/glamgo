<?php

namespace App\Http\Controllers\Salon;

use App\Http\Controllers\Controller;
use App\Models\Chat;
use App\Models\Clinic;
use App\Models\Doctor;
use App\Models\DoctorCategory;
use App\Models\Notification;
use App\Models\Offer;
use App\Models\PromoCode;
use App\Models\Reservation;
use App\Models\Service;
use App\Models\Setting;
use Carbon\Carbon;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function index()
    {
        $services_count = Service::query()->where('salon_id', auth('salon')->id())->count();
        $reservations_count = Reservation::query()->where('salon_id', auth('salon')->id())->count();
        $promo_codes_count = PromoCode::query()->where('salon_id', auth('salon')->id())->count();

        $reservations_cash = Reservation::query()->where('salon_id', auth('salon')->id())
            ->where('payment_method_id', 1)->where('status', 3)->get();
        $cash_total = 0;
        foreach ($reservations_cash as $cash){
            $cash_total += $cash->total_price_after_code != null ? $cash->total_price_after_code : $cash->total_price;
        }

        $app_percentage_setting = Setting::query()->where('key', 'app_percentage')->first();

        if ($app_percentage_setting){
            $app_percentage = ($cash_total * ($app_percentage_setting->value / 100));
        }else{
            $app_percentage = 0;
        }

        $days = [];
        $reservations = [];

        for ($i = 0; $i < 10; $i++){
            $days[] = Carbon::now()->subDays($i)->format('m/d');
            $date = Carbon::now()->subDays($i)->format('Y-m-d');
            $reservations[] = Reservation::query()->where('salon_id', auth('salon')->id())->where('date', $date)->count();
        }

        return view('salon.home.index', compact('services_count', 'reservations_count', 'promo_codes_count', 'days', 'reservations', 'cash_total', 'app_percentage'));
    }

    /**
     * @return array
     */
    public function get_notifications()
    {
        $notifications = Notification::query()->where('reference_id', auth('salon')->id())
            ->whereIn('type', ['salon_chat_notification', 'salon_reservation_notification'])->orderByDesc('created_at');

        $notifications = $notifications->take(10)->get();
        $notifications_count = $notifications->where('is_seen', 0)->count();

        return ['status' => true, 'notifications' => $notifications, 'notifications_count' => $notifications_count];
    }

    /**
     * @return bool[]
     */
    public function read_notifications()
    {
        $notifications = Notification::query()->where('reference_id', auth('salon')->id())
            ->whereIn('type', ['salon_chat_notification', 'salon_reservation_notification'])->where('is_seen', 0)->update(['is_seen' => 1]);

        return ['status' => true];
    }
}
