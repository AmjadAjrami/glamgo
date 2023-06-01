<?php

namespace App\Models;

use Astrotomic\Translatable\Translatable;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class City extends Model
{
    use HasFactory, Translatable, SoftDeletes;

    protected $table = 'cities';
    protected $guarded = [];
    protected $hidden = ['translations', 'created_at', 'updated_at', 'status', 'country'];
    protected $translatedAttributes = ['name'];
    protected $appends = ['add_time', 'country_name'];

    public function getAddTimeAttribute()
    {
        return Carbon::parse($this->created_at)->format('y-m-d A g:i');
    }

    public function country()
    {
        return $this->belongsTo(Country::class, 'country_id');
    }

    public function getCountryNameAttribute()
    {
        return @$this->country->name;
    }
}
