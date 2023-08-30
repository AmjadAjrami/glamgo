<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class HomeServiceTranslation extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'home_service_translations';
    protected $guarded = [];
}
