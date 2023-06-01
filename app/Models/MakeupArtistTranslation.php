<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MakeupArtistTranslation extends Model
{
    use HasFactory;

    protected $table = 'makeup_artist_translations';
    protected $guarded = [];
}
