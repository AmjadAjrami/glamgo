<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NotificationUser extends Model
{
    use HasFactory;

    protected $table = 'notification_users';
    protected $guarded = [];
    protected $appends = ['title', 'message', 'image', 'type', 'send_date', 'reference_id', 'provider_id', 'provider_name', 'provider_type'];
    protected $hidden = ['notification_data', 'created_at', 'updated_at'];

    public function notification_data()
    {
        return $this->belongsTo(Notification::class, 'notification_id');
    }

    public function getTitleAttribute()
    {
        return @$this->notification_data->title;
    }

    public function getMessageAttribute()
    {
        return @$this->notification_data->message;
    }

    public function getImageAttribute()
    {
        return @$this->notification_data->image;
    }

    public function getTypeAttribute()
    {
        return @$this->notification_data->type;
    }

    public function getSendDateAttribute()
    {
        return @$this->notification_data->send_date;
    }

    public function getReferenceIDAttribute()
    {
        return @$this->notification_data->reference_id;
    }

    public function getProviderIdAttribute()
    {
        if ($this->notification_data->type == 'new_salon_message') {
            return @$this->notification_data->reference_id;
        } elseif ($this->notification_data->type == 'new_artist_message') {
            return @$this->notification_data->reference_id;
        }

        return null;
    }

    public function getProviderNameAttribute()
    {
        if ($this->notification_data->type == 'new_salon_message') {
            return Salon::query()->find(@$this->notification_data->reference_id)->name;
        } elseif ($this->notification_data->type == 'new_artist_message') {
            return MakeupArtist::query()->find(@$this->notification_data->reference_id)->name;
        }

        return null;
    }

    public function getProviderTypeAttribute()
    {
        if ($this->notification_data->type == 'new_salon_message') {
            return 'salon';
        } elseif ($this->notification_data->type == 'new_artist_message') {
            return 'artist';
        }

        return null;
    }
}
