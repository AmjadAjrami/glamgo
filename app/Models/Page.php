<?php

namespace App\Models;

use Astrotomic\Translatable\Translatable;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Page extends Model
{
    use HasFactory, Translatable;

    protected $table = 'pages';
    protected $guarded = [];
    protected $hidden = ['translations', 'created_at', 'updated_at'];
    protected $translatedAttributes = ['title', 'description'];
    protected $appends = ['add_time'];

    public function getAddTimeAttribute()
    {
        return Carbon::parse($this->created_at)->format('y-m-d A g:i');
    }
}
