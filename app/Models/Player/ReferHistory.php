<?php

namespace App\Models\Player;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReferHistory extends Model
{
    use HasFactory;
    protected $table = "referral_history";

    protected $fillable = [
          "id",
          "main_user_id	",
          "referred_user_id",
          "amount",
         
    ];
}
