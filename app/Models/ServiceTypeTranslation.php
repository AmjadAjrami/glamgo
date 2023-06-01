<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServiceTypeTranslation extends Model
{
    use HasFactory;

    protected $table = 'service_type_translations';
    protected $guarded = [];
}
