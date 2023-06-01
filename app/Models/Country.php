<?php

namespace App\Models;

use Astrotomic\Translatable\Translatable;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Country extends Model
{
    use HasFactory, Translatable, SoftDeletes;

    protected $table = 'countries';
    protected $guarded = [];
    protected $translatedAttributes = ['name'];
    protected $appends = ['add_time', 'country_cities'];
    protected $hidden = ['created_at', 'updated_at', 'deleted_at', 'status', 'cities', 'translations'];

    public function getFlagAttribute($value)
    {
        return $value ? url('/') . '/images/' . $value : url('/') . '/placeholder.jpg';
    }

    public function getAddTimeAttribute()
    {
        return Carbon::parse($this->created_at)->format('y-m-d A g:i');
    }

    public function cities()
    {
        return $this->hasMany(City::class, 'country_id')->join('city_translations', 'city_translations.city_id', '=', 'cities.id')
            ->select('city_translations.name as city_name', 'cities.*')->where('city_translations.locale', app()->getLocale())
            ->orderBy('city_name', 'asc');
    }

    public function getCountryCitiesAttribute()
    {
        return $this->cities;
    }
}
