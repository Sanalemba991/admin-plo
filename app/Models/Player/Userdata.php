<?php

namespace App\Models\Player;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Userdata extends Model
{
    use HasFactory;

    protected $fillable = [
           "userid",
           "playerid",
           "username",
           "userphone",
           "password",
           "OTPCode",
           "useremail",
           "photo",
           "refer_code",
           "used_refer_code",
           "totalgem",
           "totalcoin",
           "playcoin",
           "wincoin",
           "device_token",
           "registerDate",
           "refrelCoin",
           "GamePlayed",
           "HandGamePlayed",
           "hg_win",
           "twoPlayWin",
           "FourPlayWin",
           "twoPlayloss",
           "FourPlayloss",
           "status",
           "banned",
           "accountHolder",
           "accountNumber",
           "ifsc",
           "is_bot",
           "aadhaar_url",
           "pan_number",
           "pan_url",
           "kyc_status",
    ];
}
