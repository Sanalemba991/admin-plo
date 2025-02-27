<?php

namespace App\Http\Controllers\Api\Player;

use App\Http\Controllers\Controller;
use App\Models\Player\Userdata;
use App\Models\WebSetting\Websetting;
use App\Models\Friends\Friend;
use App\Models\Withdraw\Withdraw;
use App\Models\Transaction\Transaction;
use App\Models\Gamehistory\Gamehistorie;
use Illuminate\Http\Request;
use App\Models\Bidvalue\Leagues;
use App\Models\Bidvalue\League_prize_pools;
use App\Models\Bidvalue\LeagueRankUsers;
use App\Models\Bidvalue\League_rank_prizes;
use Illuminate\Support\Facades\DB;


class GameManagerController extends Controller
{
    
    //cut coin
  public function JoinGame(Request $request)
    {
        $PlayerData = Userdata::where('playerid', $request->playerid)->first();
           $GamePlayed = $PlayerData['GamePlayed'] + 1;
            $gameplayedamount=$PlayerData['game_played_amount'] +$request->bidamount;
            if( $PlayerData['used_refer_code']!=null){
             $ReferData = Userdata::where('refer_code', $PlayerData['used_refer_code'])->first();
             $finalTotalAmount = $ReferData['refrelCoin'] + (($request->bidamount/100)*5);
             $UpdateCoin = Userdata::where('playerid', $ReferData['playerid'])->update(array(
            'refrelCoin' => $finalTotalAmount));
             $insert = ReferHistory::insert([
                    'main_user_id' =>  $ReferData['playerid'],
                    "referred_user_id" =>  $request->playerid,
                    "amount" =>$gameConfig["refer_bonus"],
                  
                ]);
            }
        if($PlayerData['totalcoin']>=$request->bidamount){
            $finalTotalAmount = $PlayerData['totalcoin'] - $request->bidamount;
             $UpdateCoin = Userdata::where('playerid', $request->playerid)->update(array(
            'totalcoin' => $finalTotalAmount,'GamePlayed' => $GamePlayed,'game_played_amount'=>$gameplayedamount,
        ));

        if ($UpdateCoin) {
              $userdata = Userdata::where('playerid', $request->playerid)->first();
           $finalAmount = $userdata['totalcoin']+$userdata['wincoin']+$userdata['refrelCoin'];

        $insert = Gamehistorie::insert([
            "playerid" => $request->playerid,
            "status" => $request->status,
               "game_name" => $request->gamename,
            "bid_amount" => $request->bidamount,
            
            "playername" => $userdata['username'],
            "finalamount" => $finalAmount,
              "roomcode" => $request->roomcode,
            
            "playtime" =>  date("l jS F Y h:i:s A"),
        ]);
            $response = ['notice' => 'Coin Updated', 'totalcoin' => $finalTotalAmount];
            return response($response, 200);
        } else {
            $response = ['notice' => 'Coin Not Updated', 'totalcoin' => $finalTotalAmount];
            return response($response, 200);
        }

        }else if($PlayerData['wincoin']>=$request->bidamount){

              $finalWinAmount = $PlayerData['wincoin'] - $request->bidamount;
               $UpdateCoin = Userdata::where('playerid', $request->playerid)->update(array(
               'wincoin' => $finalWinAmount,'GamePlayed' => $GamePlayed,'game_played_amount'=>$gameplayedamount,
        ));

        if ($UpdateCoin) {
              $userdata = Userdata::where('playerid', $request->playerid)->first();
           $finalAmount = $userdata['totalcoin']+$userdata['wincoin']+$userdata['refrelCoin'];

        $insert = Gamehistorie::insert([
            "playerid" => $request->playerid,
            "status" => $request->status,
               "game_name" => $request->gamename,
            "bid_amount" => $request->bidamount,
            
            "playername" => $userdata['username'],
            "finalamount" => $finalAmount,
            
            "playtime" =>  date("l jS F Y h:i:s A"),
        ]);
            $response = ['notice' => 'Coin Updated', 'totalcoin' => $finalWinAmount];
            return response($response, 200);
        } else {
            $response = ['notice' => 'Coin Not Updated', 'totalcoin' => $finalWinAmount];
            return response($response, 200);
        }

        }else{
            $finalplayTotalAmount = $request->bidamount-$PlayerData['totalcoin'];
            $finalWinAmount = $PlayerData['wincoin'] - $finalplayTotalAmount;
           
            $UpdateCoin = Userdata::where('playerid', $request->playerid)->update(array(
                'wincoin' => $finalWinAmount,
                'totalcoin'=> "0",
                'GamePlayed' => $GamePlayed,'game_played_amount'=>$gameplayedamount,
           ));

           if ($UpdateCoin) {
                 $userdata = Userdata::where('playerid', $request->playerid)->first();
           $finalAmount = $userdata['totalcoin']+$userdata['wincoin']+$userdata['refrelCoin'];

        $insert = Gamehistorie::insert([
            "playerid" => $request->playerid,
            "status" => $request->status,
               "game_name" => $request->gamename,
            "bid_amount" => $request->bidamount,
            
            "playername" => $userdata['username'],
            "finalamount" => $finalAmount,
            
            "playtime" =>  date("l jS F Y h:i:s A"),
        ]);
            $response = ['notice' => 'Coin Updated', 'totalcoin' => $finalWinAmount];
            return response($response, 200);
          } else {
            $response = ['notice' => 'Coin Not Updated', 'totalcoin' => $finalWinAmount];
            return response($response, 200);
         }

        }

    }



 
     public function JoinLeague(Request $request)
    {
        $PlayerData = Userdata::where('playerid', $request->playerid)->first();
        $GamePlayed = $PlayerData['GamePlayed'] + 1;
        $UserdReferCode=$PlayerData['used_refer_code'];
        if($UserdReferCode!=null ||$UserdReferCode!="" ){
        $ReferData=Userdata::where('refer_code', $UserdReferCode)->first();
        $Comission=round(($request->bidamount)/10);
        $newrefcoin=$ReferData['refrelCoin']+$Comission;
        $gameplayedamount=$request->bidamount;
         $UpdateComission = Userdata::where('playerid', $ReferData['playerid'])->update(array(
            'refrelCoin' => $newrefcoin
        ));
        }
        if($PlayerData['totalcoin']>=$request->bidamount){
            $finalTotalAmount = $PlayerData['totalcoin'] - $request->bidamount;
             $UpdateCoin = Userdata::where('playerid', $request->playerid)->update(array(
            'totalcoin' => $finalTotalAmount,'GamePlayed' => $GamePlayed,'game_played_amount'=>$request->bidamount,
        ));

        if ($UpdateCoin) {
           $userdata = Userdata::where('playerid', $request->playerid)->first();
           $finalAmount = $userdata['totalcoin']+$userdata['wincoin']+$userdata['refrelCoin'];

        $insert = Gamehistorie::insert([
            "playerid" => $request->playerid,
            "status" => 'Join a Tournament',
             "game_name" => 'Ludo',
            "bid_amount" => $request->bidamount,
            
            "playername" => $userdata['username'],
            "finalamount" => $finalAmount,
            
            "playtime" =>  date("l jS F Y h:i:s A"),
        ]);
         $insert2 = LeagueRankUsers::insert([
            "league_id" => $request->league_id,
            "player_name" => $userdata['username'],
            "player_id" => $request->playerid,
            
        ]);
       $update2 = Leagues::where('league_id', $request->league_id)
    ->update([
        'joined' => \DB::raw('joined + 1')
    ]);
    
            $response = ['notice' => 'Coin Updated', 'totalcoin' => $finalTotalAmount];
            return response($response, 200);
        } else {
            $response = ['notice' => 'Coin Not Updated', 'totalcoin' => $finalTotalAmount];
            return response($response, 200);
        }

        }else if($PlayerData['wincoin']>=$request->bidamount){

              $finalWinAmount = $PlayerData['wincoin'] - $request->bidamount;
               $UpdateCoin = Userdata::where('playerid', $request->playerid)->update(array(
               'wincoin' => $finalWinAmount,'GamePlayed' => $GamePlayed,'game_played_amount'=>$request->bidamount,
        ));

        if ($UpdateCoin) {
            $userdata = Userdata::where('playerid', $request->playerid)->first();
           $finalAmount = $userdata['totalcoin']+$userdata['wincoin']+$userdata['refrelCoin'];

        $insert = Gamehistorie::insert([
            "playerid" => $request->playerid,
             "status" => 'Join a Tournament',
             "game_name" => 'Ludo',
            "bid_amount" => $request->bidamount,
            
            "playername" => $userdata['username'],
            "finalamount" => $finalAmount,
            
            "playtime" =>  date("l jS F Y h:i:s A"),
        ]);
         $insert2 = LeagueRankUsers::insert([
            "league_id" => $request->league_id,
            "player_name" => $userdata['username'],
            "player_id" => $request->playerid,
            
        ]);
        $newjoined=$request->joined +1 ;
       $update2 = Leagues::where('league_id', $request->league_id)
    ->update([
        'joined' => \DB::raw('joined + 1')
    ]);
            $response = ['notice' => 'Coin Updated', 'totalcoin' => $finalWinAmount];
            return response($response, 200);
        } else {
            $response = ['notice' => 'Coin Not Updated', 'totalcoin' => $finalWinAmount];
            return response($response, 200);
        }

        }else if($PlayerData['refrelCoin']>=$request->bidamount){

              $finalWinAmount = $PlayerData['refrelCoin'] - $request->bidamount;
               $UpdateCoin = Userdata::where('playerid', $request->playerid)->update(array(
               'refrelCoin' => $finalWinAmount,'GamePlayed' => $GamePlayed,'game_played_amount'=>$request->bidamount,
        ));

        if ($UpdateCoin) {
            $userdata = Userdata::where('playerid', $request->playerid)->first();
           $finalAmount = $userdata['totalcoin']+$userdata['wincoin']+$userdata['refrelCoin'];

        $insert = Gamehistorie::insert([
            "playerid" => $request->playerid,
           "status" => 'Join a Tournament',
             "game_name" => 'Ludo',
            "bid_amount" => $request->bidamount,
            
            "playername" => $userdata['username'],
            "finalamount" => $finalAmount,
            
            "playtime" =>  date("l jS F Y h:i:s A"),
        ]);
         $insert2 = LeagueRankUsers::insert([
            "league_id" => $request->league_id,
            "player_name" => $userdata['username'],
            "player_id" => $request->playerid,
            
        ]);
        $update2 = Leagues::where('league_id', $request->league_id)
    ->update([
        'joined' => \DB::raw('joined + 1')
    ]);
            $response = ['notice' => 'Coin Updated', 'totalcoin' => $finalWinAmount];
            return response($response, 200);
        } else {
            $response = ['notice' => 'Coin Not Updated', 'totalcoin' => $finalWinAmount];
            return response($response, 200);
        }

        }else{
            $finalplayTotalAmount = $request->bidamount-$PlayerData['totalcoin'];
            if($PlayerData['wincoin']>=$finalplayTotalAmount){
            $finalWinAmount = $PlayerData['wincoin'] - $finalplayTotalAmount;
            $UpdateCoin = Userdata::where('playerid', $request->playerid)->update(array(
                'wincoin' => $finalWinAmount,'GamePlayed' => $GamePlayed,'game_played_amount'=>$request->bidamount,
                'totalcoin'=> "0",
           ));
            }else{
                $finalref=$request->bidamount-($PlayerData['totalcoin']+$PlayerData['wincoin']);
                $UpdateCoin = Userdata::where('playerid', $request->playerid)->update(array(
                'wincoin' => 0,'GamePlayed' => $GamePlayed,'game_played_amount'=>$request->bidamount,
                'totalcoin'=> "0",'refrelCoin'=> $PlayerData['totalcoin']-$finalref,
           ));
            }
            

           if ($UpdateCoin) {
               $userdata = Userdata::where('playerid', $request->playerid)->first();
           $finalAmount = $userdata['totalcoin']+$userdata['wincoin']+$userdata['refrelCoin'];

        $insert = Gamehistorie::insert([
            "playerid" => $request->playerid,
            "status" => 'Join a Tournament',
             "game_name" => 'Ludo',
            "bid_amount" => $request->bidamount,
            
            "playername" => $userdata['username'],
            "finalamount" => $finalAmount,
            
            "playtime" =>  date("l jS F Y h:i:s A"),
        ]);
         $insert2 = LeagueRankUsers::insert([
            "league_id" => $request->league_id,
            "player_name" => $userdata['username'],
            "player_id" => $request->playerid,
            
        ]);
       $update2 = Leagues::where('league_id', $request->league_id)
    ->update([
        'joined' => \DB::raw('joined + 1')
    ]);
            $response = ['notice' => 'Coin Updated', 'totalcoin' => $finalWinAmount];
            return response($response, 200);
          } else {
            $response = ['notice' => 'Coin Not Updated', 'totalcoin' => $finalWinAmount];
            return response($response, 200);
         }

        }

    }
  
 public function JoinTournament(Request $request)
    {
        $PlayerData = Userdata::where('playerid', $request->playerid)->first();
        $GamePlayed = $PlayerData['GamePlayed'] + 1;
        $UserdReferCode=$PlayerData['used_refer_code'];
        if($UserdReferCode!=null ||$UserdReferCode!="" ){
        $ReferData=Userdata::where('refer_code', $UserdReferCode)->first();
        $Comission=round(($request->bidamount)/10);
        $newrefcoin=$ReferData['refrelCoin']+$Comission;
        
         $UpdateComission = Userdata::where('playerid', $ReferData['playerid'])->update(array(
            'refrelCoin' => $newrefcoin
        ));
        }
        if($PlayerData['totalcoin']>=$request->bidamount){
            $finalTotalAmount = $PlayerData['totalcoin'] - $request->bidamount;
             $UpdateCoin = Userdata::where('playerid', $request->playerid)->update(array(
            'totalcoin' => $finalTotalAmount,'GamePlayed' => $GamePlayed,'game_played_amount'=>$gameplayedamount,
        ));

        if ($UpdateCoin) {
           $userdata = Userdata::where('playerid', $request->playerid)->first();
           $finalAmount = $userdata['totalcoin']+$userdata['wincoin']+$userdata['refrelCoin'];

        $insert = Gamehistorie::insert([
            "playerid" => $request->playerid,
            "status" => 'Join a Tournament',
             "game_name" => 'Ludo',
            "bid_amount" => $request->bidamount,
            
            "playername" => $userdata['username'],
            "finalamount" => $finalAmount,
            
            "playtime" =>  date("l jS F Y h:i:s A"),
        ]);
         $insert2 = DB::table('tournament_users')->insert([
            "tournament_id" => $request->league_id,
            "player_name" => $userdata['username'],
            "playerid" =>$userdata['playerid'],
             "pic_url" => $userdata['photo'],
              "is_bot" => $request->isbot,
            
        ]);
     
        $up=DB::table('tournaments')
    ->where('id', $request->league_id)
    ->increment('joined');
            $response = ['notice' => 'Coin Updated', 'totalcoin' => $finalTotalAmount];
            return response($response, 200);
        } else {
            $response = ['notice' => 'Coin Not Updated', 'totalcoin' => $finalTotalAmount];
            return response($response, 200);
        }

        }else if($PlayerData['wincoin']>=$request->bidamount){

              $finalWinAmount = $PlayerData['wincoin'] - $request->bidamount;
               $UpdateCoin = Userdata::where('playerid', $request->playerid)->update(array(
               'wincoin' => $finalWinAmount,'GamePlayed' => $GamePlayed,'game_played_amount'=>$gameplayedamount,
        ));

        if ($UpdateCoin) {
            $userdata = Userdata::where('playerid', $request->playerid)->first();
           $finalAmount = $userdata['totalcoin']+$userdata['wincoin']+$userdata['refrelCoin'];

        $insert = Gamehistorie::insert([
            "playerid" => $request->playerid,
            "status" => 'joined',
               "game_name" => 'HandCricket',
            "bid_amount" => $request->bidamount,
            
            "playername" => $userdata['username'],
            "finalamount" => $finalAmount,
            
            "playtime" =>  date("l jS F Y h:i:s A"),
        ]);
        $insert2 = DB::table('tournament_users')->insert([
            "tournament_id" => $request->league_id,
            "player_name" => $userdata['username'],
             "playerid" =>$userdata['playerid'],
             "pic_url" => $userdata['photo'],
              "is_bot" => $request->isbot,
            
        ]);
            $up=DB::table('tournaments')
    ->where('id', $request->league_id)
    ->increment('joined');
          $response = ['notice' => 'Coin Updated', 'totalcoin' => $finalWinAmount];
            return response($response, 200);
        } else {
            $response = ['notice' => 'Coin Not Updated', 'totalcoin' => $finalWinAmount];
            return response($response, 200);
        }

        }else if($PlayerData['refrelCoin']>=$request->bidamount){

              $finalWinAmount = $PlayerData['refrelCoin'] - $request->bidamount;
               $UpdateCoin = Userdata::where('playerid', $request->playerid)->update(array(
               'refrelCoin' => $finalWinAmount,'GamePlayed' => $GamePlayed,'game_played_amount'=>$gameplayedamount,
        ));

        if ($UpdateCoin) {
            $userdata = Userdata::where('playerid', $request->playerid)->first();
           $finalAmount = $userdata['totalcoin']+$userdata['wincoin']+$userdata['refrelCoin'];

        $insert = Gamehistorie::insert([
            "playerid" => $request->playerid,
            "status" => 'joined',
               "game_name" => 'HandCricket',
            "bid_amount" => $request->bidamount,
            
            "playername" => $userdata['username'],
            "finalamount" => $finalAmount,
            
            "playtime" =>  date("l jS F Y h:i:s A"),
        ]);
         $insert2 = DB::table('tournament_users')->insert([
            "tournament_id" => $request->league_id,
            "player_name" => $userdata['username'],
              "playerid" =>$userdata['playerid'],
             "pic_url" => $userdata['photo'],
              "is_bot" => $request->isbot,
            
        ]);
        $up=DB::table('tournaments')
    ->where('id', $request->league_id)
    ->increment('joined');
            $response = ['notice' => 'Coin Updated', 'totalcoin' => $finalWinAmount];
            return response($response, 200);
        } else {
            $response = ['notice' => 'Coin Not Updated', 'totalcoin' => $finalWinAmount];
            return response($response, 200);
        }

        }else{
            $finalplayTotalAmount = $request->bidamount-$PlayerData['totalcoin'];
            if($PlayerData['wincoin']>=$finalplayTotalAmount){
            $finalWinAmount = $PlayerData['wincoin'] - $finalplayTotalAmount;
            $UpdateCoin = Userdata::where('playerid', $request->playerid)->update(array(
                'wincoin' => $finalWinAmount,'GamePlayed' => $GamePlayed,'game_played_amount'=>$gameplayedamount,
                'totalcoin'=> "0",
           ));
            }else{
                $finalref=$request->bidamount-($PlayerData['totalcoin']+$PlayerData['wincoin']);
                $UpdateCoin = Userdata::where('playerid', $request->playerid)->update(array(
                'wincoin' => 0,'GamePlayed' => $GamePlayed,'game_played_amount'=>$gameplayedamount,
                'totalcoin'=> "0",'refrelCoin'=> $PlayerData['totalcoin']-$finalref,
           ));
            }
            

           if ($UpdateCoin) {
               $userdata = Userdata::where('playerid', $request->playerid)->first();
           $finalAmount = $userdata['totalcoin']+$userdata['wincoin']+$userdata['refrelCoin'];

        $insert = Gamehistorie::insert([
            "playerid" => $request->playerid,
            "status" => 'joined',
               "game_name" => 'Ludo Tournament',
            "bid_amount" => $request->bidamount,
            
            "playername" => $userdata['username'],
            "finalamount" => $finalAmount,
            
            "playtime" =>  date("l jS F Y h:i:s A"),
        ]);
        $insert2 = DB::table('tournament_users')->insert([
            "tournament_id" => $request->league_id,
            "player_name" => $userdata['username'],
               "playerid" =>$userdata['playerid'],
             "pic_url" => $userdata['photo'],
              "is_bot" => $request->isbot,
            
        ]);
            $up=DB::table('tournaments')
    ->where('id', $request->league_id)
    ->increment('joined');
            $response = ['notice' => 'Coin Updated', 'totalcoin' => $finalWinAmount];
            return response($response, 200);
          } else {
            $response = ['notice' => 'Coin Not Updated', 'totalcoin' => $finalWinAmount];
            return response($response, 200);
         }

        }

    }
    //game status

    public function GameStatus(Request $request)
    {
        if ($request->status == "win"){
          
            $PlayerData = Userdata::where('playerid', $request->playerid)->first();
            $finalAmount = $PlayerData['totalcoin'] ;
              $game_win_amount= $PlayerData['game_win_amount']+ $request->winamount+ $request->entrycoin;
            $winAmount = $PlayerData['wincoin'] + $request->winamount+ $request->entrycoin;

            $PlayerTotalCount = $finalAmount+$winAmount;

            if ($request->wintype == "twoplayerwin") {
                $wintype = $PlayerData['twoPlayWin'] + 1;
                $UpdateCoin = Userdata::where('playerid', $request->playerid)->update(array(
                    'wincoin' => $winAmount,
                    'totalcoin' => $finalAmount,
                    'playcoin' => $PlayerTotalCount,'game_win_amount'=>$game_win_amount,
                   
                    'twoPlayWin' => $wintype,
                ));

                if ($UpdateCoin) {
                    $response = ['notice' => 'User Win Status Update','totalcoin' => $PlayerTotalCount];
                    return response($response, 200);
                } else {
                    $response = ['notice' => 'Coin Not Updated','totalcoin' => $PlayerTotalCount];
                    return response($response, 200);
                }
            } else if ($request->wintype == "fourplayerwin"){
                $wintype = $PlayerData['FourPlayWin'] + 1;
                $UpdateCoin = Userdata::where('playerid', $request->playerid)->update(array(
                    'wincoin' => $winAmount,
                    'totalcoin' => $finalAmount,
                    
                    'FourPlayWin' => $wintype,
                ));

                if ($UpdateCoin) {
                    $response = ['notice' => 'User Win Status Update','totalcoin' => $PlayerTotalCount];
                    return response($response, 200);
                } else {
                    $response = ['notice' => 'Coin Not Updated','totalcoin' => $PlayerTotalCount];
                    return response($response, 200);
                }
            }
            if ($request->wintype == "Private") {
                $wintype = $PlayerData['twoPlayWin'] + 1;
                $UpdateCoin = Userdata::where('playerid', $request->playerid)->update(array(
                    'wincoin' => $winAmount,
                    'totalcoin' => $finalAmount,
                    'playcoin' => $PlayerTotalCount,'game_win_amount'=>$game_win_amount,
                    
                    'twoPlayWin' => $wintype,
                ));

                if ($UpdateCoin) {
                    $response = ['notice' => 'User Win Status Update','totalcoin' => $PlayerTotalCount];
                    return response($response, 200);
                } else {
                    $response = ['notice' => 'Coin Not Updated','totalcoin' => $PlayerTotalCount];
                    return response($response, 200);
                }
            }
            else{
                $response = ['notice' => 'Coin Not Updated'];
                    return response($response, 200);
            }
        } 
        else{
            
            $PlayerData = Userdata::where('playerid', $request->playerid)->first();
            
            
            if ($request->wintype == "twoplayerwin"){
                $losstype = $PlayerData['twoPlayloss'] + 1;
                $UpdateCoin = Userdata::where('playerid', $request->playerid)->update(array(
                    
                    'twoPlayloss' => $losstype,
                ));

                if ($UpdateCoin) {
                    $response = ['notice' => 'User loss Status Update'];
                    return response($response, 200);
                } else {
                    $response = ['notice' => 'Coin Not Updated'];
                    return response($response, 200);
                }
            } else {
                $losstype = $PlayerData['FourPlayloss'] + 1;
                $UpdateCoin = Userdata::where('playerid', $request->playerid)->update(array(
                   
                    'FourPlayloss' => $losstype,
                ));

                if ($UpdateCoin) {
                    $response = ['notice' => 'User loss Status Update'];
                    return response($response, 200);
                } else {
                    $response = ['notice' => 'Coin Not Updated'];
                    return response($response, 200);
                }
            }
        }
    }
     public function HandGameStatus(Request $request)
    {
        if ($request->status == "win"){
            $PlayerData = Userdata::where('playerid', $request->playerid)->first();
            $finalAmount = $PlayerData['totalcoin'] ;
             $game_win_amount= $PlayerData['game_win_amount']+ $request->winamount+ $request->entrycoin;
            $winAmount = $PlayerData['wincoin'] + $request->winamount+ $request->entrycoin;

            $PlayerTotalCount = $finalAmount+$winAmount;

            if ($request->wintype == "twoplayerwin") {
                $wintype = $PlayerData['twoPlayWin'] + 1;
                $UpdateCoin = Userdata::where('playerid', $request->playerid)->update(array(
                    'wincoin' => $winAmount,
                    'totalcoin' => $finalAmount,
                    'playcoin' => $PlayerTotalCount,'game_win_amount'=>$game_win_amount,
                   
                    'twoPlayWin' => $wintype,
                ));

                if ($UpdateCoin) {
                    $response = ['notice' => 'User Win Status Update','totalcoin' => $PlayerTotalCount];
                    return response($response, 200);
                } else {
                    $response = ['notice' => 'Coin Not Updated','totalcoin' => $PlayerTotalCount];
                    return response($response, 200);
                }
            }
        }
        else{
            
            $PlayerData = Userdata::where('playerid', $request->playerid)->first();
            
            
            if ($request->wintype == "twoplayerwin"){
                $losstype = $PlayerData['twoPlayloss'] + 1;
                $UpdateCoin = Userdata::where('playerid', $request->playerid)->update(array(
                    
                    'twoPlayloss' => $losstype,
                ));

                if ($UpdateCoin) {
                    $response = ['notice' => 'User loss Status Update'];
                    return response($response, 200);
                } else {
                    $response = ['notice' => 'Coin Not Updated'];
                    return response($response, 200);
                }
            } 
        }
    }

    public function WithdrawRequest(Request $request)
    {
        $PlayerData = Userdata::where('playerid', $request->playerid)->first();
        $withdrawData = Withdraw::where('userid', $request->playerid)->where('status', '0')->first();

        if ($withdrawData != "") {
            $response = ['notice' => 'Already Requested'];
            return response($response, 200);
        } else {
            if ($PlayerData['wincoin'] >= $request->requestAmount) {
                $RestWinAmount = $PlayerData['wincoin'] - $request->requestAmount;
                //now check payment method
                if ($request->withdrawmethod == "upi") {

                    //now check payment method
                    $insert = Withdraw::insert([
                        "userid" => $request->playerid,
                        'amount' => $request->requestAmount,
                        "payment_method" => "UPI",
                        "bank_name" => "..",
                        "account_number" => $request->upi_id,
                        "ifsc_code" => "..",
                        "status" => "0",
                        "created_at" => \Carbon\Carbon::now(),
                    ]);

                    if ($insert) {
                        $playbalance = $PlayerData['totalcoin']+$RestWinAmount;

                        $UpdateTotalCoin = Userdata::where('playerid', $request->playerid)->update(array(
                            'wincoin' => $RestWinAmount,
                            'playcoin' => $playbalance,
                        ));
                        if ($UpdateTotalCoin) {
                            $remaningwincoin = Userdata::where('playerid', $request->playerid)->first();
                            $response = ['notice' => 'Final Amount Update',"wincoin"=>$remaningwincoin['wincoin']];
                            return response($response, 200);
                        } else {
                            $response = ['notice' => 'Final Amount Not Updated'];
                            return response($response, 200);
                        }
                    } else {
                        $response = ['notice' => 'Bank Details Not Inserted'];
                        return response($response, 200);
                    }
                } else if ($request->withdrawmethod == "paytm") {

                    //now check payment method
                    $insert = Withdraw::insert([
                        "userid" => $request->playerid,
                        'amount' => $request->requestAmount,
                        "payment_method" => "Paytm",
                        "bank_name" => "..",
                        "account_number" => $request->Paytm_ID,
                        "ifsc_code" => "..",
                        "status" => "0",
                        "created_at" => \Carbon\Carbon::now(),
                    ]);

                    if ($insert) {
                        $playbalance = $PlayerData['totalcoin']+$RestWinAmount;
                        $UpdateTotalCoin = Userdata::where('playerid', $request->playerid)->update(array(
                            'wincoin' => $RestWinAmount,
                            'playcoin' => $playbalance,
                        ));
                        if ($UpdateTotalCoin) {
                            $remaningwincoin = Userdata::where('playerid', $request->playerid)->first();
                            $response = ['notice' => 'Final Amount Update',"wincoin"=>$remaningwincoin['wincoin']];
                            return response($response, 200);
                        } else {
                            $response = ['notice' => 'Final Amount Not Updated'];
                            return response($response, 200);
                        }
                    } else {
                        $response = ['notice' => 'Bank Details Not Inserted'];
                        return response($response, 200);
                    }
                } else if ($request->withdrawmethod == "bank") {

                    //now check payment method
                    $insert = Withdraw::insert([
                        "userid" => $request->playerid,
                        'amount' => $request->requestAmount,
                        "payment_method" => "Bank Account",
                        "bank_name" => $request->bank_name,
                        "account_number" => $request->account_number,
                        "ifsc_code" => $request->ifsc,
                        "status" => "0",
                        "created_at" => \Carbon\Carbon::now(),
                    ]);

                    if ($insert) {
                        $playbalance = $PlayerData['totalcoin']+$RestWinAmount;
                        $UpdateTotalCoin = Userdata::where('playerid', $request->playerid)->update(array(
                            'wincoin' => $RestWinAmount,
                            'playcoin' => $playbalance,
                        ));
                        if ($UpdateTotalCoin) {
                            $remaningwincoin = Userdata::where('playerid', $request->playerid)->first();
                            $response = ['notice' => 'Final Amount Update',"wincoin"=>$remaningwincoin['wincoin']];
                            return response($response, 200);
                        } else {
                            $response = ['notice' => 'Final Amount Not Updated'];
                            return response($response, 200);
                        }
                    } else {
                        $response = ['notice' => 'Bank Details Not Inserted'];
                        return response($response, 200);
                    }
                }
            } else {
                $response = ['notice' => 'You Dont have Enough Coin'];
                return response($response, 200);
            }
        }
    }

    //update bank account

    public function UpdateBankAccount(Request $request)
    {
        $UpdateBank = Userdata::where('playerid', $request->playerid)->update(array(
            'accountHolder' => $request->accountHolder,
            'accountNumber' => $request->accountNumber,
            'ifsc' => $request->ifsc,
        ));
        if ($UpdateBank) {
            $response = ['notice' => 'Account NUmber Update'];
            return response($response, 200);
        } else {
            $response = ['notice' => 'Account Number Not Updated'];
            return response($response, 200);
        }
    }

    //payment history

    public function PaymentHistory(Request $request)
    {
        $withdraw = Withdraw::where('userid', $request->playerid)->get();
        $transaction = Transaction::where('userid', $request->playerid)->get();
        if ($withdraw) {
            $response = ['notice' => 'Player Withdraw & Transaction History', 'withdrawhistory' => $withdraw, 'transactionhistory' => $transaction];
            return response($response, 200);
        } else {
            $response = ['notice' => 'No Any History Found'];
            return response($response, 200);
        }
    }

    //now search player

    public function SearchPlayer(Request $request)
    {
        $searchPlayer = Userdata::where('playerid', $request->playerid)->first();
        if ($searchPlayer) {
            $response = ['notice' => 'Player Found', 'playerdata' => $searchPlayer];
            return response($response, 200);
        } else {
            $response = ['notice' => 'Player Not Found'];
            return response($response, 200);
        }
    }

    //now add game history

    public function AddGameHistory(Request $request){
        $userdata = Userdata::where('playerid', $request->playerid)->first();
        $finalAmount = $userdata['totalcoin']+$userdata['wincoin']+$userdata['refrelCoin'];

        $insert = Gamehistorie::insert([
            "playerid" => $request->playerid,
            "status" => $request->status,
            "bid_amount" => $request->bid_amount,
            "Win_amount" => $request->Win_amount,
            "loss_amount" => $request->loss_amount,
            "seat_limit" => $request->seat_limit,
            "oppo1" => $request->oppo1,
            "oppo2" => $request->oppo2,
            "oppo3" => $request->oppo3,
            "game_name" => $request->game_name,
            "playername" => $userdata['username'],
            "finalamount" => $finalAmount,
            "isOpponentBot" => $request->isOpponentBot,
            "playtime" =>  date("l jS F Y h:i:s A"),
        ]);

        if ($insert) {
            $response = ['notice' => 'History Added'];
            return response($response, 200);
        } else {
            $response = ['notice' => 'History Not Added'];
            return response($response, 200);
        }

    }


     public function Leaderboard(Request $request)
    {
        $userdata =Userdata::select('*')
    ->orderBy(DB::raw('CAST(game_win_amount AS UNSIGNED)'), 'DESC')
    ->limit(15)->get();
        $response = ["message" => 'Leader Board Fetch Success', 'leaderboard' => $userdata];
        return response($response, 200);
    }

    //now refer player code

    public function ReferCode(Request $request)
    {
        $gameConfig = Websetting::first();
        $ReferCode = Userdata::where('refer_code', $request->refercode)->first();
        if ($ReferCode != "") {
            $userdata = Userdata::where('playerid', $request->playerid)->first();
            $refercoin = $ReferCode["refrelCoin"] + $gameConfig["refer_bonus"];

            if ($userdata["used_refer_code"] == null) {
                $update = Userdata::where('playerid', $request->playerid)->update(array(
                    "used_refer_code" => $request->refercode,
                ));
                if ($update) {

                    $updatereferuser = Userdata::where('refer_code', $request->refercode)->update(array(
                        "refrelCoin" => $refercoin,
                    ));

                    if ($updatereferuser) {
                        $response = ['notice' => 'Refer Success'];
                        return response($response, 200);
                    } else {
                        $response = ['notice' => 'Something is wrong'];
                        return response($response, 200);
                    }
                } else {
                    $response = ['notice' => 'Refer Code Not Updated'];
                    return response($response, 200);
                }
            } else {
                $response = ['notice' => 'Refer Code Already Used'];
                return response($response, 200);
            }
        } else {
            $response = ['notice' => 'Invalid Refer Code'];
            return response($response, 200);
        }
    }
   public function UpdateLeaguePoint(Request $request){
    $league_id = $request->league_id;
    $pid = $request->playerid;
    $points = $request->points;

  $update = LeagueRankUsers::where('league_id', $league_id)
        ->where('player_id', $pid)
        ->update([
            'points' => $points,
            'chances_used' => DB::raw('chances_used + 1')
        ]);

    if ($update) {
        $response = ['notice' => 'Points Added'];
        return response($response, 200);
    } else {
        $response = ['notice' => 'Points Not Added'];
        return response($response, 200);
    }
}
 public function UpdateLeaguePointBot(Request $request){
    $league_id = $request->league_id;
    $pid = $request->playerid;
    $points = $request->points;

  $update = LeagueRankUsers::where('league_id', $league_id)
        ->where('player_name', $pid)
        ->where('is_bot', 1)
        ->update([
            'points' => DB::raw("points + $points"),
            'chances_used' => DB::raw('chances_used + 1')
        ]);


    if ($update) {
        $response = ['notice' => 'Points Added'];
        return response($response, 200);
    } else {
        $response = ['notice' => 'Points Not Added'];
        return response($response, 200);
    }
}

public function GameLeft($pid,$bidamount,$finalamount){
       
}
    public function AppVersion(){
       $gameConfig = Websetting::first();
       $response = ["message" =>'All Details Fetched Successfully','gameconfig'=>$gameConfig];
       return response($response, 200);
    }
}
