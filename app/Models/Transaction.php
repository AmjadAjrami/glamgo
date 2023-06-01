<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $table = 'transactions';
    protected $guarded = [];
    protected $appends = ['title', 'image', 'date'];
    protected $hidden = ['created_at', 'updated_at'];

    public function getTitleAttribute()
    {
        if ($this->type == 1){
            return __('common.pay');
        }elseif ($this->type == 2){
            return __('common.wallet_charging');
        }elseif ($this->type == 3){
            return __('common.refunded_balance');
        }
    }

    public function getImageAttribute()
    {
        if ($this->type == 1){
            return url('/') . '/Card.png';
        }elseif ($this->type == 2){
            return url('/') . '/Card-2.png';
        }elseif ($this->type == 3){
            return url('/') . '/Card-3.png';
        }
    }

    public function getDateAttribute()
    {
        return Carbon::parse($this->created_at)->format('d/m/Y');
    }
}
