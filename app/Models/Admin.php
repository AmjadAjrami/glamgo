<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Spatie\Permission\Traits\HasRoles;

class Admin extends Authenticatable
{
    use HasFactory, HasRoles;

    protected $table = 'admins';
    protected $guarded = [];
    protected $appends = ['country_name', 'city_name'];
    protected $hidden = ['country', 'city'];

    public function getImageAttribute($value)
    {
        return $value ? url('/') . '/images/' . $value : url('/') . '/placeholder.jpg';
    }

    public function country()
    {
        return $this->belongsTo(Country::class, 'country_id');
    }

    public function city()
    {
        return $this->belongsTo(City::class, 'city_id');
    }

    public function getCountryNameAttribute()
    {
        return @$this->country->name;
    }

    public function getCityNameAttribute()
    {
        return @$this->city->name;
    }
}
