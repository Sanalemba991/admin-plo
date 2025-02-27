<?php

namespace App\Models\Bidvalue;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bid extends Model
{
    use HasFactory;

    protected $fillable = [
        "entry_fee",
          "first_prize",
            "second_prize",
            "game_type",
        ];
}
