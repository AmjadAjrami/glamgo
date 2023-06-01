<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Favorite extends Model
{
    use HasFactory;

    protected $table = 'favorites';
    protected $guarded = [];
    protected $hidden = ['created_at', 'updated_at', 'salon', 'makeup_artist'];
    protected $appends = ['image', 'cover_image', 'name', 'categories', 'distance', 'distance_int', 'is_favorite'];

    public function salon()
    {
        return $this->belongsTo(Salon::class, 'salon_id');
    }

    public function makeup_artist()
    {
        return $this->belongsTo(MakeupArtist::class, 'makeup_artist_id');
    }

    public function getImageAttribute()
    {
        if ($this->salon_id != null){
            return @$this->salon->image;
        }else{
            return @$this->makeup_artist->image;
        }
    }

    public function getCoverImageAttribute()
    {
        if ($this->salon_id != null){
            return @$this->salon->cover_image;
        }else{
            return @$this->makeup_artist->cover_image;
        }
    }

    public function getNameAttribute()
    {
        if ($this->salon_id != null){
            return @$this->salon->name;
        }else{
            return @$this->makeup_artist->name;
        }
    }

    public function getCategoriesAttribute()
    {
        if ($this->salon_id != null){
            return @$this->salon->categories;
        }else{
            return @$this->makeup_artist->categories;
        }
    }

    public function getDistanceAttribute()
    {
        if ($this->salon_id != null){
            return @$this->salon->distance;
        }else{
            return @$this->makeup_artist->distance;
        }
    }

    public function getDistanceIntAttribute()
    {
        if ($this->salon_id != null){
            return @$this->salon->distance_int;
        }else{
            return @$this->makeup_artist->distance_int;
        }
    }

    public function getIsFavoriteAttribute()
    {
        if ($this->salon_id != null){
            return @$this->salon->is_favorite;
        }else{
            return @$this->makeup_artist->is_favorite;
        }
    }
}
