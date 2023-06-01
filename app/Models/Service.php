<?php

namespace App\Models;

use Astrotomic\Translatable\Translatable;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    use HasFactory, Translatable;

    protected $table = 'services';
    protected $guarded = [];
    protected $translatedAttributes = ['name', 'description'];
    protected $appends = ['add_time', 'service_type_name', 'service_category_name'];
    protected $hidden = ['created_at', 'updated_at', 'deleted_at', 'status', 'translations', 'service_type'];

    public function getImageAttribute($value)
    {
        return $value ? url('/') . '/images/' . $value : url('/') . '/placeholder.jpg';
    }

    public function getAddTimeAttribute()
    {
        return Carbon::parse($this->created_at)->format('y-m-d A g:i');
    }

    public function service_type()
    {
        return $this->belongsTo(ServiceType::class, 'service_type_id');
    }

    public function getServiceTypeNameAttribute()
    {
        return @$this->service_type->name;
    }

    public function getServiceCategoryNameAttribute()
    {
        if ($this->service_category == 1){
            return __('common.internal_services');
        }elseif ($this->service_category == 2){
            return __('common.external_services');
        }else{
            return __('common.both');
        }
    }
}
