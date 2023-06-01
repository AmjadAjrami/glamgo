<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Address;
use App\Models\CartItem;
use App\Models\MobileToken;
use App\Models\Notification;
use App\Models\NotificationTranslation;
use App\Models\NotificationUser;
use App\Models\Order;
use App\Models\OrderStatus;
use App\Models\Setting;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class OrderController extends Controller
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
        $orders = Order::query()->get();
        $total_sales = 0;
        $salons_sales = 0;
        $admin_sales = 0;
        foreach ($orders as $order) {
            $total_sales += $order->total_price_after_code == null ? $order->total_price : $order->total_price_after_code;
            if ($order->salon_id == null){
                $admin_sales += $order->total_price_after_code == null ? $order->total_price : $order->total_price_after_code;
            }else{
                $salons_sales += $order->total_price_after_code == null ? $order->total_price : $order->total_price_after_code;
            }
        }

        return view('admin.orders.index', compact('total_sales', 'admin_sales', 'salons_sales'));
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
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
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
     * Remove the specified resource from storage.
     *
     * @param int $id
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
    public function update_order_status(Request $request)
    {
        $rules = [
            'order_id' => 'required',
            'status' => 'required',
        ];

        $this->validate($request, $rules);

        $order = Order::query()->find($request->order_id);

        $order->update([
            'status' => $request->status,
            'reject_reason' => $request->reject_reason ?? null,
        ]);

        OrderStatus::query()->insert([
            'order_id' => $order->id,
            'status' => $request->status,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        $image = url('/icons/store.png');

        $title = app()->getLocale() == 'ar' ? 'حالة الطلب' : 'Update Order Status';
        $notification_title_ar = 'حالة الطلب';
        $notification_title_en = 'Order Status';
        if ($request->status == 1) {
            $notification_message_ar = 'تم تغيير حالة طلب برقم #' . $request->order_id . ' الي قيد الشحن';
            $notification_message_en = 'Your order number #' . $request->order_id .' has been changed to status (Shipping)';
        } elseif ($request->status == 2) {
            $notification_message_ar = 'تم تغيير حالة طلب برقم #' . $request->order_id . ' الي مكتمل';
            $notification_message_en = 'Your order number #' . $request->order_id .' has been changed to status (Completed)';
        } elseif ($request->status == 4) {
            $notification_message_ar = 'تم تغيير حالة طلب برقم #' . $request->order_id . ' الي مرفوض';
            $notification_message_en = 'Your order number #' . $request->order_id .' has been changed to status (Rejected)';
        }

        $user = User::query()->find($order->user_id);

        $message = app()->getLocale() == 'ar' ? $notification_message_ar : $notification_message_en;

        $notification = Notification::query()->create([
            'image' => $image,
            'type' => 'order_status',
            'send_date' => Carbon::parse(Carbon::now())->format('Y-m-d'),
            'reference_id' => $order->id,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        foreach (locales() as $key => $value) {
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
            'user_id' => $order->user_id,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        if ($user->notification_active == 1) {
            $android_tokens_user = MobileToken::query()->where('user_id', $order->user_id)->where('device', 'android')->pluck('token');
            $ios_tokens_user = MobileToken::query()->where('user_id', $order->user_id)->where('device', 'ios')->pluck('token');

            fcmNotification($android_tokens_user, $notification->id, $title, $message, $message, 'order_status', 'android', $order->id
                , $image, null, null, null, null, $order->id);
            fcmNotification($ios_tokens_user, $notification->id, $title, $message, $message, 'order_status', 'ios', $order->id
                , $image, null, null, null, null, $order->id);
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
        $orders = Order::query()->orderByDesc('created_at');
        return DataTables::of($orders)->filter(function ($query) use ($request) {
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
//                $date = Carbon::parse($request->date)->format('Y-m-d');
                $query->where('created_at', $request->date);
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
    public function order_details(Request $request)
    {
        $rules = [
            'order_id' => 'required',
        ];

        $this->validate($request, $rules);

        $order = Order::query()->find($request->order_id);

        $address = Address::query()->find($order->address_id);

        $cart_items = CartItem::query()->where('cart_id', $order->cart_id)->get();

        $delivery_price = Setting::query()->where('key', 'delivery_price')->first()->value;

        $products = [];
        foreach ($cart_items as $cart_item){
            $products[] = [
                'product_id' => $cart_item->product_id,
                'product_title' => @$cart_item->product->title,
                'product_image' => @$cart_item->product->image,
                'product_category' => @$cart_item->product->category_name,
                'total_price' => $cart_item->total_price,
                'quantity' => $cart_item->quantity,
                'price' => $cart_item->discount_price != null ? $cart_item->discount_price : $cart_item->price,
            ];
        }

        $cart_items_price = $order->cart->items->sum('item_price');

        return ['status' => true, 'order' => $order, 'products' => $products, 'address' => $address, 'delivery_price' => $delivery_price, 'cart_items_price' => $cart_items_price];
    }

    /**
     * @param $id
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function order_invoice($id)
    {
        $order = Order::query()->find($id);
        return view('admin.bill', compact('order'));
    }
}
