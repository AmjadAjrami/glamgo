<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReservationItem extends Model
{
    use HasFactory;

    protected $table = 'reservation_items';
    protected $guarded = [];
    protected $hidden = ['service'];
    protected $appends = ['service_name', 'service_image', 'total_price', 'service_category_name'];

    public function service()
    {
        return $this->belongsTo(Service::class, 'service_id');
    }

    public function getServiceNameAttribute()
    {
        return $this->service->name;
    }

    public function getServiceImageAttribute()
    {
        return $this->service->image;
    }

    public function getTotalPriceAttribute()
    {
        if ($this->discount_price == null){
            return $this->price * $this->quantity;
        }else{
            return $this->discount_price * $this->quantity;
        }
    }

    public function getServiceCategoryNameAttribute()
    {
        return $this->service->service_category_name;
    }
}
