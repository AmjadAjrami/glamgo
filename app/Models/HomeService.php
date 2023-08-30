<?php

namespace App\Models;

use Astrotomic\Translatable\Translatable;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;

class HomeService extends Authenticatable
{
    use HasFactory, Translatable, SoftDeletes;

    protected $table = 'home_services';
    protected $guarded = [];
    protected $translatedAttributes = ['name', 'bio'];
    protected $appends = ['add_time', 'video', 'thumbnail', /*'distance', 'distance_int', */'categories', 'is_favorite', 'type', 'service_types'];
    protected $hidden = ['created_at', 'updated_at', 'deleted_at', 'status', 'translations', 'gallery', 'password',
        'home_services_categories', 'favorite', '\service_types', 'services'];

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

    public function home_service_categories()
    {
        return $this->hasMany(HomeServiceCategory::class, 'home_service_id');
    }

    public function gallery()
    {
        return $this->hasMany(HomeServiceGallery::class, 'home_service_id');
    }

    public function getCategoriesAttribute()
    {
        $count = count($this->home_service_categories);
        $i = 0;
        $string = '';
        foreach ($this->home_service_categories as $key => $category){
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
        return $this->gallery->where('type', 2)->first() == null ? url('/') . '/placeholder.jpg' : $this->gallery->where('type', 2)->first()->video;
    }

    public function getThumbnailAttribute()
    {
        return $this->gallery->where('type', 2)->first() == null ? url('/') . '/placeholder.jpg' : $this->gallery->where('type', 2)->first()->thumbnail;
    }

//    public function getDistanceAttribute()
//    {
//        if (request('lat') == null && request('lng') == null){
//            return '';
//        }
//        return getDistanceInKM($this->lat, $this->lng, request('lat'), request('lng')) . ' ' . __('common.km');
//    }
//
//    public function getDistanceIntAttribute()
//    {
//        if (request('lat') == null && request('lng') == null){
//            return 0;
//        }
//        return (int)getDistanceInKM($this->lat, $this->lng, request('lat'), request('lng'));
//    }

    public function favorite()
    {
        return $this->belongsToMany(User::class, 'favorites', 'home_service_id', 'user_id');
    }

    public function getIsFavoriteAttribute()
    {
        return $this->favorite->contains(auth('sanctum')->id());
    }

    public function services()
    {
        return $this->hasMany(Service::class, 'home_service_id')->where('service_category', 2);
    }

    public function getServiceTypesAttribute()
    {
        $services = [];
        $all_services = [];
        foreach ($this->services->groupBy('service_type_id') as $key => $service){
            $service_type = ServiceType::query()->find($key);
            $services['id'] = $key;
            $services['name'] = $service_type->name;
            $services['services'] = $service;
            $all_services[] = $services;
        }

        return $all_services;
    }

    public function getTypeAttribute()
    {
        return 2;
    }
}
