<?php

namespace App\Models\Player;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SlidingBanner extends Model
{
    use HasFactory;

    protected $fillable = [
           "url",
           "target_url"
    ];
}
?>