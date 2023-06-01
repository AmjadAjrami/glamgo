<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
{
    use HasFactory;

    protected $table = 'reservations';
    protected $guarded = [];
    protected $casts = [
        'salon_id' => 'integer',
        'makeup_artist_id' => 'integer',
    ];
    protected $hidden = ['salon', 'artist', 'time', 'items', 'user', 'payment_method'];
    protected $appends = ['provider_type', 'provider_id', 'provider_name', 'provider_image', 'provider_categories', 'reservation_provider_type',
        'reservation_date', 'status_text', 'date_text', 'reservation_time', 'user_name', 'user_mobile', 'user_email', 'services', 'quantity', 'payment_method_title'];

    public function items()
    {
        if ($this->offer_id == null){
            return $this->hasMany(ReservationItem::class, 'reservation_id');
        }

        return  $this->belongsTo(Offer::class, 'offer_id');
    }

    public function getServicesAttribute()
    {
        if ($this->offer_id == null){
            $count = count($this->items);
            $i = 0;
            $string = '';
            foreach ($this->items as $key => $service){
                if (++$i != $count){
                    $string .= $service->service_name . ' , ';
                }else{
                    $string .= $service->service_name;
                }
            }
            return $string;
        }

        return @$this->items->title;
    }

    public function getQuantityAttribute()
    {
        if ($this->offer_id == null){
            return $this->items->sum('quantity');
        }

        return $this->offer_quantity;
    }

    public function salon()
    {
        return $this->belongsTo(Salon::class, 'salon_id');
    }

    public function artist()
    {
        return $this->belongsTo(MakeupArtist::class, 'makeup_artist_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function getReservationProviderTypeAttribute()
    {
        if ($this->salon_id != null){
            return 'salon';
        }else{
            return 'artist';
        }
    }

    public function getProviderIdAttribute()
    {
        if ($this->salon_id != null){
            return $this->salon_id;
        }else{
            return $this->makeup_artist_id;
        }
    }

    public function getProviderTypeAttribute()
    {
        if ($this->salon_id != null){
            return __('common.salon');
        }else{
            return __('common.artist');
        }
    }

    public function getProviderNameAttribute()
    {
        if ($this->salon_id != null){
            return @$this->salon->name;
        }else{
            return @$this->artist->name;
        }
    }

    public function getProviderImageAttribute()
    {
        if ($this->salon_id != null){
            return @$this->salon->image;
        }else{
            return @$this->artist->image;
        }
    }

    public function getProviderCategoriesAttribute()
    {
        if ($this->salon_id != null){
            return @$this->salon->categories;
        }else{
            return @$this->artist->categories;
        }
    }

    public function getUserNameAttribute()
    {
        return @$this->user->name;
    }

    public function getUserMobileAttribute()
    {
        return @$this->user->mobile;
    }

    public function getUserEmailAttribute()
    {
        return @$this->user->email;
    }

    public function time()
    {
        return $this->belongsTo(BookingTime::class, 'booking_time_id');
    }

    public function getDateTextAttribute()
    {
        return __('common.' . \Carbon\Carbon::parse($this->date)->format('l')) . ' ' . \Carbon\Carbon::parse($this->date)->format('m/d');
    }

    public function getReservationTimeAttribute()
    {
        if ($this->time != null){
            return Carbon::parse(@$this->time->from)->isoFormat('h:mm a');
        }

        return '';
    }

    public function getReservationDateAttribute()
    {
        return $this->date_text . ' - ' . $this->reservation_time;
    }

    public function getStatusTextAttribute()
    {
        if ($this->status == 1){
            return __('common.under_confirmation');
        }elseif ($this->status == 2){
            return __('common.confirmed');
        }elseif ($this->status == 3){
            return __('common.completed');
        }elseif ($this->status == 4){
            return __('common.canceled');
        }elseif ($this->status == 5){
            return __('common.rejected');
        }
    }

    public function payment_method()
    {
        return $this->belongsTo(PaymentMethod::class, 'payment_method_id');
    }

    public function getPaymentMethodTitleAttribute()
    {
        return @$this->payment_method->title;
    }
}
