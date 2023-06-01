<?php

namespace App\Models;

use Astrotomic\Translatable\Translatable;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServiceType extends Model
{
    use HasFactory, Translatable;

    protected $table = 'service_types';
    protected $guarded = [];
    protected $translatedAttributes = ['name'];
    protected $appends = ['add_time', 'type_services'];
    protected $hidden = ['created_at', 'updated_at', 'deleted_at', 'status', 'translations', 'type_services'];

    public function getAddTimeAttribute()
    {
        return Carbon::parse($this->created_at)->format('y-m-d A g:i');
    }

    public function type_services()
    {
        return $this->hasMany(Service::class, 'service_type_id');
    }
}
