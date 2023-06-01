<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $guarded = [];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'country',
        'city'
    ];
    protected $appends = ['country_name', 'city_name', 'add_time', 'is_active', 'is_social'];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function getAddTimeAttribute()
    {
        return Carbon::parse($this->created_at)->format('y-m-d A g:i');
    }

    public function getImageAttribute($value)
    {
        if ($this->social_token != null){
            $result = str_contains('http', $value) ? $value : url('/') . '/images/' . $value;
            return Str::startsWith(Str::after($result, 'images/'), 'http') ? Str::after($result, 'images/') : url('/') . '/images/' . $value;
        }

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

    public function getIsActiveAttribute()
    {
        return $this->status;
    }

    public function getIsSocialAttribute()
    {
        return $this->social_provider != null;
    }
}
