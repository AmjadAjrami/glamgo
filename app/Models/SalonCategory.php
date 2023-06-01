<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SalonCategory extends Model
{
    use HasFactory;

    protected $table = 'salon_categories';
    protected $guarded = [];
    protected $appends = ['category_name'];
    protected $hidden = ['category', 'created_at', 'updated_at'];

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function getCategoryNameAttribute()
    {
        return @$this->category->name;
    }
}
