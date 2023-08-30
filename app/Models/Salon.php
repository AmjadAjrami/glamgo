<?php

namespace App\Models;

use Astrotomic\Translatable\Translatable;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Salon extends Authenticatable
{
    use HasFactory, Translatable, SoftDeletes;

    protected $table = 'salons';
    protected $guarded = [];
    protected $translatedAttributes = ['name', 'bio'];
    protected $appends = ['add_time', 'video', 'thumbnail', 'distance', 'distance_int', 'categories', 'is_favorite', 'in_salon_service_types', 'home_service_types'];
    protected $hidden = ['created_at', 'updated_at', 'deleted_at', 'status', 'translations', 'gallery', 'password', 'salon_categories', 'favorite',
        'salon_service_types', 'in_salon_services', 'home_services'];

    public function getImageAttribute($value)
    {
        return $value ? url('/') . '/images/' . $value : url('/') . '/placeholder.jpg';
    }

    public function getCoverImageAttribute($value)
    {
        return $value ? url('/') . '/images/' . $value : url('/') . '/placeholder.jpg';
    }

    public function getAddTimeAttribute()
    {
        return Carbon::parse($this->created_at)->format('y-m-d A g:i');
    }

    public function salon_categories()
    {
        return $this->hasMany(SalonCategory::class, 'salon_id');
    }

    public function gallery()
    {
        return $this->hasMany(SalonGallery::class, 'salon_id');
    }

    public function getCategoriesAttribute()
    {
        $count = count($this->salon_categories);
        $i = 0;
        $string = '';
        foreach ($this->salon_categories as $key => $category){
            if (++$i != $count){
                $string .= $category->category_name . ' , ';
            }else{
                $string .= $category->category_name;
            }
        }
        return $string;
    }

    public function getVideoAttribute()
    {
        return $this->gallery->where('type', 2)->first() == null ? url('/') . '/placeholder.jpg' : $this->gallery->where('type', 2)->first()->item;
    }

    public function getThumbnailAttribute()
    {
        return $this->gallery->where('type', 2)->first() == null ? url('/') . '/placeholder.jpg' : $this->gallery->where('type', 2)->first()->thumbnail;
    }

    public function getDistanceAttribute()
    {
        if (request('lat') == null && request('lng') == null){
            return '';
        }
        return getDistanceInKM($this->lat, $this->lng, request('lat'), request('lng')) . ' ' . __('common.km');
    }

    public function getDistanceIntAttribute()
    {
        if (request('lat') == null && request('lng') == null){
            return 0;
        }
        return (int)getDistanceInKM($this->lat, $this->lng, request('lat'), request('lng'));
    }

    public function favorite()
    {
        return $this->belongsToMany(User::class, 'favorites', 'salon_id', 'user_id');
    }

    public function getIsFavoriteAttribute()
    {
        return $this->favorite->contains(auth('sanctum')->id());
    }

    public function salon_service_types()
    {
        return $this->hasMany(ServiceType::class, 'salon_id');
    }

    public function in_salon_services()
    {
        return $this->hasMany(Service::class, 'salon_id')->where('status', 1)->whereIn('service_category', [1, 3]);
    }

    public function home_services()
    {
        return $this->hasMany(Service::class, 'salon_id')->where('status', 1)->whereIn('service_category', [2, 3]);
    }

    public function getInSalonServiceTypesAttribute()
    {
        $services = [];
        $all_services = [];
        foreach ($this->in_salon_services->groupBy('service_type_id') as $key => $service){
            $service_type = ServiceType::query()->find($key);
            if ($service_type->status == 1){
                $services['id'] = $key;
                $services['name'] = $service_type->name;
                $services['services'] = $service;
                $all_services[] = $services;
            }
        }

        return $all_services;
    }

    public function getHomeServiceTypesAttribute()
    {
        $services = [];
        $all_services = [];
        foreach ($this->home_services->groupBy('service_type_id') as $key => $service){
            $service_type = ServiceType::query()->find($key);
            if ($service_type->status == 1){
                $services['id'] = $key;
                $services['name'] = $service_type->name;
                $services['services'] = $service;
                $all_services[] = $services;
            }
        }

        return $all_services;
    }

    public function products()
    {
        return $this->hasMany(Product::class, 'salon_id');
    }
}
