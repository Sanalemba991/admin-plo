<?php

namespace App\Models\Bidvalue;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class League_prize_pools extends Model
{
    use HasFactory;

    protected $fillable = [
        "league_id",
        "rank_from",
        "rank_to",
          "prize_amount",
          
        ];
}
