<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PromoCode extends Model
{
    use HasFactory;

    protected $table = 'promo_codes';
    protected $guarded = [];
    protected $appends = ['add_time', 'discount_type_text'];

    public function getAddTimeAttribute()
    {
        return Carbon::parse($this->created_at)->format('y-m-d A g:i');
    }

    public function getDiscountTypeTextAttribute()
    {
        return $this->discount_type == 1 ? __('common.fixed_price') : __('common.percentage');
    }
}
