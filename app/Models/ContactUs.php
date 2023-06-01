<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContactUs extends Model
{
    use HasFactory;

    protected $table = 'contact_us';
    protected $guarded = [];
    protected $appends = ['user_name', 'user_mobile', 'user_email', 'add_time'];
    protected $hidden = ['user'];

    public function getAddTimeAttribute()
    {
        return Carbon::parse($this->created_at)->format('y-m-d A g:i');
    }

    public function getImageAttribute($value)
    {
        return $value ? url('/') . '/images/' . $value : url('/') . '/placeholder.jpg';
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function getUserNameAttribute()
    {
        return @$this->user->name;
    }

    public function getUserMobileAttribute()
    {
        return @$this->user->mobile;
    }

    public function getUserEmailAttribute()
    {
        return @$this->user->email;
    }
}
