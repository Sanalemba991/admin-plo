<?php

namespace App\Models\Bidvalue;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Match_cricket extends Model
{
    use HasFactory;

    protected $fillable = [
        "game_mode",
        "entry_fee",
        "prize_pool",
          "pos1_prize",
            "pos2_prize",
            "pos3_prize",
            "match_status",
            "match_time",
            "total_no_of_players",
            "total_joined_players",
        ];
}
