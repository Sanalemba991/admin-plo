<?php

namespace App\Models\Bidvalue;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class League_rank_prizes extends Model
{
    use HasFactory;

    protected $fillable = [
        "league_id",
        "rank",
        "prize",
         
        ];
}
