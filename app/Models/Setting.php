<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use HasFactory;

    protected $table = 'settings';
    protected $guarded = [];
    protected $hidden = ['created_at', 'updated_at'];
    protected $appends = ['icon'];

    public function getIconAttribute()
    {
        if ($this->key == 'facebook_link'){
            return url('/icons/facebook.png');
        }elseif ($this->key == 'twitter_link'){
            return url('/icons/twitter.png');
        }elseif ($this->key == 'instagram_link'){
            return url('/icons/instagram.png');
        }elseif ($this->key == 'snapchat_link'){
            return url('/icons/snap.png');
        }elseif ($this->key == 'whatsapp_link'){
            return url('/icons/whats.png');
        }else{
            return '';
        }
    }
}
