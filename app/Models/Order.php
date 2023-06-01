<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $table = 'orders';
    protected $guarded = [];
    protected $appends = ['status_text', 'order_time', 'image', 'user_name', 'user_email', 'user_mobile', 'add_time', 'address_details', 'address_postal_number',
        'payment_method_title', 'final_price', 'address_mobile', 'can_cancel'];
    protected $hidden = ['created_at', 'updated_at', 'cart', 'user', 'address', 'payment_method'];

    public function getAddTimeAttribute()
    {
        return Carbon::parse($this->created_at)->format('y-m-d A g:i');
    }

    public function cart()
    {
        return $this->belongsTo(Cart::class, 'cart_id');
    }

    public function getImageAttribute()
    {
        return @$this->cart->items->first() != null ? @$this->cart->items->first()->product->image : '';
    }

    public function getStatusTextAttribute()
    {
        if ($this->status == 1){
            return __('common.shipped');
        }elseif ($this->status == 2){
            return __('common.completed');
        }elseif ($this->status == 3){
            return __('common.canceled');
        }elseif ($this->status == 4){
            return __('common.rejected');
        }
    }

    public function getOrderTimeAttribute()
    {
        return Carbon::parse($this->created_at)->diffForHumans();
    }

    public function address()
    {
        return $this->belongsTo(Address::class, 'address_id');
    }

    public function payment_method()
    {
        return $this->belongsTo(PaymentMethod::class, 'payment_method_id');
    }

    public function getAddressDetailsAttribute()
    {
        return @$this->address->address_name . ' - ' . @$this->address->detailed_address;
    }

    public function getAddressPostalNumberAttribute()
    {
        return @$this->address->postal_number;
    }

    public function getAddressMobileAttribute()
    {
        return @$this->address->mobile;
    }

    public function getPaymentMethodTitleAttribute()
    {
        return @$this->payment_method->title;
    }

    public function user()
    {
        return $this->belongsTo(User::class,'user_id');
    }

    public function getUserNameAttribute()
    {
        return @$this->user->name;
    }

    public function getUserEmailAttribute()
    {
        return @$this->user->email;
    }

    public function getUserMobileAttribute()
    {
        return @$this->user->mobile;
    }

    public function getFinalPriceAttribute()
    {
        return $this->total_price_after_code != null ? $this->total_price_after_code : $this->total_price;
    }

    public function getCanCancelAttribute()
    {
        $reservation_time = Carbon::parse($this->updated_at)->addMinutes(5);
        $now = Carbon::now();

        if ($now->isBefore($reservation_time)){
            return true;
        }

        return false;
    }
}
