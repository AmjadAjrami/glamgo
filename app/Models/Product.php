<?php

namespace App\Models;

use Astrotomic\Translatable\Translatable;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory, Translatable, SoftDeletes;

    protected $table = 'products';
    protected $guarded = [];
    protected $translatedAttributes = ['title', 'description'];
    protected $appends = ['add_time', 'category_name', 'image', 'images', 'is_new', 'is_available', 'owner_id', 'owner_type', 'owner_name', 'owner_image', 'owner_products_count', 'evacuation_responsibility'];
    protected $hidden = ['created_at', 'updated_at', 'deleted_at', 'status', 'translations', 'product_images', 'category', 'salon', 'cover_image'];

    public function getAddTimeAttribute()
    {
        return Carbon::parse($this->created_at)->format('y-m-d A g:i');
    }

    public function product_images()
    {
        return $this->hasMany(ProductImage::class, 'product_id');
    }

    public function getImageAttribute()
    {
        return $this->cover_image ? url('/') . '/images/' . $this->cover_image : url('/') . '/placeholder.jpg';
    }

    public function getImagesAttribute()
    {
        return $this->product_images;
    }

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function getCategoryNameAttribute()
    {
        return @$this->category->name;
    }

    public function getIsNewAttribute()
    {
        $now_dt = Carbon::now();
        return Carbon::parse($this->created_at)->diffInDays($now_dt) <= 0;
    }

    public function salon()
    {
        return $this->belongsTo(Salon::class, 'salon_id');
    }

    public function getOwnerIdAttribute()
    {
        return $this->salon_id == null ? 0 : $this->salon->id;
    }

    public function getOwnerTypeAttribute()
    {
        return $this->salon_id == null ? __('common.management') : 'salon';
    }

    public function getOwnerNameAttribute()
    {
        return $this->salon_id == null ? 'Glamgo - جلامجو' : $this->salon->name;
    }

    public function getOwnerImageAttribute()
    {
        return $this->salon_id == null ? url('/') . '/logo.png' : $this->salon->image;
    }

    public function getOwnerProductsCountAttribute()
    {
        $admin_products = Product::query()->where('salon_id', null)->count();
        return $this->salon_id == null ? $admin_products : count($this->salon->products);
    }

    public function getIsAvailableAttribute()
    {
        return true;
    }

    public function getEvacuationResponsibilityAttribute()
    {
        $setting =Setting::query()->where('key', 'evacuation_responsibility')->first();
        if ($setting){
            return request()->header('accept-language') == 'ar' ? $setting->value : $setting->value_en;
        }
        return 'نحن لسنا مسؤولين عن ترخيص هذا المنتج من وزارة الصحة ، ولكنه يتبع لمالك المنتج';
    }
}
