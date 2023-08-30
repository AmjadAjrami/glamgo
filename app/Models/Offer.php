<?php

namespace App\Models;

use Astrotomic\Translatable\Translatable;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Offer extends Model
{
    use HasFactory, Translatable, SoftDeletes;

    protected $table = 'offers';
    protected $guarded = [];
    protected $translatedAttributes = ['title', 'description'];
    protected $appends = ['add_time', 'salon_name', 'artist_name', 'owner_id', 'owner_type', 'owner_name', 'owner_image', 'owner_category', 'images', 'category_name', 'has_discount'];
    protected $hidden = ['created_at', 'updated_at', 'deleted_at', 'status', 'translations', 'salon', 'artist', 'category'];

    public function getImageAttribute($value)
    {
        return $value ? url('/') . '/images/' . $value : url('/') . '/placeholder.jpg';
    }

    public function getImagesAttribute()
    {
        return array([
            'id' => 1,
            'image' => $this->image
        ]);
    }

    public function getAddTimeAttribute()
    {
        return Carbon::parse($this->created_at)->format('y-m-d A g:i');
    }

    public function salon()
    {
        return $this->belongsTo(Salon::class, 'salon_id')->withTrashed();
    }

    public function getSalonNameAttribute()
    {
        return @$this->salon->name;
    }

    public function artist()
    {
        return $this->belongsTo(MakeupArtist::class, 'makeup_artist_id')->withTrashed();
    }

    public function getArtistNameAttribute()
    {
        return @$this->artist->name;
    }

    public function getOwnerIDAttribute()
    {
        if ($this->salon_id != null){
            return @$this->salon->id;
        }else{
            return @$this->artist->id;
        }
    }

    public function getOwnerTypeAttribute()
    {
        if ($this->salon_id != null){
            return 'salon';
        }else{
            return 'artist';
        }
    }

    public function getOwnerNameAttribute()
    {
        if ($this->salon_id != null){
            return @$this->salon->name;
        }else{
            return @$this->artist->name;
        }
    }

    public function getOwnerImageAttribute()
    {
        if ($this->salon_id != null){
            return @$this->salon->image;
        }else{
            return @$this->artist->image;
        }
    }

    public function getOwnerCategoryAttribute()
    {
        if ($this->salon_id != null){
            return @$this->salon->categories;
        }else{
            return @$this->artist->categories;
        }
    }

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function getCategoryNameAttribute()
    {
        return @$this->category->name;
    }

    public function getHasDiscountAttribute()
    {
        return $this->discount_price != null ? 1 : 0;
    }
}
