<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Support extends Model
{
    use HasFactory;

    protected $table = 'supports';
    protected $guarded = [];
    protected $appends = ['user_name', 'user_image'];
    protected $hidden = ['user'];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function getUserNameAttribute()
    {
        return @$this->user->name;
    }

    public function getUserImageAttribute()
    {
        return @$this->user->image;
    }
}
