<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Withdraw extends Model
{
    use HasFactory;

    protected $table = 'withdraws';
    protected $guarded = [];
    protected $appends = ['add_time'];

    public function getImageAttribute($value)
    {
        return $value ? url('/') . '/images/' . $value : url('/') . '/placeholder.jpg';
    }

    public function getAddTimeAttribute()
    {
        return Carbon::parse($this->created_at)->format('y-m-d A g:i');
    }

    public function salon()
    {
        return $this->belongsTo(Salon::class, 'salon_id');
    }

    public function artist()
    {
        return $this->belongsTo(MakeupArtist::class, 'makeup_artist_id');
    }
}
