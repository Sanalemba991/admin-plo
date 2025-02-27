<?php

namespace App\Models\Bidvalue;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LeagueRankUsers extends Model
{
    use HasFactory;

    protected $fillable = [
        "league_id",
        "player_name",
        "player_id",
        "is_bot",
        "pic_url",
        "points",
        "winning",
        "chances_used",
         
        ];
}
