<?php

namespace App\Models;

use Astrotomic\Translatable\Translatable;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory, Translatable;

    protected $table = 'categories';
    protected $guarded = [];
    protected $translatedAttributes = ['name'];
    protected $appends = ['add_time', 'category_type_text'];
    protected $hidden = ['created_at', 'updated_at', 'deleted_at', 'status', 'translations'];

    public function getImageAttribute($value)
    {
        if ($value != 0){
            return $value ? url('/') . '/images/' . $value : url('/') . '/placeholder.jpg';
        }else{
            return '';
        }
    }

    public function getAddTimeAttribute()
    {
        return Carbon::parse($this->created_at)->format('y-m-d A g:i');
    }

    public function getCategoryTypeTextAttribute()
    {
        return $this->user_type == 1 ? __('common.men') : __('common.women');
    }
}
