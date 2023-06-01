<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CartItem extends Model
{
    use HasFactory;

    protected $table = 'cart_items';
    protected $guarded = [];
    protected $appends = ['item_price'];

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id')->withTrashed();
    }

    public function getItemPriceAttribute()
    {
        return $this->discount_price != null ? $this->discount_price * $this->quantity : $this->price * $this->quantity;
    }
}
