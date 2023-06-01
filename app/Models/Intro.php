<?php

namespace App\Models;

use Astrotomic\Translatable\Translatable;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Intro extends Model
{
    use HasFactory, Translatable;

    protected $table = 'intros';
    protected $guarded = [];
    protected $translatedAttributes = ['title', 'description'];
    protected $appends = ['add_time'];
    protected $hidden = ['created_at', 'updated_at', 'deleted_at', 'status', 'translations'];

    public function getImageAttribute($value)
    {
        return $value ? url('/') . '/images/' . $value : url('/') . '/placeholder.jpg';
    }

    public function getAddTimeAttribute()
    {
        return Carbon::parse($this->created_at)->format('y-m-d A g:i');
    }
}
