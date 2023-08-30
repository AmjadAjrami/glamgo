<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HomeServiceGallery extends Model
{
    use HasFactory;

    protected $table = 'home_service_galleries';
    protected $guarded = [];

    protected $hidden = ['created_at', 'updated_at'];

    public function getItemAttribute($value)
    {
        return $value ? url('/') . '/gallery/' . $value : url('/') . '/placeholder.jpg';
    }

    public function getThumbnailAttribute($value)
    {
        if ($value == null){
            return '';
        }
        return $value ? url('/') . '/gallery/' . $value : url('/') . '/placeholder.jpg';
    }
}
