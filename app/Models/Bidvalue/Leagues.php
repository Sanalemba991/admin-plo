<?php

namespace App\Models\Bidvalue;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Leagues extends Model
{
    use HasFactory;

    protected $fillable = [
        "game",
          "entry_fee",
            "total_spots",
            "joined",
             "start_time",
              "end_time",
               "result_time",
                "total_chances",
        ];
}
