<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Chat extends Model
{
    use HasFactory;

    protected $table = 'chats';
    protected $guarded = [];
    protected $appends = ['provider_name', 'provider_image'];
    protected $hidden = ['provider'];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function provider()
    {
        if ($this->provider_type == 1){
            return $this->belongsTo(Salon::class, 'provider_id');
        }else{
            return $this->belongsTo(MakeupArtist::class, 'provider_id');
        }
    }

    public function getProviderNameAttribute()
    {
        return @$this->provider->name;
    }

    public function getProviderImageAttribute()
    {
        return @$this->provider->image;
    }
}
