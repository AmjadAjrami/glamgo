<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\City;
use App\Models\Clinic;
use App\Models\MakeupArtist;
use App\Models\Notification;
use App\Models\Offer;
use App\Models\Order;
use App\Models\Product;
use App\Models\Reservation;
use App\Models\Salon;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function index()
    {
        $users_count = User::query()->count();
        $men_salons_count = Salon::query()->where('type', 1)->count();
        $women_salons_count = Salon::query()->where('type', 2)->count();
        $artists_count = MakeupArtist::query()->count();
        $cities_count = City::query()->count();
        $offers_count = Offer::query()->count();
        $products_count = Product::query()->count();
        $reservations_count = Reservation::query()->count();
        $orders_count = Order::query()->count();

        $days = [];
        $reservations = [];
        $users_chart_count = [];

        for ($i = 0; $i < 10; $i++){
            $days[] = Carbon::now()->subDays($i)->format('m/d');
            $date = Carbon::now()->subDays($i)->format('Y-m-d');
            $user_date = Carbon::now()->subDays($i)->format('Y-m-d g:i:s');
            $reservations[] = Reservation::query()->where('date', $date)->count();
            $users_chart_count[] = User::query()->whereRaw('DATE(created_at) = ?', Carbon::parse($user_date)->toDateString())->count();
        }

        $all_cities = City::query()->get();
        $cities = [];
        $users = [];
        $colors = [];

        foreach ($all_cities as $city){
            $count = User::query()->where('city_id', $city->id)->count();
            $cities[] = $city->name;
            $users[] = $count;
            $colors[] = '#' . str_pad(dechex(mt_rand(0, 0xFFFFFF)), 6, '0', STR_PAD_LEFT);
        }

        return view('admin.home.index', compact('users_count', 'men_salons_count', 'women_salons_count', 'artists_count', 'cities_count', 'offers_count',
                'days', 'reservations', 'users_chart_count', 'cities', 'users', 'colors', 'products_count', 'reservations_count', 'orders_count'));
    }

    /**
     * @return array
     */
    public function get_notifications()
    {
        $notifications = Notification::query()->where('reference_id', 0)
            ->whereIn('type', ['order_notification', 'chat_support_notification', 'contact_us_notification'])->orderByDesc('created_at');

        $notifications = $notifications->take(10)->get();
        $notifications_count = $notifications->where('is_seen', 0)->count();

        return ['status' => true, 'notifications' => $notifications, 'notifications_count' => $notifications_count];
    }

    /**
     * @return bool[]
     */
    public function read_notifications()
    {
        $notifications = Notification::query()->where('reference_id', 0)
            ->whereIn('type', ['order_notification', 'chat_support_notification', 'contact_us_notification'])->where('is_seen', 0)->update(['is_seen' => 1]);

        return ['status' => true];
    }
}
