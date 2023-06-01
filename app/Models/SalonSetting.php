<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SalonSetting extends Model
{
    use HasFactory;

    protected $table = 'salon_settings';
    protected $guarded = [];
}
