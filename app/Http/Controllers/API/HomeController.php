<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use App\Models\BookingTime;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Category;
use App\Models\Chat;
use App\Models\City;
use App\Models\ContactUs;
use App\Models\Country;
use App\Models\Favorite;
use App\Models\Intro;
use App\Models\MakeupArtist;
use App\Models\MakeupArtistTranslation;
use App\Models\Notification;
use App\Models\NotificationTranslation;
use App\Models\NotificationUser;
use App\Models\Offer;
use App\Models\Order;
use App\Models\OrderStatus;
use App\Models\Page;
use App\Models\PaymentMethod;
use App\Models\Product;
use App\Models\ProductTranslation;
use App\Models\PromoCode;
use App\Models\Reservation;
use App\Models\ReservationItem;
use App\Models\Salon;
use App\Models\SalonTranslation;
use App\Models\Service;
use App\Models\ServiceType;
use App\Models\Setting;
use App\Models\Support;
use App\Models\Transaction;
use App\Models\User;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class HomeController extends Controller
{
    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function intros()
    {
        $intros = Intro::query()->where('status', 1)->get();
        return mainResponse(true, __('ok'), $intros, [], 200);
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function app_config()
    {
        $settings = Setting::query()->whereNotNull('value')->whereNotIn('key', [
            'evacuation_responsibility', 'vat', 'app_percentage'
        ])->get();
        return mainResponse_2(true, __('ok'), $settings, [], 200);
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function social_media_links()
    {
        $settings = Setting::query()->whereNotNull('value')->whereNotIn('key', [
            'evacuation_responsibility', 'vat', 'app_percentage', 'email', 'mobile', 'website'
        ])->get();
        return mainResponse_2(true, __('ok'), $settings, [], 200);
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function countries()
    {
        $countries = Country::query()->where('status', 1)->orderBy('position', 'asc')->get()->makeHidden(['country_cities']);
        return mainResponse(true, __('ok'), $countries, [], 200);
    }

    /**
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function country_cities($id)
    {
        $cities = City::query()->where('status', 1)->where('country_id', $id)->join('city_translations', 'city_translations.city_id', '=', 'cities.id')
            ->select('city_translations.name as city_name', 'cities.*')->where('city_translations.locale', app()->getLocale())
            ->orderBy('city_name', 'asc')->get();

        return mainResponse(true, __('ok'), $cities, [], 200);
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function privacy_policy()
    {
        $page = Page::query()->where('type', 3)->get()->first();
        return mainResponse_2(true, __('ok'), $page, [], 200);
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function terms()
    {
        $page = Page::query()->where('type', 2)->get()->first();
        return mainResponse_2(true, __('ok'), $page, [], 200);
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function about_us()
    {
        $page = Page::query()->where('type', 1)->get()->first();
        return mainResponse_2(true, __('ok'), $page, [], 200);
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function payment_methods()
    {
        $methods = PaymentMethod::query()->where('status', 1)->get();
        return mainResponse_2(true, __('ok'), $methods, [], 200);
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function charge_wallet_payment_methods()
    {
        $methods = PaymentMethod::query()->whereNotIn('id', [1, 2])->where('status', 1)->get();
        return mainResponse_2(true, __('ok'), $methods, [], 200);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function home(Request $request)
    {
        $rules = [
            'type' => 'required',
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return mainResponse(false, $validator->errors()->first(), (object)[], $validator->errors()->messages(), 200);
        }

        $lat = $request->lat;
        $lng = $request->lng;

        $data = [];
        $now_date = Carbon::now()->format('Y-m-d');

        $banners = Banner::query()->where('status', 1)->whereDate('start_date', '<=', $now_date)->whereDate('end_date', '>=', $now_date)->where('type', $request->type)->get();
        $categories = Category::query()->where('status', 1)->where('type', $request->type)->get();
        if ($request->lat && $request->lng) {
            $nearest_salons = Salon::query()->where('status', 1)->where('type', $request->type)->select("salons.*", DB::raw("6367 * acos(cos(radians(" . $lat . "))
                        * cos(radians(salons.lat))
                        * cos(radians(salons.lng) - radians(" . $lng . "))
                        + sin(radians(" . $lat . "))
                        * sin(radians(salons.lat))) AS nearest_distance"))
                ->having('nearest_distance', '<', 1000)
                ->orderBy('nearest_distance', 'asc')
                ->get()->makeHidden(['in_salon_service_types', 'home_service_types']);
        } else {
            $nearest_salons = [];
        }

        $salons = Salon::query()->where('status', 1)->where('type', $request->type)->orderByDesc('created_at')->get()->makeHidden(['in_salon_service_types', 'home_service_types'])->take(4);
        $artists = MakeupArtist::query()->where('status', 1)->get()->makeHidden(['in_salon_service_types', 'home_service_types'])->take(4);
        if ($request->type == 2) {
            $offers = Offer::query()->where('status', 1)->whereHas('salon', function ($q){
                $q->where('deleted_at', null);
            })->orWhere('status', 1)->whereHas('artist', function ($q){
                $q->where('deleted_at', null);
            })->get()->makeHidden([
                'salon_id', 'makeup_artist_id', 'category_id', 'price', 'discount_price', 'add_time', 'salon_name', 'artist_name', 'title', 'description'
            ]);
        } else {
            $offers = [];
        }

        if ($request->type == 3) {
            $products = Product::query()->where('status', 1);
            if ($request->category_id || $request->category_id != 0) {
                $products = $products->where('category_id', $request->category_id);
            }
            $products = $products->get()->makeHidden(['images']);
        } else {
            $products = [];
        }

        if (count($banners) > 0) {
            $data[] = [
                'id' => 1,
                'type' => 'banners',
                'section_type' => 'banner',
                'title' => __('common.banners'),
                'position' => 1,
                'items' => $banners,
            ];
        }
        if (count($categories) > 0) {
            $data[] = [
                'id' => 2,
                'type' => 'categories',
                'section_type' => 'category',
                'title' => __('common.categories'),
                'position' => 2,
                'items' => $categories,
            ];
        }
        if (count($nearest_salons) > 0) {
            $data[] = [
                'id' => 3,
                'type' => 'nearest_salons',
                'section_type' => 'salon',
                'title' => __('common.nearest_salons'),
                'position' => 3,
                'items' => $nearest_salons,
            ];
        }
        if (count($salons) > 0) {
            $data[] = [
                'id' => 4,
                'type' => 'salons',
                'section_type' => 'salon',
                'title' => __('common.news_salons'),
                'position' => 4,
                'items' => $salons,
            ];
        }
        if (count($offers) > 0) {
            $data[] = [
                'id' => 5,
                'type' => 'offers',
                'section_type' => 'offer',
                'title' => __('common.best_offers'),
                'position' => 5,
                'items' => $offers,
            ];
        }
        if ($request->type == 2) {
            if (count($artists) > 0) {
                $data[] = [
                    'id' => 6,
                    'type' => 'artists',
                    'section_type' => 'artist',
                    'title' => __('common.artists'),
                    'position' => 6,
                    'items' => $artists,
                ];
            }
        }
        if ($request->type == 3) {
            if (count($products) > 0) {
                $data[] = [
                    'id' => 7,
                    'type' => 'products',
                    'section_type' => 'product',
                    'title' => __('common.products'),
                    'position' => 7,
                    'items' => $products,
                ];
            }
        }

        if (auth()->check() == true) {
            $notifications_count = NotificationUser::query()->where('user_id', auth('sanctum')->id())
                ->whereHas('notification_data')->where('is_seen', 0)->count();
        } else {
            $notifications_count = 0;
        }

        $result = [
            'notifications_count' => $notifications_count,
            'chat_count' => 0,
            'items' => $data
        ];

        return mainResponse(true, __('ok'), $result, [], 200);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function home_search(Request $request)
    {
        $rules = [
            'name' => 'required',
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return mainResponse(false, $validator->errors()->first(), (object)[], $validator->errors()->messages(), 200);
        }

        $salons_ids = SalonTranslation::query()->where('name', 'like', '%' . $request->name . '%')->pluck('salon_id');
        $salons = Salon::query()->where('status', 1)->whereIn('id', $salons_ids)->get();

        $artists_ids = MakeupArtistTranslation::query()->where('name', 'like', '%' . $request->name . '%')->pluck('makeup_artist_id');
        $artists = MakeupArtist::query()->where('status', 1)->whereIn('id', $artists_ids)->get();

        $result = [];

        foreach ($salons as $salon) {
            $result[] = [
                'id' => $salon->id,
                'name' => $salon->name,
                'cover_image' => $salon->image,
                'image' => $salon->image,
                'categories' => $salon->categories,
                'type' => 'salon',
            ];
        }

        foreach ($artists as $artist) {
            $result[] = [
                'id' => $artist->id,
                'name' => $artist->name,
                'cover_image' => $artist->image,
                'image' => $artist->image,
                'categories' => $artist->categories,
                'type' => 'artist',
            ];
        }

        return mainResponse(true, __('ok'), $result, [], 200);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function categories(Request $request)
    {
        $categories = Category::query()->where('type', $request->type)->where('status', 1)->get();
        return mainResponse(true, __('ok'), $categories, [], 200);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function salons(Request $request)
    {
        $rules = [
            'id' => 'nullable',
            'type' => 'required',
            'category_id' => 'nullable|exists:categories,id|array',
            'name' => 'nullable',
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return mainResponse(false, $validator->errors()->first(), (object)[], $validator->errors()->messages(), 200);
        }

        $lat = $request->lat;
        $lng = $request->lng;

        if ($request->id == 3) {
            if ($request->lat && $request->lng) {
                $salons = Salon::query()->where('status', 1)->where('type', $request->type)->select("salons.*", DB::raw("6367 * acos(cos(radians(" . $request->lat . "))
                        * cos(radians(salons.lat))
                        * cos(radians(salons.lng) - radians(" . $request->lng . "))
                        + sin(radians(" . $request->lat . "))
                        * sin(radians(salons.lat))) AS nearest_distance"))
                    ->having('nearest_distance', '<', 1000);
            }
        } else {
            $salons = Salon::query()->where('status', 1)->where('type', $request->type);
        }

        if ($request->name) {
            $salons_ids = SalonTranslation::query()->where('name', 'like', '%' . $request->name . '%')->pluck('salon_id');
            $salons = $salons->whereIn('id', $salons_ids);
        }

        if ($request->category_id) {
            $salons = $salons->whereHas('salon_categories', function ($q) use ($request) {
                $q->whereIn('category_id', $request->category_id);
            });
        }

        if ($request->filter_nearest) {
            if ($request->lat && $request->lng) {
                $salons = $salons->select("salons.*", DB::raw("6367 * acos(cos(radians(" . $lat . "))
                        * cos(radians(salons.lat))
                        * cos(radians(salons.lng) - radians(" . $lng . "))
                        + sin(radians(" . $lat . "))
                        * sin(radians(salons.lat))) AS nearest_distance"))
                    ->having('nearest_distance', '<', 1000)->get()->sortBy('nearest_distance')->values();
            }
        } else {
            if ($request->id == 3) {
                if ($request->lat && $request->lng) {
                    $salons = $salons->get()->sortBy('distance_int')->values();
                } else {
                    $salons = [];
                }
            } else {
                $salons = $salons->orderByDesc('created_at')->get();
            }
        }

        return mainResponse(true, __('ok'), $salons->makeHidden(['in_salon_service_types', 'home_service_types']), [], 200);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function nearest_salons(Request $request)
    {
        $rules = [
            'type' => 'required',
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return mainResponse(false, $validator->errors()->first(), (object)[], $validator->errors()->messages(), 200);
        }

        $lat = $request->lat;
        $lng = $request->lng;

        if ($request->lat && $request->lng) {
            $nearest_salons = Salon::query()->where('status', 1)->where('type', $request->type)->select("salons.*", DB::raw("6367 * acos(cos(radians(" . $lat . "))
                        * cos(radians(salons.lat))
                        * cos(radians(salons.lng) - radians(" . $lng . "))
                        + sin(radians(" . $lat . "))
                        * sin(radians(salons.lat))) AS nearest_distance"))
                ->having('nearest_distance', '<', 1000)
                ->orderBy('nearest_distance', 'asc')
                ->get()->makeHidden(['in_salon_service_types', 'home_service_types']);
        } else {
            $nearest_salons = [];
        }

        return mainResponse(true, __('ok'), $nearest_salons, [], 200);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function salon_details(Request $request)
    {
        $rules = [
            'salon_id' => 'required|exists:salons,id',
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return mainResponse(false, $validator->errors()->first(), (object)[], $validator->errors()->messages(), 200);
        }

        $salon = Salon::query()->where('status', 1)->with('gallery')->find($request->salon_id)->makeVisible(['gallery']);

        return mainResponse(true, __('ok'), $salon, [], 200);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function artists(Request $request)
    {
        $rules = [
            'id' => 'nullable',
            'category_id' => 'nullable|array',
            'name' => 'nullable',
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return mainResponse(false, $validator->errors()->first(), (object)[], $validator->errors()->messages(), 200);
        }

        $artists = MakeupArtist::query()->where('status', 1);

        if ($request->name) {
            $artists_ids = MakeupArtistTranslation::query()->where('name', 'like', '%' . $request->name . '%')->pluck('makeup_artist_id');
            $artists = $artists->whereIn('id', $artists_ids);
        }

        if ($request->category_id) {
            if ($request->category_id[0] != 0) {
                $artists = $artists->whereHas('makeup_artist_categories', function ($q) use ($request) {
                    $q->whereIn('category_id', $request->category_id);
                });
            }
        }

        $artists = $artists->get();

        return mainResponse(true, __('ok'), $artists, [], 200);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function artist_details(Request $request)
    {
        $rules = [
            'artist_id' => 'required|exists:makeup_artists,id',
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return mainResponse(false, $validator->errors()->first(), (object)[], $validator->errors()->messages(), 200);
        }

        $artist = MakeupArtist::query()->find($request->artist_id)->makeVisible(['gallery']);

        return mainResponse(true, __('ok'), $artist, [], 200);
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function offers()
    {
        $offers = Offer::query()->where('status', 1)->whereHas('salon', function ($q){
            $q->where('deleted_at', null);
        })->orWhere('status', 1)->whereHas('artist', function ($q){
            $q->where('deleted_at', null);
        })->get()->makeHidden(['images']);

        return mainResponse(true, __('ok'), $offers, [], 200);
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function offer_details(Request $request)
    {
        $rules = [
            'offer_id' => 'required|exists:offers,id',
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return mainResponse(false, $validator->errors()->first(), (object)[], $validator->errors()->messages(), 200);
        }

        $offer = Offer::query()->find($request->offer_id);

        return mainResponse(true, __('ok'), $offer, [], 200);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $rules = [
            'type' => 'required',
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return mainResponse(false, $validator->errors()->first(), (object)[], $validator->errors()->messages(), 200);
        }

        $now_date = Carbon::now()->format('Y-m-d');
        $banners = Banner::query()->where('status', 1)->whereDate('start_date', '<=', $now_date)->whereDate('end_date', '>=', $now_date);
        if ($request->type == 1) {
            $banners = $banners->where('type', 4);
        } else {
            $banners = $banners->where('type', 5);
        }

        $banners = $banners->get();

        $categories = Category::query()->where('status', 1)->where('type', 3)->where('user_type', $request->type)->get();
        $products = Product::query()->where('status', 1)->where('type', $request->type);
        if ($request->category_id || $request->category_id != 0) {
            $products = $products->where('category_id', $request->category_id);
        }
        $products = $products->orderByDesc('created_at')->get()->makeHidden(['images']);

//        $items = $products->getCollection();
//        $items->map(function ($item){
//            $item->makeHidden(['images']);
//        });
//        $products->setCollection($items);

        if (auth('sanctum')->check() == true) {
            $cart = Cart::query()->where('user_id', auth('sanctum')->id())->where('status', 1)->first();
            $current_cart_id = $cart != null ? $cart->id : 0;
            $cart_items_count = $cart != null ? count($cart->items) : 0;
        } else {
            $current_cart_id = 0;
            $cart_items_count = 0;
        }

        return mainResponse(true, __('ok'), compact('current_cart_id', 'cart_items_count', 'banners', 'categories', 'products'), [], 200);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store_search(Request $request)
    {
        $rules = [
            'type' => 'required',
            'title' => 'required',
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return mainResponse(false, $validator->errors()->first(), (object)[], $validator->errors()->messages(), 200);
        }

        $products = Product::query()->where('status', 1)->where('type', $request->type);
        if ($request->title) {
            $ids = ProductTranslation::query()->where('title', 'like', '%' . $request->title . '%')->pluck('product_id');
            $products = $products->whereIn('id', $ids);
        }
        $products = $products->orderByDesc('created_at')->get()->makeHidden(['images']);

        return mainResponse(true, __('ok'), $products, [], 200);
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function products(Request $request)
    {
        $rules = [
            'type' => 'required',
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return mainResponse(false, $validator->errors()->first(), (object)[], $validator->errors()->messages(), 200);
        }

        $products = Product::query()->where('status', 1)->where('type', $request->type);
        if ($request->category_id || $request->category_id != 0) {
            $products = $products->where('category_id', $request->category_id);
        }
        $products = $products->orderByDesc('created_at')->get()->makeHidden(['images']);

        return mainResponse(true, __('ok'), $products, [], 200);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function product_details(Request $request)
    {
        $rules = [
            'product_id' => 'required|exists:products,id',
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return mainResponse(false, $validator->errors()->first(), (object)[], $validator->errors()->messages(), 200);
        }

        $product = Product::query()->find($request->product_id);

        if (auth('sanctum')->check() == true) {
            $cart = Cart::query()->where('user_id', auth('sanctum')->id())->where('status', 1)->first();
            $cart_items_count = $cart != null ? count($cart->items) : 0;
        } else {
            $cart_items_count = 0;
        }

        $product->setAttribute('cart_items_count', $cart_items_count);

        return mainResponse(true, __('ok'), $product, [], 200);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function salon_products(Request $request)
    {
        $rules = [
            'salon_id' => 'required|exists:salons,id',
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return mainResponse(false, $validator->errors()->first(), (object)[], $validator->errors()->messages(), 200);
        }

        $salon = Salon::query()->find($request->salon_id);

        $data = [
            'id' => $salon->id,
            'name' => $salon->name,
            'image' => $salon->image,
            'products_count' => count($salon->products),
            'products' => $salon->products->makeHidden(['images']),
        ];

        return mainResponse(true, __('ok'), $data, [], 200);
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function days()
    {
        $days_ids = [
            'Sunday' => 1,
            'Monday' => 2,
            'Tuesday' => 3,
            'Wednesday' => 4,
            'Thursday' => 5,
            'Friday' => 6,
            'Saturday' => 7,
        ];

        $now = Carbon::now();

        $weekStartDate = $now->format('Y-m-d H:i');
        $weekEndDate = $now->addDays(30)->format('Y-m-d H:i');

        $dates = CarbonPeriod::create($weekStartDate, $weekEndDate);

        $week_days = [];
        foreach ($dates as $key => $date) {
            $week_days[] = [
                'id' => $days_ids[$date->format('l')],
                'day' => __('common.' . $date->format('l')),
                'date_text' => __('common.' . $date->format('F')) . ' ' . $date->format('d'),
                'date' => $date->format('Y-m-d'),
            ];
        }

        return mainResponse_2(true, __('ok'), $week_days, [], 200);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function booking_times(Request $request)
    {
        $rules = [
            'day_id' => 'required',
            'salon_id' => 'nullable|exists:salons,id',
            'artist_id' => 'nullable|exists:makeup_artists,id',
            'date' => 'required',
            'service_type' => 'required|in:1,2',
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return mainResponse(false, $validator->errors()->first(), (object)[], $validator->errors()->messages(), 200);
        }

        if ($request->salon_id) {
            $booking_times = BookingTime::query()->where('salon_id', $request->salon_id)
                ->where('day', $request->day_id)->where('type', $request->service_type)->where('status', 1)->get();
        } else {
            $booking_times = BookingTime::query()->where('makeup_artist_id', $request->artist_id)
                ->where('day', $request->day_id)->where('type', $request->service_type)->where('status', 1)->get();
        }

        $now_time = Carbon::now();

        $days = [
            '1' => 'Sunday',
            '2' => 'Monday',
            '3' => 'Tuesday',
            '4' => 'Wednesday',
            '5' => 'Thursday',
            '6' => 'Friday',
            '7' => 'Saturday',
        ];

        $now_day = Carbon::now()->format('l');

        $times = [];
        foreach ($booking_times as $time) {
            $time_from = Carbon::parse($time->from);
            if ($days[$request->day_id] == $now_day) {
                if ($now_time->isBefore($time_from)) {
                    if ($time->is_reserved == 0) {
                        $is_reserved = false;
                    } else {
                        $is_reserved = true;
                    }
                    $times[] = [
                        'id' => $time->id,
                        'time' => Carbon::parse($time->from)->isoFormat('h:mm a'),
                        'is_reserved' => $is_reserved,
                    ];
                }
            } else {
                if ($time->is_reserved == 0) {
                    $is_reserved = false;
                } else {
                    $is_reserved = true;
                }

                $times[] = [
                    'id' => $time->id,
                    'time' => Carbon::parse($time->from)->isoFormat('h:mm a'),
                    'is_reserved' => $is_reserved,
                ];
            }
        }

        return mainResponse_2(true, __('ok'), $times, [], 200);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function reservation(Request $request)
    {
        $rules = [
            'salon_id' => 'nullable|exists:salons,id',
            'makeup_artist_id' => 'nullable|exists:makeup_artists,id',
            'services' => 'required|array',
            'services_category' => 'required|array',
            'quantities' => 'required|array',
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return mainResponse(false, $validator->errors()->first(), (object)[], $validator->errors()->messages(), 200);
        }

        if (in_array(1, $request->services_category) && in_array(2, $request->services_category)) {
            return mainResponse_2(false, __('common.cant_have_to_services'), (object)[], [], 200);
        }

        $data = [];
        $data['reservation_number'] = auth('sanctum')->id() . rand(11111, 99999);
        $data['user_id'] = auth('sanctum')->id();
        $data['salon_id'] = $request->salon_id ?? null;
        $data['makeup_artist_id'] = $request->makeup_artist_id ?? null;
        $data['status'] = 0;

        $reservation = Reservation::query()->create($data);

        $total_price = 0;
        for ($i = 0; $i < count($request->services); $i++) {
            $service = Service::query()->find($request->services[$i]);
            ReservationItem::query()->insert([
                'reservation_id' => $reservation->id,
                'service_type_id' => $service->service_type_id,
                'service_id' => $service->id,
                'service_category' => $request->services_category[$i],
                'price' => $service->price,
                'discount_price' => $service->discount_price ?? null,
                'quantity' => $request->quantities[$i],
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);

            if ($service->discount_price != null) {
                $total_price += $service->discount_price * $request->quantities[$i];
            } else {
                $total_price += $service->price * $request->quantities[$i];
            }
        }

        Reservation::query()->find($reservation->id)->update(['total_price' => $total_price]);

        $reservation_items = $reservation->items->makeHidden(['created_at', 'updated_at'])->groupBy('service_type_id');

        $items = [];
        foreach ($reservation_items as $reservation_item) {
            $items[] = [
                'service_type_title' => ServiceType::query()->find($reservation_item[0]->service_type_id)->name,
                'items' => $reservation_item
            ];
        }

        $days_ids = [
            'Sunday' => 1,
            'Monday' => 2,
            'Tuesday' => 3,
            'Wednesday' => 4,
            'Thursday' => 5,
            'Friday' => 6,
            'Saturday' => 7,
        ];

        $now = Carbon::now();

        $weekStartDate = $now->format('Y-m-d H:i');
        $weekEndDate = $now->addDays(30)->format('Y-m-d H:i');

        $dates = CarbonPeriod::create($weekStartDate, $weekEndDate);

        $week_days = [];
        foreach ($dates as $key => $date) {
            $week_days[] = [
                'id' => $days_ids[$date->format('l')],
                'day' => __('common.' . $date->format('l')),
                'date_text' => __('common.' . $date->format('F')) . ' ' . $date->format('d'),
                'date' => $date->format('Y-m-d'),
            ];
        }

        $result = [
            'reservation_id' => $reservation->id,
            'salon_id' => $reservation->salon_id,
            'makeup_artist_id' => $reservation->makeup_artist_id,
            'offer_id' => $reservation->offer_id,
            'items_count' => count($reservation->items),
            'total_price' => $total_price,
            'reservation_items' => $items,
            'days' => $week_days,
        ];

        return mainResponse_2(true, __('ok'), $result, [], 200);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function offer_reservation(Request $request)
    {
        $rules = [
            'salon_id' => 'nullable|exists:salons,id',
            'makeup_artist_id' => 'nullable|exists:makeup_artists,id',
            'offer_id' => 'required|exists:offers,id',
            'quantity' => 'required',
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return mainResponse(false, $validator->errors()->first(), (object)[], $validator->errors()->messages(), 200);
        }

        $offer = Offer::query()->find($request->offer_id);
        $offer_price = $offer->discount_price != null ? $offer->discount_price : $offer->price;

        $data = [];
        $data['reservation_number'] = auth('sanctum')->id() . rand(11111, 99999);
        $data['user_id'] = auth('sanctum')->id();
        $data['offer_id'] = $request->offer_id;
        $data['salon_id'] = $request->salon_id ?? null;
        $data['makeup_artist_id'] = $request->makeup_artist_id ?? null;
        $data['offer_quantity'] = $request->quantity;
        $data['status'] = 0;

        $reservation = Reservation::query()->create($data);

        $reservation->update([
            'total_price' => $offer_price * $request->quantity,
        ]);

        $items = [
            [
                'service_type_title' => __('common.offer'),
                'items' => [
                    [
                        'id' => $offer->id,
                        'reservation_id' => $reservation->id,
                        'service_type_id' => 0,
                        'service_id' => 0,
                        'price' => $offer->price,
                        'discount_price' => $offer->discount_price,
                        'quantity' => (int)$request->quantity,
                        'service_name' => '',
                        'service_image' => '',
                        'offer_name' => $offer->title,
                        'image_image' => $offer->image,
                        'total_price' => $reservation->total_price,
                    ]
                ]
            ]
        ];

        $days_ids = [
            'Sunday' => 1,
            'Monday' => 2,
            'Tuesday' => 3,
            'Wednesday' => 4,
            'Thursday' => 5,
            'Friday' => 6,
            'Saturday' => 7,
        ];

        $now = Carbon::now();

        $weekStartDate = $now->format('Y-m-d H:i');
        $weekEndDate = $now->addDays(30)->format('Y-m-d H:i');

        $dates = CarbonPeriod::create($weekStartDate, $weekEndDate);

        $week_days = [];
        foreach ($dates as $key => $date) {
            $week_days[] = [
                'id' => $days_ids[$date->format('l')],
                'day' => __('common.' . $date->format('l')),
                'date_text' => __('common.' . $date->format('F')) . ' ' . $date->format('d'),
                'date' => $date->format('Y-m-d'),
            ];
        }

        $result = [
            'reservation_id' => $reservation->id,
            'salon_id' => $reservation->salon_id,
            'makeup_artist_id' => $reservation->makeup_artist_id,
            'offer_id' => (int)$reservation->offer_id,
            'owner_type' => $offer->owner_type,
            'items_count' => 1,
            'total_price' => $reservation->total_price,
            'reservation_items' => $items,
            'days' => $week_days,
        ];

        return mainResponse_2(true, __('ok'), $result, [], 200);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function determine_reservation_booking_time(Request $request)
    {
        $rules = [
            'reservation_id' => 'required|exists:reservations,id',
            'day_id' => 'required',
            'date' => 'required',
            'booking_time_id' => 'required|exists:booking_times,id',
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return mainResponse(false, $validator->errors()->first(), (object)[], $validator->errors()->messages(), 200);
        }

        $reservation = Reservation::query()->find($request->reservation_id);

        $reservation->update([
            'day' => $request->day_id,
            'date' => $request->date,
            'booking_time_id' => $request->booking_time_id,
            'status' => 1,
        ]);

        $result = [
            'reservation_id' => $reservation->id,
            'salon_id' => $reservation->salon_id,
            'makeup_artist_id' => $reservation->makeup_artist_id,
            'offer_id' => $reservation->offer_id,
            'provider_name' => $reservation->provider_name,
            'provider_image' => $reservation->provider_image,
            'provider_categories' => $reservation->provider_categories,
            'services_count' => $reservation->offer_id != null ? 1 : count($reservation->items),
            'people_count' => $reservation->offer_id != null ? $reservation->offer_quantity : $reservation->items->sum('quantity'),
            'price' => $reservation->total_price,
            'discount' => 0,
            'total' => $reservation->total_price
        ];

        return mainResponse_2(true, __('ok'), $result, [], 200);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function check_promo_code(Request $request)
    {
        $rules = [
            'code' => 'required|exists:promo_codes,code',
            'item_id' => 'required',
            'type' => 'required|in:1,2',
        ];

        $validation = [
            'code.exists' => request()->header('accept-language') == 'en' ? 'Code Does Not Exists' : 'الكود غير متوفر',
            'code.required' => request()->header('accept-language') == 'en' ? 'Code is required' : 'حقل كود الخصم مطلوب',
        ];

        $validator = Validator::make($request->all(), $rules, $validation);
        if ($validator->fails()) {
            return mainResponse_2(false, $validator->errors()->first(), (object)[], $validator->errors()->messages());
        }

        if ($request->type == 1) {
            $item = Reservation::query()->find($request->item_id);
        } else {
            $item = CartItem::query()->where('cart_id', $request->item_id);
        }

        if (!$item) {
            return mainResponse_2(false, __('Not Found'), (object)[], [], 200);
        }

        $user_id = auth('sanctum')->id();
        $code = PromoCode::query()->where('status', 1);

        if ($request->type == 1) {
            if ($item->salon_id != null) {
                $code = $code->where('salon_id', $item->salon_id);
            } else {
                $code = $code->where('makeup_artist_id', $item->makeup_artist_id);
            }

            $code = $code->where('code', $request->code)->get()->first();
        } else {
            $code = $code->where('code', $request->code)->get()->first();
        }

        if ($code == null) {
            return mainResponse(false, __('code not found'), [], [], 404);
        }

        if ($request->type == 1) {
            $used_code = Reservation::query()->where('promo_code_id', $code->id);
        } else {
            $used_code = Order::query()->where('promo_code_id', $code->id);
        }

        if (count($used_code->get()) >= $code->number_of_usage) {
            return mainResponse(false, __('common.code_number_of_usage'), [], [], 404);
        } elseif (count($used_code->where('user_id', $user_id)->get()) >= $code->number_of_usage_for_user) {
            return mainResponse(false, __('common.cant_use_code') . ' ' . $code->number_of_usage_for_user . ' ' . __('common.times'), [], [], 404);
        }

        $now = Carbon::now();
        if ($now->isAfter($code->date_from) && $now->isBefore($code->date_to)) {
            $code_amount = 0;
            $price = $request->type == 1 ? $item->total_price : $item->sum('total_price');

            if ($code->discount_type == 2) {
                $code_amount = ($price * ($code->discount / 100));
            } else {
                $code_amount = $code->discount;
            }

            if ($code_amount > $price) {
                return mainResponse_2(false, __('common.code_price_more'), (object)[], [], 404);
            } else {
                $price = $price - $code_amount;
            }

            $result = [
                'code' => $request->code,
                'code_id' => $code->id,
                'code_amount' => number_format($code_amount, 2),
                'price' => number_format($request->type == 1 ? $item->total_price : $item->sum('total_price'), 2),
                'total_price' => number_format($price, 2),
            ];

            return mainResponse(true, __('ok'), $result, [], 200);
        } else {
            return mainResponse(false, __('common.code_expired'), [], [], 404);
        }
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function pay_for_reservation(Request $request)
    {
        $rules = [
            'reservation_id' => 'required|exists:reservations,id',
            'payment_method_id' => 'required|exists:payment_methods,id',
            'promo_code_id' => 'nullable',
            'transaction_no' => 'nullable',
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return mainResponse(false, $validator->errors()->first(), (object)[], $validator->errors()->messages(), 200);
        }

        $user = User::query()->find(auth('sanctum')->id());

        $reservation = Reservation::query()->find($request->reservation_id);

        $code_type = null;
        $code_price = null;
        $total_price_after_code = null;

        if ($request->promo_code_id) {
            $code = PromoCode::query()->find($request->promo_code_id);

            $code_amount = 0;
            $price = $reservation->total_price;

            if ($code->discount_type == 2) {
                $code_amount = ($price * ($code->discount / 100));
            } else {
                $code_amount = $code->discount;
            }

            if ($code_amount > $price) {
                return mainResponse_2(false, __('common.common.code_price_more'), (object)[], [], 404);
            } else {
                $price = $price - $code_amount;
            }

            $code_type = $code->discount_type;
            $code_price = $code_amount;
            $total_price_after_code = $price;
        }

        $amount = $total_price_after_code == null ? $reservation->total_price : $total_price_after_code;

        if ($request->payment_method_id == 2) {
            if ($user->balance < $amount) {
                return mainResponse_2(false, __('common.balance_not_enough'), (object)[], [], 200);
            }
        }

        if (!in_array($request->payment_method_id, [1, 2])) {
            $lang = request()->header('accept-language') == 'ar' ? 'ARB' : 'ENG';
            $url = url('/api/reservation_payment_webview?reservation_id=' . $reservation->id . '&amount=' . $amount . '&mobile=' . $user->mobile . '&payment_method_id=' . $request->payment_method_id
                . '&code_id=' . $request->promo_code_id . '&code_type=' . $code_type . '&code_price=' . $code_price . '&total_price_after_code=' . $total_price_after_code . '&lang=' . $lang);
            return mainResponse_2(true, __('ok'), $url, [], 200);
        } else {
            $reservation->update([
                'payment_method_id' => $request->payment_method_id,
                'promo_code_id' => $request->promo_code_id ?? null,
                'transaction_no' => $request->transaction_no ?? null,
                'code_type' => $code_type,
                'code_price' => $code_price,
                'total_price_after_code' => $total_price_after_code,
                'status' => 1,
            ]);

            if ($request->payment_method_id == 2) {
                $user = User::query()->find(auth('sanctum')->id());
                $user->balance -= $amount;

                $user->update();
            }

            Transaction::query()->insert([
                'user_id' => auth('sanctum')->id(),
                'type' => 1,
                'price' => $amount,
                'payment_method_id' => $request->payment_method_id,
                'transaction_no' => $request->transaction_no,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);

            $title = 'حجز جديد';
            $message = 'لديك حجز جديد من المستخدم ' . $user->name;

            $notification = Notification::query()->create([
                'image' => @$user->image,
                'type' => $reservation->salon_id != null ? 'salon_reservation_notification' : 'artist_reservation_notification',
                'send_date' => Carbon::parse(Carbon::now())->format('Y-m-d'),
                'is_seen' => 0,
                'reference_id' => $reservation->salon_id != null ? $reservation->salon_id : $reservation->makeup_artist_id,
                'user_id' => $user->id,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);

            foreach (locales() as $key => $value) {
                NotificationTranslation::query()->insert([
                    'notification_id' => $notification->id,
                    'title' => $key == 'ar' ? 'حجز جديد' : 'New Message',
                    'message' => $key == 'ar' ? 'لديك حجز جديد من المستخدم ' . $user->name : 'You have new reservation from ' . $user->name,
                    'locale' => $key,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ]);
            }

            if ($reservation->salon_id != null) {
                event(new \App\Events\Notification($title, $message, $reservation->salon_id, 0, 'salon_reservation_notification'));
            } else {
                event(new \App\Events\Notification($title, $message, 0, $reservation->makeup_artist_id, 'artist_reservation_notification'));
            }

            return mainResponse_2(true, __('common.done_successfully'), (object)[], [], 200);
        }
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function reservation_webview(Request $request)
    {
        $order_id = $request->reservation_id;
        $amount = $request->amount;
        $mobile = $request->mobile;
        $email = $request->email;
        $callback_url = url('/api/reservation_callback?reservation_id=' . $request->reservation_id . '&payment_method_id=' . $request->payment_method_id
            . '&code_id=' . $request->promo_code_id . '&code_type=' . $request->code_type . '&code_price=' . $request->code_price
            . '&total_price_after_code=' . $request->total_price_after_code);
        $reservation_items = ReservationItem::query()->where('reservation_id', $request->reservation_id)->get();
        $lang = $request->lang;
        $items = [];
        foreach ($reservation_items as $item) {
            $items[] = [
                'order_id' => $order_id,
                'amount' => $item->discount_price != null ? $item->discount_price : $item->price,
                'quantity' => $item->quantity,
                'itemname' => @$item->service->name ?? 'Service',
            ];
        }

        return view('payment_webview', compact('order_id', 'amount', 'mobile', 'email', 'items', 'callback_url', 'lang'));
    }

    /**
     * @param Request $request
     * @return string
     */
    public function reservation_callback(Request $request)
    {
        if ($request->transaction_status != 3) {
            return redirect(route('fail'));
        }

        $reservation = Reservation::query()->find($request->reservation_id);
        $user = User::query()->find($reservation->user_id);

        $reservation->update([
            'payment_method_id' => $request->payment_method_id,
            'promo_code_id' => $request->promo_code_id ?? null,
            'code_type' => $request->code_type,
            'code_price' => $request->code_price,
            'total_price_after_code' => $request->total_price_after_code,
            'status' => 1,
        ]);

        $title = 'حجز جديد';
        $message = 'لديك حجز جديد من المستخدم ' . $user->name;

        $notification = Notification::query()->create([
            'image' => @$user->image,
            'type' => $reservation->salon_id != null ? 'salon_reservation_notification' : 'artist_reservation_notification',
            'send_date' => Carbon::parse(Carbon::now())->format('Y-m-d'),
            'is_seen' => 0,
            'reference_id' => $reservation->salon_id != null ? $reservation->salon_id : $reservation->makeup_artist_id,
            'user_id' => $user->id,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        foreach (locales() as $key => $value) {
            NotificationTranslation::query()->insert([
                'notification_id' => $notification->id,
                'title' => $key == 'ar' ? 'حجز جديد' : 'New Message',
                'message' => $key == 'ar' ? 'لديك حجز جديد من المستخدم ' . $user->name : 'You have new reservation from ' . $user->name,
                'locale' => $key,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
        }

        if ($reservation->salon_id != null) {
            event(new \App\Events\Notification($title, $message, $reservation->salon_id, 0, 'salon_reservation_notification'));
        } else {
            event(new \App\Events\Notification($title, $message, 0, $reservation->makeup_artist_id, 'artist_reservation_notification'));
        }

        return redirect(route('success'));
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function user_reservations(Request $request)
    {
        $rules = [
            'status' => 'nullable',
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return mainResponse(false, $validator->errors()->first(), (object)[], $validator->errors()->messages(), 200);
        }

        $reservations = Reservation::query()->where('user_id', auth('sanctum')->id())->whereNotNull('payment_method_id');

        if ($request->status) {
            $reservations = $reservations->where('status', $request->status);
        } else {
            $reservations = $reservations->whereIn('status', [1, 2, 3]);
        }

        $reservations = $reservations->orderByDesc('created_at')->get()->makeHidden([
            'day', 'date', 'booking_time_id', 'cancel_reason', 'promo_code_id', 'code_type', 'code_price', 'total_price', 'total_price_after_code', 'payment_method_id',
            'updated_at', 'created_at', 'date_text', 'reservation_time'
        ])->groupBy(function ($item) {
            return $item->created_at->format('Y-m-d');
        });

        $user_reservations = [];
        foreach ($reservations as $i => $reservation) {
            $created_at = '';
            if (Carbon::parse($i)->isCurrentDay()) {
                $created_at = __('common.today');
            } else {
                $created_at = $i;
            }
            $user_reservations[] = [
                'created_at' => $created_at,
                'reservations' => $reservation,
            ];
        }

        return mainResponse(true, __('ok'), $user_reservations, [], 200);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function reservation_details(Request $request)
    {
        $rules = [
            'reservation_id' => 'required|exists:reservations,id',
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return mainResponse(false, $validator->errors()->first(), (object)[], $validator->errors()->messages(), 200);
        }

        $reservation = Reservation::query()->find($request->reservation_id);

        $reservation_items = $reservation->items->makeHidden(['created_at', 'updated_at'])->groupBy('service_type_id');

        $items = [];
        if ($reservation->offer_id != null) {
            $offer = Offer::query()->find($reservation->offer_id);
            $items[] = [
                'service_type_title' => $offer->title,
                'items' => [
                    'id' => $reservation->offer_id,
                    'reservation_id' => $reservation->id,
                    'service_type_id' => $offer->service_type,
                    'service_id' => $reservation->offer_id,
                    'service_category' => $offer->category_id,
                    'price' => $offer->price,
                    'discount_price' => $offer->discount_price,
                    'quantity' => $reservation->quantity,
                    'service_name' => $offer->title,
                    'service_image' => $offer->image,
                    'total_price' => $reservation->total_price,
                    'service_category_name' => $offer->category_name
                ]
            ];
        } else {
            foreach ($reservation_items as $reservation_item) {
                $items[] = [
                    'service_type_title' => ServiceType::query()->find($reservation_item[0]->service_type_id)->name,
                    'items' => $reservation_item
                ];
            }
        }

        $data = [
            'reservation_id' => $reservation->id,
            'reservation_number' => $reservation->reservation_number,
            'created_at' => Carbon::parse($reservation->created_at)->diffForHumans(),
            'salon_id' => $reservation->salon_id,
            'makeup_artist_id' => $reservation->makeup_artist_id,
            'provider_name' => $reservation->provider_name,
            'provider_image' => $reservation->provider_image,
            'reservation_date' => $reservation->date_text,
            'reservation_time' => $reservation->reservation_time,
            'can_cancel' => $reservation->can_cancel,
            'status' => $reservation->status,
            'status_text' => $reservation->status_text,
            'items_count' => $reservation->offer_id == null ? count($reservation->items) : 1,
            'services' => $items,
            'payment_method_title' => @$reservation->payment_method->title,
            'payment_method_image' => @$reservation->payment_method->image,
            'bill' => [
                'services_count' => $reservation->offer_id == null ? count($reservation->items) : 1,
                'people_count' => $reservation->offer_id == null ? $reservation->items->sum('quantity') : $reservation->offer_quantity,
                'price' => $reservation->total_price,
                'discount' => $reservation->promo_code_id == null ? 0 : $reservation->code_price,
                'total' => $reservation->promo_code_id == null ? $reservation->total_price : $reservation->total_price_after_code
            ]
        ];

        return mainResponse(true, __('ok'), $data, [], 200);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function cancel_reservation(Request $request)
    {
        $rules = [
            'reservation_id' => 'required|exists:reservations,id',
            'reason' => 'required',
        ];

        $validation = [
            'reservation_id.required' => __('common.reservation_required'),
            'reason.required' => __('common.reason_required'),
        ];

        $validator = Validator::make($request->all(), $rules, $validation);
        if ($validator->fails()) {
            return mainResponse(false, $validator->errors()->first(), (object)[], $validator->errors()->messages(), 200);
        }

        $reservation = Reservation::query()->find($request->reservation_id);
        $reservation->update([
            'status' => 4,
            'cancel_reason' => $request->reason,
        ]);

        return mainResponse(true, __('common.canceled_successfully'), (object)[], [], 200);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function add_to_cart(Request $request)
    {
        $rules = [
            'cart_id' => 'nullable|exists:carts,id',
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required',
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return mainResponse(false, $validator->errors()->first(), (object)[], $validator->errors()->messages(), 200);
        }

        $order_exists = Order::query()->where('user_id', auth('sanctum')->id())->where('cart_id', $request->cart_id)->whereNotNull('payment_method_id')->exists();
        if ($order_exists) {
            return mainResponse_2(false, __('common.cart_order'), (object)[], [], 200);
        }

        $product = Product::query()->find($request->product_id);
        $product_stock = CartItem::query()->where('product_id', $product->id)->sum('quantity');

        $product_price = $product->discount_price != null ? $product->discount_price : $product->price;

        if ($request->quantity > $product->stock) {
            return mainResponse_2(false, __('common.quantity_greater'), (object)[], [], 200);
        }

        if ($product_stock == $product->stock) {
            return mainResponse_2(false, __('common.product_out_of_stock'), (object)[], [], 200);
        }

        if ($request->cart_id) {
            $cart = Cart::query()->find($request->cart_id);

            if ($cart->user_id != auth('sanctum')->id()) {
                return mainResponse_2(false, __('common.cant_add_product'), (object)[], [], 200);
            }

            $product_exists = CartItem::query()->where('cart_id', $request->cart_id)->where('product_id', $product->id)->first();
            if ($product_exists) {
                $product_exists->quantity += $request->quantity;
                $product_exists->total_price = $product_price * $product_exists->quantity;
                $product_exists->update();
//                return mainResponse_2(true, __('common.product_cart'), (object)[], [], 200);
            } else {
                CartItem::query()->create([
                    'cart_id' => $request->cart_id,
                    'product_id' => $product->id,
                    'quantity' => $request->quantity,
                    'price' => $product->price,
                    'discount_price' => $product->discount_price,
                    'total_price' => $product_price * $request->quantity,
                ]);
            }
        } else {
            $data = [];
            $data['user_id'] = auth('sanctum')->id();

            $cart = Cart::query()->create($data);

            CartItem::query()->create([
                'cart_id' => $cart->id,
                'product_id' => $product->id,
                'quantity' => $request->quantity,
                'price' => $product->price,
                'discount_price' => $product->discount_price,
                'total_price' => $product_price * $request->quantity,
            ]);
        }

        $cart_items_count = count($cart->items);
        $cart_id = $cart->id;

        return mainResponse_2(true, __('common.add_to_cart'), compact('cart_id', 'cart_items_count'), [], 200);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function cart(Request $request)
    {
        $rules = [
            'cart_id' => 'required|exists:carts,id',
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return mainResponse(false, $validator->errors()->first(), (object)[], $validator->errors()->messages(), 200);
        }

        $order_exists = Order::query()->where('cart_id', $request->cart_id)->whereNotNull('payment_method_id')->exists();
        if ($order_exists) {
            return mainResponse_2(false, __('common.cart_paid'), (object)[], [], 200);
        }

        $cart = Cart::query()->find($request->cart_id);

        if ($cart->user_id != auth('sanctum')->id()) {
            return mainResponse_2(false, __('common.cart_not_available'), (object)[], [], 200);
        }

        $cart_items = CartItem::query()->where('cart_id', $request->cart_id)->get();

        $items = [];
        $total_price = 0;
        foreach ($cart_items as $cart_item) {
            $items[] = [
                'id' => $cart_item->id,
                'product_id' => $cart_item->product_id,
                'product_title' => @$cart_item->product->title,
                'product_image' => @$cart_item->product->image,
                'product_category' => @$cart_item->product->category_name,
                'total_price' => $cart_item->total_price,
                'quantity' => $cart_item->quantity,
            ];
            $total_price += $cart_item->total_price;
        }

        $delivery = Setting::query()->where('key', 'delivery_price')->first();
        $delivery_price = $delivery == null ? 0 : (int)$delivery->value;

        return mainResponse_2(true, __('ok'), compact('total_price', 'delivery_price', 'items'), [], 200);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function increment_decrement_product(Request $request)
    {
        $rules = [
            'type' => 'required|in:1,2',
            'item_id' => 'required|exists:cart_items,id',
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return mainResponse(false, $validator->errors()->first(), (object)[], $validator->errors()->messages(), 200);
        }

        $cart_item = CartItem::query()->find($request->item_id);
        if ($request->type == 1) {
            $cart_item->increment('quantity');
        } else {
            $cart_item->decrement('quantity');
        }

        $cart_item->update([
            'total_price' => $cart_item->discount_price != null ? $cart_item->quantity * $cart_item->discount_price : $cart_item->price * $cart_item->quantity,
        ]);

        return mainResponse_2(true, __('ok'), (object)[], [], 200);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function delete_cart_item(Request $request)
    {
        $rules = [
            'item_id' => 'required|exists:cart_items,id',
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return mainResponse(false, $validator->errors()->first(), (object)[], $validator->errors()->messages(), 200);
        }

        CartItem::query()->find($request->item_id)->delete();

        return mainResponse_2(true, __('ok'), (object)[], [], 200);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\UrlGenerator|\Illuminate\Http\JsonResponse|string
     */
    public function order(Request $request)
    {
        $rules = [
            'cart_id' => 'required|exists:carts,id',
            'promo_code_id' => 'nullable|exists:promo_codes,id',
            'payment_method_id' => 'nullable|exists:payment_methods,id',
            'address_id' => 'nullable|exists:addresses,id',
            'transaction_no' => 'nullable',
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return mainResponse(false, $validator->errors()->first(), (object)[], $validator->errors()->messages(), 200);
        }

        $user = User::query()->find(auth('sanctum')->id());

        $order_exists = Order::query()->where('user_id', auth('sanctum')->id())->where('cart_id', $request->cart_id)->whereNotNull('payment_method_id')->exists();
        if ($order_exists) {
            return mainResponse_2(false, __('common.cart_order'), (object)[], [], 200);
        }

        $cart_price = CartItem::query()->where('cart_id', $request->cart_id)->sum('total_price');
        $cart_items = CartItem::query()->where('cart_id', $request->cart_id)->get();

        $delivery = Setting::query()->where('key', 'delivery_price')->first();
        $delivery_price = $delivery == null ? 0 : (int)$delivery->value;

        $order_amount = $cart_price + $delivery_price;

        if ($request->payment_method_id == 2) {
            if ($user->balance < $order_amount) {
                return mainResponse_2(false, __('common.balance_not_enough'), (object)[], [], 200);
            }
        }

        $code_type = null;
        $code_price = null;
        $total_price_after_code = null;

        if ($request->promo_code_id) {
            $code = PromoCode::query()->find($request->promo_code_id);

            $code_amount = 0;
            $price = $order_amount;

            if ($code->discount_type == 2) {
                $code_amount = ($price * ($code->discount / 100));
            } else {
                $code_amount = $code->discount;
            }

            if ($code_amount > $price) {
                return mainResponse_2(false, __('common.code_price_more'), (object)[], [], 404);
            } else {
                $price = $price - $code_amount;
            }

            $code_type = $code->discount_type;
            $code_price = $code_amount;
            $total_price_after_code = $price;
        }

        $order = Order::query()->create([
            'user_id' => auth('sanctum')->id(),
            'cart_id' => $request->cart_id,
            'total_price' => $order_amount,
            'promo_code_id' => $request->promo_code_id ?? null,
            'code_type' => $code_type,
            'code_price' => $code_price,
            'delivery_price' => $delivery_price,
            'total_price_after_code' => $total_price_after_code != null ? $total_price_after_code + $delivery_price : null,
            'address_id' => $request->address_id,
        ]);

        if (!in_array($request->payment_method_id, [1, 2])) {
            $lang = request()->header('accept-language') == 'ar' ? 'ARB' : 'ENG';
            $url = url('/api/order_payment_webview?order_id=' . $order->id . '&payment_method_id=' . $request->payment_method_id . '&amount=' . $order_amount . '&mobile=' . $user->mobile . '&cart_id=' . $request->cart_id . '&lang=' . $lang);
            return mainResponse_2(true, __('ok'), $url, [], 200);
        } else {
            Cart::query()->find($request->cart_id)->update(['status' => 2]);

            Order::query()->find($order->id)->update([
                'payment_method_id' => $request->payment_method_id,
                'transaction_no' => $request->transaction_no ?? null,
                'status' => 1,
            ]);

            OrderStatus::query()->insert([
                'order_id' => $order->id,
                'status' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);

            if ($request->payment_method_id == 2) {
                $user = User::query()->find(auth('sanctum')->id());
                $user->balance -= $order_amount;
                $user->update();
            }

            Transaction::query()->insert([
                'user_id' => auth('sanctum')->id(),
                'type' => 1,
                'price' => $request->promo_code_id ? $total_price_after_code : $order_amount,
                'payment_method_id' => $request->payment_method_id,
                'transaction_no' => $request->transaction_no,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);

            foreach ($cart_items as $item) {
                $product = Product::query()->find($item->product_id);
                $product->stock -= $item->quantity;
                $product->save();
            }

            $user = User::query()->find(auth('sanctum')->id());

            $title = 'طلب جديد';
            $message = 'لديك طلب جديد من المستخدم ' . $user->name;

            $notification = Notification::query()->create([
                'image' => @$user->image,
                'type' => 'order_notification',
                'send_date' => Carbon::parse(Carbon::now())->format('Y-m-d'),
                'is_seen' => 0,
                'reference_id' => 0,
                'user_id' => $user->id,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);

            foreach (locales() as $key => $value) {
                NotificationTranslation::query()->insert([
                    'notification_id' => $notification->id,
                    'title' => $key == 'ar' ? 'طلب جديد' : 'New Order',
                    'message' => $key == 'ar' ? 'لديك طلب جديد من المستخدم ' . $user->name : 'You have new order from ' . $user->name,
                    'locale' => $key,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ]);
            }

            event(new \App\Events\Notification($title, $message, 0, 0, 'order_notification'));

            return mainResponse_2(true, __('common.done_successfully'), (object)[], [], 200);
        }
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function order_webview(Request $request)
    {
        $delivery = Setting::query()->where('key', 'delivery_price')->first();
        $delivery_price = $delivery == null ? 0 : (int)$delivery->value;

        $order_id = $request->order_id;
        $amount = $request->amount;
        $mobile = $request->mobile;
        $email = $request->email;
        $callback_url = url('/api/order_callback?order_id=' . $request->order_id . '&payment_method_id=' . $request->payment_method_id);
        $cart_items = \App\Models\CartItem::query()->where('cart_id', $request->cart_id)->get();
        $lang = $request->lang;
        $items = [];
        foreach ($cart_items as $item) {
            $items[] = [
                'order_id' => $order_id,
                'amount' => $item->discount_price != null ? $item->discount_price : $item->price,
                'quantity' => $item->quantity,
                'itemname' => @$item->product->title ?? 'Product',
            ];
        }

        $items[] = [
            'order_id' => $order_id,
            'amount' => $delivery_price,
            'quantity' => 1,
            'itemname' => 'Delivery Fee',
        ];

        return view('payment_webview', compact('order_id', 'amount', 'mobile', 'email', 'items', 'callback_url', 'lang'));
    }

    /**
     * @param Request $request
     * @return string
     */
    public function order_callback(Request $request)
    {
        if ($request->transaction_status != 3) {
            return redirect(route('fail'));
        }

        $order = Order::query()->find($request->order_id);
        $user = User::query()->find($order->user_id);

        $order->update([
            'payment_method_id' => $request->payment_method_id,
            'status' => 1
        ]);

        OrderStatus::query()->insert([
            'order_id' => $order->id,
            'status' => 1,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        Cart::query()->find($order->cart_id)->update(['status' => 2]);

        $title = 'طلب جديد';
        $message = 'لديك طلب جديد من المستخدم ' . $user->name;

        $notification = Notification::query()->create([
            'image' => @$user->image,
            'type' => 'order_notification',
            'send_date' => Carbon::parse(Carbon::now())->format('Y-m-d'),
            'is_seen' => 0,
            'reference_id' => 0,
            'user_id' => $user->id,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        foreach (locales() as $key => $value) {
            NotificationTranslation::query()->insert([
                'notification_id' => $notification->id,
                'title' => $key == 'ar' ? 'طلب جديد' : 'New Order',
                'message' => $key == 'ar' ? 'لديك طلب جديد من المستخدم ' . $user->name : 'You have new order from ' . $user->name,
                'locale' => $key,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
        }

        event(new \App\Events\Notification($title, $message, 0, 0, 'order_notification'));

        return redirect(route('success'));
    }

    /**
     * @return string
     */
    public function success()
    {
        return 'success';
    }

    /**
     * @return string
     */
    public function fail()
    {
        return 'fail';
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function get_orders(Request $request)
    {
        $orders = Order::query()->whereNotNull('payment_method_id')->where('user_id', auth('sanctum')->id());

        if ($request->status) {
            $orders = $orders->where('status', $request->status);
        }

        $orders = $orders->orderByDesc('created_at')->get()->makeHidden([
            'cart_id', 'user_id', 'promo_code_id', 'code_type', 'code_price', 'payment_method_id', 'address_id'
        ])->groupBy(function ($item) {
            return $item->created_at->format('Y-m-d');
        });

        $user_orders = [];
        foreach ($orders as $i => $order) {
            $created_at = '';
            if (Carbon::parse($i)->isCurrentDay()) {
                $created_at = __('common.today');
            } else {
                $created_at = $i;
            }
            $user_orders[] = [
                'created_at' => $created_at,
                'orders' => $order,
            ];
        }

        return mainResponse_2(true, __('ok'), $user_orders, [], 200);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function order_details(Request $request)
    {
        $rules = [
            'order_id' => 'required|exists:orders,id',
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return mainResponse(false, $validator->errors()->first(), (object)[], $validator->errors()->messages(), 200);
        }

        $order = Order::query()->find($request->order_id);

        $cart_items = CartItem::query()->where('cart_id', $order->cart_id)->get();

        $products = [];
        $bill = [];
        $total_price = 0;
        foreach ($cart_items as $cart_item) {
            $products[] = [
                'product_id' => $cart_item->product_id,
                'product_title' => @$cart_item->product->title,
                'product_image' => @$cart_item->product->image,
                'product_category' => @$cart_item->product->category_name,
                'owner_type' => @$cart_item->product->owner_type,
                'owner_name' => @$cart_item->product->owner_name,
                'owner_image' => @$cart_item->product->owner_image,
                'total_price' => $cart_item->total_price,
                'quantity' => $cart_item->quantity,
            ];
            $bill[] = [
                'product_title' => @$cart_item->product->title,
                'total_price' => $cart_item->total_price,
                'quantity' => $cart_item->quantity,
            ];

            $total_price += $cart_item->total_price;
        }

        $purchase_done = OrderStatus::query()->where('order_id', $order->id)->where('status', 1)->first();
//        $in_shipping = OrderStatus::query()->where('order_id', $order->id)->where('status', 2)->first();
        $delivered = OrderStatus::query()->where('order_id', $order->id)->where('status', 2)->first();
        $delivery = Setting::query()->where('key', 'delivery_price')->first();
        $delivery_price = $delivery == null ? 0 : $delivery->value;

        $final_price = $order->total_price_after_code != null ? $order->total_price_after_code : $total_price;

        $data = [
            'id' => $order->id,
            'order_date' => Carbon::parse($order->created_at)->format('d/m/Y'),
            'status' => $order->status,
            'can_cancel' => $order->can_cancel,
            'statuses' => [
                [
                    'title' => __('common.purchase_done'),
                    'created_at' => $purchase_done != null ? Carbon::parse($purchase_done->created_at)->format('d/m/Y, g:i A') : '',
                    'is_active' => $purchase_done != null,
                ],
                [
                    'title' => __('common.in_shipping'),
                    'created_at' => $purchase_done != null ? Carbon::parse($purchase_done->created_at)->format('d/m/Y, g:i A') : '',
                    'is_active' => $purchase_done != null,
                ],
                [
                    'title' => __('common.delivered'),
                    'created_at' => $delivered != null ? Carbon::parse($delivered->created_at)->format('d/m/Y, g:i A') : '',
                    'is_active' => $delivered != null,
                ],
            ],
            'cancel_reason' => $order->cancel_reason,
            'reject_reason' => $order->reject_reason,
            'products' => $products,
            'address' => [
                'id' => @$order->address->id,
                'address_name' => @$order->address->address_name,
                'detailed_address' => @$order->address->detailed_address,
            ],
            'payment_method' => [
                'title' => @$order->payment_method->title,
                'image' => @$order->payment_method->image,
            ],
            'bill' => $bill,
            'delivery_price' => $order->delivery_price,
            'total_price' => $total_price,
            'has_promo_code_discount' => $order->code_price != null ? true : false,
            'discount' => $order->code_price,
            'total_price_with_discount' => $order->total_price_after_code,
            'final_price' => $final_price,
        ];

        return mainResponse_2(true, __('ok'), $data, [], 200);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function cancel_order(Request $request)
    {
        $rules = [
            'order_id' => 'required|exists:orders,id',
            'reason' => 'required',
        ];

        $validation = [
            'order_id.required' => __('common.order_required'),
            'reason.required' => __('common.reason_required'),
        ];

        $validator = Validator::make($request->all(), $rules, $validation);
        if ($validator->fails()) {
            return mainResponse(false, $validator->errors()->first(), (object)[], $validator->errors()->messages(), 200);
        }

        $order = Order::query()->find($request->order_id);
        $order->update([
            'status' => 3,
            'cancel_reason' => $request->reason,
        ]);

        OrderStatus::query()->insert([
            'order_id' => $order->id,
            'status' => 3,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        return mainResponse(true, __('common.canceled_successfully'), (object)[], [], 200);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function complete_order(Request $request)
    {
        $rules = [
            'order_id' => 'required|exists:orders,id',
        ];

        $validation = [
            'order_id.required' => __('common.order_required'),
        ];

        $validator = Validator::make($request->all(), $rules, $validation);
        if ($validator->fails()) {
            return mainResponse(false, $validator->errors()->first(), (object)[], $validator->errors()->messages(), 200);
        }

        $order = Order::query()->find($request->order_id);
        $order->update([
            'status' => 2,
            'cancel_reason' => $request->reason,
        ]);

        OrderStatus::query()->insert([
            'order_id' => $order->id,
            'status' => 2,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        return mainResponse(true, __('common.delivered_successfully'), (object)[], [], 200);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function favorite(Request $request)
    {
        $rules = [
            'salon_id' => 'nullable|exists:salons,id',
            'makeup_artist_id' => 'nullable|exists:makeup_artists,id',
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return mainResponse(false, $validator->errors()->first(), (object)[], $validator->errors()->messages(), 200);
        }

        if ($request->salon_id) {
            $favorite = Favorite::query()->where('user_id', auth('sanctum')->id())->where('salon_id', $request->salon_id)->first();
        } else {
            $favorite = Favorite::query()->where('user_id', auth('sanctum')->id())->where('makeup_artist_id', $request->makeup_artist_id)->first();
        }

        if ($favorite) {
            $favorite->delete();
        } else {
            Favorite::query()->insert([
                'user_id' => auth('sanctum')->id(),
                'salon_id' => $request->salon_id ?? null,
                'makeup_artist_id' => $request->makeup_artist_id ?? null,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
        }

        return mainResponse_2(true, __('common.done_successfully'), (object)[], [], 200);
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function get_favorites(Request $request)
    {
        $rules = [
            'type' => 'required',
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return mainResponse(false, $validator->errors()->first(), (object)[], $validator->errors()->messages(), 200);
        }

        $favorites = Favorite::query()->where('user_id', auth('sanctum')->id());
        if ($request->type == 1) {
            $favorites = $favorites->where('makeup_artist_id', null);
        } else {
            $favorites = $favorites->where('salon_id', null);
        }

        $favorites = $favorites->get();

        return mainResponse(true, __('ok'), $favorites, [], 200);
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function notifications()
    {
        $notifications = NotificationUser::query()->where('user_id', auth('sanctum')->id())->orderByDesc('created_at');

        $notifications->update(['is_seen' => 1]);
        $notifications = $notifications->paginate(15);

        return mainResponse(true, __('ok'), $notifications, [], 200);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function delete_notification(Request $request)
    {
        $rules = [
            'notification_id' => 'required|exists:notification_users,id',
        ];

        $validation = [
            'notification_id.required' => __('common.notification_required'),
        ];

        $validator = Validator::make($request->all(), $rules, $validation);
        if ($validator->fails()) {
            return mainResponse(false, $validator->errors()->first(), (object)[], $validator->errors()->messages(), 200);
        }

        NotificationUser::query()->where('user_id', auth('sanctum')->id())->where('id', $request->notification_id)->delete();

        return mainResponse_2(true, __('common.done_successfully'), (object)[], [], 200);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function map_search(Request $request)
    {
        $rules = [
            'search_data' => 'required',
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return mainResponse(false, $validator->errors()->first(), (object)[], $validator->errors()->messages(), 200);
        }

        $data = [];

        $salons_ids = SalonTranslation::query()->where('name', 'like', '%' . $request->search_data . '%')->pluck('salon_id');
        $salons = Salon::query()->where('status', 1)->whereIn('id', $salons_ids)->get()->makeHidden([
            'in_salon_service_types', 'home_service_types'
        ]);

        return mainResponse_2(true, __('ok'), $salons, [], 200);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function contact_us(Request $request)
    {
        $rules = [
            'email' => 'required',
            'name' => 'required',
            'mobile' => 'required',
            'title' => 'required',
            'message' => 'required',
            'image' => 'nullable',
        ];

        $validation = [
            'title.required' => __('common.title_required'),
            'message.required' => __('common.message_required'),
            'mobile.required' => __('common.mobile_required'),
            'name.required' => __('common.name_required'),
            'email.required' => __('common.email_required'),
        ];

        $validator = Validator::make($request->all(), $rules, $validation);
        if ($validator->fails()) {
            return mainResponse(false, $validator->errors()->first(), (object)[], $validator->errors()->messages(), 200);
        }

        $data = $request->except('image');

        if ($request->image) {
            $filename = rand(111111, 999999) . '_' . \Carbon\Carbon::now()->timestamp;
            $filename .= substr($request->image->getClientOriginalName(), strrpos($request->image->getClientOriginalName(), '.'));
            $filename = $request->image->move(public_path('images'), $filename);
            $data['image'] = $filename->getBasename();
        }

        ContactUs::query()->create($data);

        $user = auth('sanctum')->user();

        $title = 'طلب مساعدة';
        $message = 'هناك طلب مساعدة جديد من المستخدم ' . $request->name;

        $notification = Notification::query()->create([
            'image' => null,
            'type' => 'contact_us_notification',
            'send_date' => Carbon::parse(Carbon::now())->format('Y-m-d'),
            'is_seen' => 0,
            'reference_id' => 0,
            'user_id' => null,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        foreach (locales() as $key => $value) {
            NotificationTranslation::query()->insert([
                'notification_id' => $notification->id,
                'title' => $key == 'ar' ? 'طلب مساعدة' : 'New Help Request',
                'message' => $key == 'ar' ? 'هناك طلب مساعدة جديد من المستخدم ' . $request->name : 'You have help request from ' . $request->name,
                'locale' => $key,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
        }

        event(new \App\Events\Notification($title, $message, 0, 0, 'contact_us_notification'));

        return mainResponse_2(true, __('common.sent_successfully'), (object)[], [], 200);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function create_chat(Request $request)
    {
        $rules = [
            'provider_id' => 'required',
            'provider_type' => 'required|in:1,2',
            'firebase_id' => 'required',
            'message' => 'required',
        ];

        $validation = [
            'firebase_id.required' => __('common.firebase_required'),
            'message.required' => __('common.message_required'),
        ];

        $validator = Validator::make($request->all(), $rules, $validation);
        if ($validator->fails()) {
            return mainResponse(false, $validator->errors()->first(), (object)[], $validator->errors()->messages(), 200);
        }

        $is_exists = Chat::query()->where('firebase_id', $request->firebase_id)
            ->where('provider_id', $request->provider_id)
            ->where('provider_type', $request->provider_type)
            ->where('user_id', auth('sanctum')->id())->first();

        if (!$is_exists) {
            Chat::query()->create([
                'provider_id' => $request->provider_id,
                'provider_type' => $request->provider_type,
                'user_id' => auth('sanctum')->id(),
                'firebase_id' => $request->firebase_id,
                'last_message' => $request->message,
                'last_message_date' => Carbon::now()->format('Y-m-d A g:i'),
                'provider_unread_messages' => 1,
            ]);
        } else {
            $is_exists->update([
                'last_message' => $request->message,
                'last_message_date' => Carbon::now()->format('Y-m-d A g:i'),
                'provider_unread_messages' => $is_exists->provider_unread_messages + 1,
            ]);
        }

        $user = auth('sanctum')->user();

        $title = 'رسالة جديدة';
        $message = 'لديك رسالة جديدة من المستخدم ' . $user->name;

        $notification = Notification::query()->create([
            'image' => @$user->image,
            'type' => $request->provider_type == 1 ? 'salon_chat_notification' : 'artist_chat_notification',
            'send_date' => Carbon::parse(Carbon::now())->format('Y-m-d'),
            'is_seen' => 0,
            'reference_id' => $request->provider_id,
            'user_id' => $user->id,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        foreach (locales() as $key => $value) {
            NotificationTranslation::query()->insert([
                'notification_id' => $notification->id,
                'title' => $key == 'ar' ? 'رسالة جديدة' : 'New Message',
                'message' => $key == 'ar' ? 'لديك رسالة جديدة من المستخدم ' . $user->name : 'You have new message from ' . $user->name,
                'locale' => $key,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
        }

        if ($request->provider_type == 1) {
            event(new \App\Events\Notification($title, $message, $request->provider_id, 0, 'chat'));
        } else {
            event(new \App\Events\Notification($title, $message, 0, $request->provider_id, 'chat'));
        }

        return mainResponse(true, __('common.done_successfully'), (object)[], [], 200);
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function chats(Request $request)
    {
        $chats = Chat::query()->where('user_id', auth('sanctum')->id());

        if ($request->name) {
            $salons_ids = SalonTranslation::query()->where('name', 'like', '%' . $request->name . '%')->pluck('salon_id');
            $artists_ids = MakeupArtistTranslation::query()->where('name', 'like', '%' . $request->name . '%')->pluck('makeup_artist_id');

            $chats = $chats->whereIn('provider_id', $salons_ids)->where('provider_type', 1);
            $chats = $chats->orWhereIn('provider_id', $artists_ids)->where('provider_type', 2);
        }

        $chats = $chats->get()->makeHidden(['created_at', 'updated_at']);

        $chats = $chats->sortByDesc('created_at')->sortByDesc('provider_unread_messages');

        return mainResponse(true, __('ok'), $chats->values(), [], 200);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function read_chat(Request $request)
    {
        $rules = [
            'provider_id' => 'required',
            'provider_type' => 'required|in:1,2',
            'firebase_id' => 'required',
        ];

        $validation = [
            'firebase_id.required' => __('common.firebase_required'),
        ];

        $validator = Validator::make($request->all(), $rules, $validation);
        if ($validator->fails()) {
            return mainResponse(false, $validator->errors()->first(), (object)[], $validator->errors()->messages(), 200);
        }

        $chat = Chat::query()->where('firebase_id', $request->firebase_id)
            ->where('provider_id', $request->provider_id)
            ->where('provider_type', $request->provider_type)
            ->where('user_id', auth('sanctum')->id())->first();
        if ($chat != null) {
            $chat->update([
                'user_unread_messages' => 0
            ]);
        }

        return mainResponse_2(true, __('common.done_successfully'), (object)[], [], 200);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \ErrorException
     */
    public function translate_chat_message(Request $request)
    {
        if (!$request->message) {
            $data = ['translated_message_ar' => '', 'translated_message_en' => ''];

            return mainResponse_2(true, __('ok'), $data, [], 200);
        }

        $tr = new \Stichoza\GoogleTranslate\GoogleTranslate();
        $tr->setSource('en');
        $tr->setSource();
        $tr->setTarget('ar');

        $translated_message_ar = $tr->translate($request->message);

        $tr->setSource('ar');
        $tr->setSource();
        $tr->setTarget('en');

        $translated_message_en = $tr->translate($request->message);

        $data = ['translated_message_ar' => $translated_message_ar, 'translated_message_en' => $translated_message_en];

        return mainResponse_2(true, __('ok'), $data, [], 200);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function create_support_chat(Request $request)
    {
        $rules = [
            'firebase_id' => 'required',
            'message' => 'required',
        ];

        $validation = [
            'firebase_id.required' => __('common.firebase_required'),
            'message.required' => __('common.message_required'),
        ];

        $validator = Validator::make($request->all(), $rules, $validation);
        if ($validator->fails()) {
            return mainResponse(false, $validator->errors()->first(), (object)[], $validator->errors()->messages(), 200);
        }

        $is_exists = Support::query()->where('firebase_id', $request->firebase_id)
            ->where('user_id', auth('sanctum')->id())->first();

        if (!$is_exists) {
            Support::query()->create([
                'admin_id' => 1,
                'user_id' => auth('sanctum')->id(),
                'firebase_id' => $request->firebase_id,
                'last_message' => $request->message,
                'last_message_date' => Carbon::now()->format('Y-m-d g:i A'),
                'user_unread_messages' => 1,
            ]);
        } else {
            $is_exists->update([
                'last_message' => $request->message,
                'last_message_date' => Carbon::now()->format('Y-m-d g:i A'),
                'user_unread_messages' => $is_exists->user_unread_messages + 1,
            ]);
        }

        $user = auth('sanctum')->user();

        $title = 'رسالة دعم فني جديدة';
        $message = 'لديك رسالة دعم فني جديدة من المستخدم ' . $user->name;

        $notification = Notification::query()->create([
            'image' => @$user->image,
            'type' => 'chat_support_notification',
            'send_date' => Carbon::parse(Carbon::now())->format('Y-m-d'),
            'is_seen' => 0,
            'reference_id' => 0,
            'user_id' => $user->id,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        foreach (locales() as $key => $value) {
            NotificationTranslation::query()->insert([
                'notification_id' => $notification->id,
                'title' => $key == 'ar' ? 'رسالة دعم فني جديدة' : 'New Support Message',
                'message' => $key == 'ar' ? 'لديك رسالة دعم فني جديدة من المستخدم ' . $user->name : 'You have new support message from ' . $user->name,
                'locale' => $key,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
        }

        event(new \App\Events\Notification($title, $message, 0, 0, 'support_chat'));

        return mainResponse(true, __('common.done_successfully'), (object)[], [], 200);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function read_support_chat(Request $request)
    {
        $rules = [
            'firebase_id' => 'required',
        ];

        $validation = [
            'firebase_id.required' => __('common.firebase_required'),
        ];

        $validator = Validator::make($request->all(), $rules, $validation);
        if ($validator->fails()) {
            return mainResponse(false, $validator->errors()->first(), (object)[], $validator->errors()->messages(), 200);
        }

        $chat = Support::query()->where('firebase_id', $request->firebase_id)
            ->where('user_id', auth('sanctum')->id())->first();
        if ($chat != null) {
            $chat->update([
                'user_unread_messages' => 0
            ]);
        }

        return mainResponse_2(true, __('common.done_successfully'), (object)[], [], 200);
    }
}
