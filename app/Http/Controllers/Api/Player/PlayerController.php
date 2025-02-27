<?php

namespace App\Http\Controllers\Api\Player;

use App\Http\Controllers\Controller;
use App\Models\Player\Userdata;
use App\Models\Player\ReferHistory;
use App\Models\Player\Gamehistory;
use App\Models\Transaction\Transaction;
use App\Models\Withdraw\Withdraw;
use App\Models\Bidvalue\Bid;
use App\Models\Bidvalue\Match_cricket;
use App\Models\Bidvalue\Leagues;
use App\Models\Bidvalue\League_prize_pools;
use App\Models\Bidvalue\LeagueRankUsers;
use App\Models\Bidvalue\League_rank_prizes;
use Illuminate\Http\File;
use App\Models\WebSetting\Websetting;
use App\Models\Shopcoin\Shopcoin;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
class PlayerController extends Controller
{


   public function CreatePlayer(Request $request)
{
    $gameConfig = Websetting::first();
    $randomNumber = random_int(100000, 999999);
    $playerid = "LUDO" . random_int(100000, 999999);

        $random=rand(1,29);
       $pic_url="https://ludowalagames.com/Avatar/".$random.".png";
    $checkGooglePrevAccount = Userdata::where('useremail', $request->email)->first();
    if ($checkGooglePrevAccount != "") {
        // Check if user exists
        $response = ['notice' => 'User Already Exists !', 'playerid' => $checkGooglePrevAccount['playerid']];
        return response($response, 200);
    } else {
        // Insert new user
        $insert = Userdata::insert([
            'playerid' => $playerid,
            "username" => $request->first_name,
            "password" => Hash::make($request->password),
            "useremail" => $request->email,
            "userphone" => $request->phone,
            "refer_code" => $randomNumber,
            "totalcoin" => $gameConfig->signup_bonus,
            "wincoin" => "0",
            "refrelCoin" => "0",
            "registerDate" => date("l jS F Y h:i:s A"),
            "status" => 1,
            "banned" => 1,
            "created_at" => Carbon::now(),
            "updated_at" => Carbon::now(),
        ]);

        if ($insert) {
            $data = Userdata::where('useremail', $request->email)->first();
            $response = ['notice' => 'User Successfully Created !', 'playerid' => $data['playerid']];
            return response($response, 200);
        } else {
            return response(array("notice" => "Opps Something Is Wrong !"), 200)->header("Content-Type", "application/json");
        }
    }
}



    public function PlayerDeatils(Request $request)
    {
        $PlayerCoin = Userdata::where('playerid', $request->playerid)->first();
        $UpdateCoin = $PlayerCoin['totalcoin'] + $PlayerCoin['wincoin']+ $PlayerCoin['refrelCoin'];
        $UpdateData = Userdata::where('playerid', $request->playerid)->update([
            "playcoin" => $UpdateCoin,
        ]);
      
        if ($UpdateData) {
            $userdata = DB::table(DB::raw('
    (SELECT RANK() OVER (ORDER BY `game_win_amount` DESC) AS rank, userdatas.* FROM `userdatas`) AS ranked_users
'))
    ->where('playerid', $request->playerid)
    ->first();
    
          
        } else {
            $response = ["message" => 'Something Is Wrong'];
            return response($response, 200);
        }

        $bid = Bid::get();
        $shopcoin = Shopcoin::get();
        $gameConfig = Websetting::first();

        $response = ["message" => 'All Details Fetched Successfully', 'playerdata' => $userdata, 'bidvalues' => $bid, 'gameconfig' => $gameConfig, 'shop_coin' => $shopcoin];
        return response($response, 200);
    }
    public function PlayerUpdate(Request $request)
    {
      
        $UpdateData = Userdata::where('playerid', $request->playerid)->update([
            "ip" => $request->ip,
             "location" => $request->location,
        ]);
      
        if ($UpdateData) {
           
      $response = ["message" => 'All Details Fetched Successfully'];
        return response($response, 200);
          
        } else {
            $response = ["message" => 'Something Is Wrong'];
            return response($response, 200);
        }

        

      
    }

    public function PlayerProfileIMGUpdate(Request $request)
    {

        if ($request->profile_img) {
            $fileName = $request->file("profile_img");
            $path = $fileName->getClientOriginalName();
            $imagePath = $fileName->storeAs("public/Profile", $path, "local");
            $imagePath = "https://ludowalagames.com/storage/Profile/".str_replace("public/Profile", "", $imagePath);
            $data["profile_img"] = $imagePath;

            $response = Userdata::where('playerid', $request->playerid)->update(array(
                "photo" => $imagePath,
            ));

            if ($response) {
                $response = ['notice' => 'Image Updated'];
                return response($response, 200);
            } else {
                $response = ['notice' => 'Image Not Updated'];
                return response($response, 200);
            }
        } else {
            $response = ['notice' => 'Image Not Received'];
            return response($response, 200);
        }
    }
      public function PANIMGUpdate(Request $request)
    {

        if ($request->pan_image) {
            $fileName = $request->file("pan_image");
            $path = $fileName->getClientOriginalName();
            $imagePath = $fileName->storeAs("public/Profile", $path, "local");
            $imagePath = "https://ludowalagames.com/storage/Profile/".str_replace("public/Profile", "", $imagePath);
            $data["pan_image"] = $imagePath;

            $response = Userdata::where('playerid', $request->playerid)->update(array(
                "pan_url" => $imagePath,"pan_number" => $request->pan_number,"kyc_status" => 1,
            ));

            if ($response) {
                $response = ['notice' => 'Image Updated'];
                return response($response, 200);
            } else {
                $response = ['notice' => 'Image Not Updated'];
                return response($response, 200);
            }
        } else {
            $response = ['notice' => 'Image Not Received'];
            return response($response, 200);
        }
    }
     public function AADHAARIMGUpdate(Request $request)
    {

        if ($request->aadhaar_image) {
            $fileName = $request->file("aadhaar_image");
            $path = $fileName->getClientOriginalName();
            $imagePath = $fileName->storeAs("public/Profile", $path, "local");
            $imagePath = "https://ludowalagames.com/storage/Profile/".str_replace("public/Profile", "", $imagePath);
            $data["aadhaar_image"] = $imagePath;

            $response = Userdata::where('playerid', $request->playerid)->update(array(
                "aadhaar_url" => $imagePath,"kyc_status" => 1,
            ));

            if ($response) {
                $response = ['notice' => 'Image Updated'];
                return response($response, 200);
            } else {
                $response = ['notice' => 'Image Not Updated'];
                return response($response, 200);
            }
        } else {
            $response = ['notice' => 'Image Not Received'];
            return response($response, 200);
        }
    }


    //now check mobile regisyter user

    public function MobileCheck(Request $request)
    {
        $CheckPhone = Userdata::where('userphone', $request->mobilenumber)->first();
        if($CheckPhone!=""){
            $response = ['message' => 'User Already Exist !','playerid' => $CheckPhone['playerid'],'playername' => $CheckPhone['username']];
                return response($response, 200);
            
        }else{
            $response = ['message' => 'User Not Exist !'];
                return response($response, 200);
        }
                
          
    }
public function MobileRegister(Request $request)
{
        $random=rand(1,29);
       $pic_url="https://ludowalagames.com/Avatar/".$random.".png";
    $gameConfig = Websetting::first();
    $randomNumber = random_int(100000, 999999);
    $playerid = "LUDO" . random_int(1000000, 9999999);

    if ($request->refer_code != "") {
        $ReferCode = Userdata::where('refer_code', $request->refer_code)->first();
        if ($ReferCode != "") {
            $refercoin = $ReferCode["refrelCoin"] + $gameConfig["refer_bonus"];
            $updatereferuser = Userdata::where('refer_code', $request->refer_code)->update(array(
                "refrelCoin" => $refercoin,
            ));
          
 $insert = ReferHistory::insert([
                    'main_user_id' =>  $ReferCode["playerid"],
                    "referred_user_id" =>  $playerid,
                    "amount" =>$gameConfig["refer_bonus"],
                  
                ]);

            if ($updatereferuser) {
                $insert = Userdata::insert([
                    'playerid' => $playerid,
                    "username" => $request->playername,
                    "password" => Hash::make($request->password),
                    "userphone" => $request->mobilenumber,
                    "useremail" => $request->email,
                    "photo" => $pic_url,
                    "refer_code" => $randomNumber,
                    "used_refer_code" =>  $request->refer_code,
                    "totalcoin" => "0",
                    "wincoin" => "0",
                    "refrelCoin" => $gameConfig->signup_bonus,
                    "registerDate" => date("l jS F Y h:i:s A"),
                    "status" => 1,
                    "banned" => 1,
                ]);

                if ($insert) {
                    $response = ['message' => 'User Created Successfully !', 'playerid' => $playerid];
                    return response($response, 200);
                } else {
                    $response = ['message' => 'Something is wrong'];
                    return response($response, 200);
                }
            } else {
                $response = ['message' => 'Something is wrong'];
                return response($response, 200);
            }
        } else {
            $response = ['message' => 'Invalid Refer Code'];
            return response($response, 200);
        }
    } else {
        $insert = Userdata::insert([
            'playerid' => $playerid,
            "username" => $request->playername,
            "password" => Hash::make($request->password),
            "userphone" => $request->mobilenumber,
            "useremail" => $request->email,
              "photo" => $pic_url,
            "refer_code" => $randomNumber,
            "totalcoin" => $gameConfig->signup_bonus,
            "wincoin" => "0",
            "refrelCoin" => "0",
            "registerDate" => date("l jS F Y h:i:s A"),
            "status" => 1,
            "banned" => 1,
        ]);

        if ($insert) {
            $response = ['message' => 'User Created Successfully !', 'playerid' => $playerid];
            return response($response, 200);
        } else {
            $response = ['message' => 'Something is wrong'];
            return response($response, 200);
        }
    }
}

public function ReferralHistory(Request $request){
   $referralHistory = $results = DB::table('referral_history')
    ->where('main_user_id',$request->playerid)
    ->get();
    $response = ["message" => 'All Details Fetched Successfully', 'referraldata' => $referralHistory];
        return response($response, 200);
    
}
public function GameHistory(Request $request){
 $gameHistories = Gamehistory::where('playerid', $request->playerid)
    ->orderBy('id', 'DESC')
    ->get();
    $response = ["message" => 'All Details Fetched Successfully', 'gamedata' => $gameHistories];
        return response($response, 200);
    
}
public function TransactionHistory(Request $request){
 $transactions = Transaction::where('userid', $request->playerid) // Specify the user ID condition here
    ->orderByDesc('id')
    ->get();
    $response = ["message" => 'All Details Fetched Successfully', 'gamedata' => $transactions];
        return response($response, 200);
    
}
public function WithdrawHistory(Request $request){
 $withdraws = Withdraw::where('userid', $request->playerid) // Specify the user ID condition here
    ->orderByDesc('id')
    ->get();
    $response = ["message" => 'All Details Fetched Successfully', 'gamedata' => $withdraws];
        return response($response, 200);
    
}
public function GetLudoContestList(Request $request){
    if($request->type==1){
         $bid = Bid::where('game_type', 1)->get(); 
    }elseif($request->type==3){
          $bid = Bid::where('game_type', 3)->get(); 
    }else{
       $bid = Bid::get(); 
    }
   
    $response = ["message" => 'All Details Fetched Successfully',  'ludobidlist' => $bid];
        return response($response, 200);
    
}
public function GetTournamentList(Request $request) {
    
    $status = $request->status;  
    $playerId = $request->playerid ?? 0; // Use 0 as default if $playerId is not provided
  
    $tournaments = DB::table('tournaments')
    ->select('tournaments.*')
    ->selectSub(function ($query) use ($playerId) {
        $query->from('tournament_users')
              ->whereColumn('tournament_users.tournament_id', 'tournaments.id')
              ->where('tournament_users.playerid', $playerId)
              ->selectRaw('COUNT(*)');
    }, 'is_joined')
    ->selectSub(function ($query) use ($playerId) {
        $query->from('tournament_users')
              ->whereColumn('tournament_users.tournament_id', 'tournaments.id')
              ->where('tournament_users.playerid', $playerId)
              ->select('rounds_played')
              ->limit(1);
    }, 'rounds_played')
     ->selectSub(function ($query) use ($playerId) {
        $query->from('tournament_users')
              ->whereColumn('tournament_users.tournament_id', 'tournaments.id')
              ->where('tournament_users.playerid', $playerId)
              ->select('win_count')
              ->limit(1);
    }, 'win_count')
    ->where('tournaments.status', $status)
    ->get();

   
    $response = ["message" => 'All Details Fetched Successfully', 'tournamentlist' => $tournaments];
    return response($response, 200);
}

public function GetLeagueList(Request $request) {
    
    $status = $request->status;  
    $game = $request->game;  
    $playerId = $request->playerid ?? 0; // Use 0 as default if $playerId is not provided
  
 

$leagues = DB::table('leagues')
    ->select(
        'leagues.*',
        DB::raw('COALESCE(SUM(league_rank_prizes.prize), 0) as prizepool'),
        DB::raw('COALESCE(COUNT(league_rank_prizes.id), 0) as totalrank'),
        DB::raw('(SELECT MAX(league_rank_prizes.prize) 
                  FROM league_rank_prizes 
                  WHERE league_rank_prizes.rank = 1 
                  AND league_rank_prizes.league_id = leagues.league_id) as firstprize'),
        DB::raw('(SELECT COUNT(*) 
                  FROM league_rank_users 
                  WHERE league_rank_users.league_id = leagues.league_id 
                  AND league_rank_users.player_id = "' . $playerId . '") as is_joined'),
        DB::raw('(SELECT chances_used 
                  FROM league_rank_users 
                  WHERE league_rank_users.league_id = leagues.league_id 
                  AND league_rank_users.player_id = "' . $playerId . '") as chances_used')
    )
    ->leftJoin('league_rank_prizes', 'league_rank_prizes.league_id', '=', 'leagues.league_id')
    ->where('leagues.game', $game)
    ->where('leagues.status', $status)
    ->groupBy('leagues.league_id')
    ->orderBy('leagues.league_id', 'desc')
    ->get();

   
    $response = ["message" => 'All Details Fetched Successfully', 'leaguelist' => $leagues];
    return response($response, 200);
}
public function GetLeagueDetails(Request $request) {
    
    $status = $request->status;  
    $league_id = $request->matchid;  
    
    $playerId = $request->playerid ?? 0; // Use 0 as default if $playerId is not provided
  
    $leagues = Leagues::leftJoin('league_rank_prizes', 'league_rank_prizes.league_id', '=', 'leagues.league_id')
    ->select('leagues.*', 
             DB::raw('COALESCE(SUM(league_rank_prizes.prize), 0) as prizepool'), 
             DB::raw('COALESCE(COUNT(*), 0) as totalrank'), 
             DB::raw('(SELECT league_rank_prizes.prize FROM league_rank_prizes WHERE league_rank_prizes.rank = 1 AND league_rank_prizes.league_id = leagues.league_id) as firstprize'), 
             DB::raw('(SELECT COUNT(*) FROM league_rank_users WHERE league_rank_users.league_id = leagues.league_id AND league_rank_users.player_id = "'.$playerId.'") as is_joined'))
     ->where('leagues.league_id', $league_id)
    ->get();
   
    $response = ["message" => 'All Details Fetched Successfully', 'leaguedetails' => $leagues];
    return response($response, 200);
}
public function GetLeaguePool(Request $request) {
    
    
   $league_id = $request->matchid;  
    
    $playerId = $request->playerid ?? 0; // Use 0 as default if $playerId is not provided
  
   $leagues = League_prize_pools::where('league_id', $league_id)
    ->orderBy('league_id', 'ASC')
    ->get();
   
    $response = ["message" => 'All Details Fetched Successfully', 'leaguepool' => $leagues];
    return response($response, 200);
}
public function GetLeagueUsers(Request $request) {
    
    
   $league_id = $request->matchid;  
    
    $playerId = $request->playerid ?? 0; // Use 0 as default if $playerId is not provided
  
   $leagues = LeagueRankUsers::where('league_id', $league_id)
    ->orderBy('points', 'DESC')
    ->get();
   
    $response = ["message" => 'All Details Fetched Successfully', 'leagueleaderboard' => $leagues];
    return response($response, 200);
}


public function GetCricketContestList(Request $request){
    
       $bid = Match_cricket::get(); 

   
    $response = ["message" => 'All Details Fetched Successfully',  'cricketbidlist' => $bid];
        return response($response, 200);
    
}
public function GetSlidingBanner(Request $request){
    
       $bid = DB::table('sliding_banners')->get();

   
    $response = ["message" => 'All Details Fetched Successfully',  'banners' => $bid];
        return response($response, 200);
    
}
public function GetBots(Request $request) {
    // Fetch all bot users
    $bots = Userdata::where('is_bot', 1)->get();

    // Check if the count is more than 10
    if ($bots->count() > 10) {
        // Shuffle and get a random 10 bots
        $bots = $bots->random(10);
    }
    
    // Prepare the response
    $response = ["message" => 'All Details Fetched Successfully', 'bots' => $bots];
    return response($response, 200);
}

public function UpdateBankDetails(Request $request){
    
    $sql=DB::table('userdatas')
    ->where('playerid', $request->playerid)
    ->update([
        'accountHolder' => $request->holder,
        'accountNumber' => $request->ac_no,
        'ifsc' => $request->ifsc,
        'bankname' => $request->bankname
    ]);
     $response = ["message" => 'Bank Details Added Sucess'];
    return response($response, 200);
    
}
public function UpdateProfile(Request $request){
    // Check if the request has an image file
    if($request->hasFile('image')) {
        $image = $request->file('image');
        
        // Generate a unique name for the image
        $imageName = time() . '_' . $image->getClientOriginalName();

        // Save the image in the public/Profile directory
        $imagePath = $image->storeAs('public/Profile', $imageName);

        // Generate the full image URL
        $imageUrl = "https://ludowalagames.com/storage/Profile/" . str_replace("public/Profile", "", $imagePath);
    }

    // Update the user's profile in the database
    $sql = DB::table('userdatas')
        ->where('playerid', $request->playerid)
        ->update([
            'username' => $request->username,
            'useremail' => $request->useremail,
            'userphone' => $request->userphone,
            'device_token' => $request->pan,
            'profile_image' => isset($imageUrl) ? $imageUrl : null, // Update the profile image if provided
        ]);

    $response = ["message" => 'Profile Details Updated Successfully'];
    return response($response, 200);
}

public function CreateChallenge(Request $request){
    
    
    $sql=DB::table('c_contest')->insert([
    'room_id' => $request->roomid,
    'game_mode' => $request->game_mode,
    'room_owner_id' => $request->playerid,
    'staus' => '0',
    'entry_fee' => $request->bidamount,
    'win_amount' => $request->win_amount,
    'created_at' => now(), // or you can set a specific date and time
]);
     $response = ["message" => 'Success'];
    return response($response, 200);
    
}
public function JoinChallenge(Request $request){
    // Update the contest table
    DB::table('c_contest')
        ->where('id', $request->id)
        ->update(['opoonent_id' => $request->playerid, 'staus' => 1, 'room_id' => $request->roomid]);
$roomOwnerId = DB::table('c_contest')
                 ->where('id', $request->id)
                 ->value('room_owner_id');

    // Get the user phone number
    $userphone = DB::table('userdatas')
                   ->where('playerid', $roomOwnerId)
                   ->value('userphone'); 
                   // Use 'value' instead of 'pluck' to get a single value
                   
                     $opponentid = DB::table('userdatas')
                   ->where('playerid', $request->playerid)
                   ->value('username'); 

    // Check if userphone is retrieved
    if ($userphone) {
        // Initialize cURL
        $curl = curl_init();

        curl_setopt_array($curl, array(
          CURLOPT_URL => 'https://wpsender.nexgino.com/api/create-message',
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => '',
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 0,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => 'POST',
          CURLOPT_POSTFIELDS => array(
              'appkey' => 'c18f58d6-4732-4860-b62a-b4cff1bfc138',
              'authkey' => 'enU1ohpiYouXoXWc7xN1s4MANJBtsuGM5B6I7XxsLLIF4sgH4g',
              'to' => '+91'.$userphone,
              'message' => $opponentid.' Accepted your Challenge, Please "LudoWala" App and Join Now.!',
              'sandbox' => 'false'
          ),
        ));

        // Execute cURL and capture the response
        $response2 = curl_exec($curl);

        // Check for cURL errors
        if ($response2 === false) {
            // Handle the error
            $response = ["message" => 'Failed to send message: ' . curl_error($curl)];
            return response($response, 500);
        }

        // Close the cURL session
        curl_close($curl);

        // Return success response
        $response = ["message" => 'Success'];
        return response($response, 200);
    } else {
        // If the phone number is not found, return an error response
        $response = ["message" => 'Phone number not found for the given player ID'];
        return response($response, 404);
    }
}

public function EndChallenge(Request $request){
    
    
    $sql=DB::table('c_contest')
    ->where('id', $request->id)
    ->update(['owner_score' => $request->owner_score, 'staus' => 3,'opponent_score'=>$request->opponent_score,'winner_id'=>$request->winner_id]);

     $response = ["message" => 'Success'];
    return response($response, 200);
    
}
public function GetChallanges(Request $request){
    
    
    $sql = DB::table('c_contest')
          ->where('staus', '!=', 3)
          ->get();

       $response = ["message" => 'All Details Fetched Successfully', 'contest' => $sql];
    return response($response, 200);
    
}
public function GetTournamentUser(Request $request) {
    
    $users = DB::table('tournament_users')
    ->where('tournament_id', $request->league_id)
    ->get();
   
    $response = ["message" => 'All Details Fetched Successfully', 'playerlist' => $users];
    return response($response, 200);
}   
public function GetTournamentBots(Request $request) {
    
    $users = DB::table('tournament_users')
    ->where('tournament_id', $request->league_id)
    ->where('is_bot', 1)
    ->get();
   
    $response = ["message" => 'All Details Fetched Successfully', 'playerlist' => $users];
    return response($response, 200);
}  
public function UpdateTournamentScore(Request $request) {
    $playerId=$request->playerid;
    $tournamentId=$request->tid;
    if($request->won==1){
        $update=DB::table('tournament_users')
    ->where('playerid', $playerId)
    ->where('tournament_id', $tournamentId)
    ->update([
        'win_count' => DB::raw('win_count + 1'),
        'rounds_played' => DB::raw('rounds_played + 1'),
        'score' => $request->score,
    ]);
    }else{
        $update=DB::table('tournament_users')
    ->where('playerid', $playerId)
    ->where('tournament_id', $tournamentId)
    ->update([
        'win_count' => DB::raw('win_count + 0'),
        'rounds_played' => DB::raw('rounds_played + 1'),
        'score' => $request->score,
    ]);
    }
   
    $response = ["message" => 'All Details Fetched Successfully', 'playerlist' => $users];
    return response($response, 200);
}  
}
