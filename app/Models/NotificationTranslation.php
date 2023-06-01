<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NotificationTranslation extends Model
{
    use HasFactory;

    protected $table = 'notification_translations';
    protected $guarded = [];
}
