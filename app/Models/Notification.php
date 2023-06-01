<?php

namespace App\Models;

use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory, Translatable;

    protected $table = 'notifications';
    protected $guarded = [];
    protected $translatedAttributes = ['title', 'message'];
    protected $hidden = ['created_at', 'updated_at', 'user', 'translations', 'users'];
    protected $appends = ['user_name', 'user_image', 'notification_users'];

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
        return @$this->user == null ? url('/') . '/placeholder.jpg' : @$this->user->image;
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'notification_users', 'user_id', 'notification_id');
    }

    public function getNotificationUsersAttribute()
    {
        return $this->users;
    }
}
