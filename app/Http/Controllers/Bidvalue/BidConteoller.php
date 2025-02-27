<?php

namespace App\Http\Controllers\Bidvalue;

use App\Http\Controllers\Controller;
use App\Models\Bidvalue\Bid;
use App\Models\Bidvalue\Match_cricket;
use App\Models\Bidvalue\Leagues;
use App\Models\Player\Userdata;
use App\Models\Bidvalue\League_prize_pools;
use App\Models\Bidvalue\LeagueRankUsers;
use App\Models\Bidvalue\League_rank_prizes;
use Illuminate\Http\Request;
use App\Models\Gamehistory\Gamehistorie;
use Illuminate\Support\Facades\DB;

class BidConteoller extends Controller
{
    public function index()
    {
        $brands = Bid::latest()->paginate(10);
        return view("admin.Bidvalue.Bidvalue", compact('brands'));
    }
      public function coupons()
    {
        $brands = DB::table('coupons')->orderBy('id', 'desc')->paginate(10);
        return view("admin.Bidvalue.Coupons", compact('brands'));
    }
     public function cricket()
    {
        $brands = Match_cricket::latest()->paginate(10);
        return view("admin.Bidvalue.Cricketvalue", compact('brands'));
    }
     public function leagues()
    {
        $brands = Leagues::latest()->paginate(10);
        return view("admin.Bidvalue.Leagues", compact('brands'));
    }
     public function tournaments()
    {
        $brands = DB::table('tournaments')->orderBy('id', 'desc')->paginate(10);
        return view("admin.Bidvalue.Tournament", compact('brands'));
    }
     public function prizepool(Request $request)
    {
       
        $league_id=$request->league_id;
         $brands = League_prize_pools::latest()->where('league_id','=',$league_id)->paginate(10);
        return view("admin.Bidvalue.Prizepool", compact('brands','league_id'));
    }
     public function rankuser(Request $request)
{
    $league_id = $request->league_id;
    $entry=Leagues::where('league_id', $league_id)->get();
  
    
    // Fetch brands
    $brands = LeagueRankUsers::latest()
        ->where('league_id', $league_id)
        ->orderBy('points', 'desc')
        ->paginate(10);
    
    // Fetch all bot users
    $botUsers = Userdata::where('is_bot', 1)->get();

  
    
    return view("admin.Bidvalue.RankUsers", compact('brands', 'league_id', 'botUsers'));
}
 public function tournament_user(Request $request)
{
   $league_id = $request->league_id;
$entry = DB::table('tournaments')
    ->where('id', $league_id)
    ->first();

if ($entry) {
    $entry_fee = $entry->bit_amount;
} else {
    // Handle the case where no entry was found
}

    
    // Fetch brands
    $brands = DB::table('tournament_users')
    ->where('tournament_id', $league_id)
    
        ->paginate(10);
    
    // Fetch all bot users
    $botUsers = Userdata::where('is_bot', 1)->get();

    // Filter bot users to remove those present in $brands
  //  $botUsers = $botUsers->reject(function ($botUser) use ($brands) {
  //      return $brands->contains('playerid', $botUser->player_id);
   // });
    
    return view("admin.Bidvalue.TournamentUser", compact('brands', 'league_id', 'botUsers','entry_fee'));
}


    //create brands

    public function create(Request $request)
    {
        $getbidlength = Bid::count();

        if ($getbidlength >= 10) {
            $request->session()->flash('error', 'You Can Create Max 10 Bids');
            return redirect('admin/bid/coin');
        } else {
            $response = Bid::create($request->all());
            if ($response) {
                $request->session()->flash('success', 'Contest Added Successfully !');
                return redirect('admin/bid/coin');
            } else {
                $request->session()->flash('error', 'Something Is Wrong Please Try Again !');
                return redirect('admin/bid/coin');
            }
        }
    }
     public function create_cricket(Request $request)
    {
        if($request->game_type==1){
            $total_no_of_player=2;
            $joined_player=1;
        }else{
            $total_no_of_player=2;
              $joined_player=$total_no_of_player/2;
        }
            $response = Match_cricket::create([
    'game_type' => $request->game_type,
    'entry_fee' => $request->entry_fee,
    'prize_pool' =>($request->pos1_prize+$request->pos2_prize+$request->pos3_prize),
    'pos1_prize' => $request->pos1_prize,
    'pos2_prize' => $request->pos2_prize,
    'pos3_prize' => $request->pos3_prize,
    'match_status' => 0,
    'match_time' => 0, // Adjust this to the correct data type
    'total_no_of_players' => $total_no_of_player,
    'total_joined_players' => $joined_player,
]);
            if ($response) {
                $request->session()->flash('success', 'Contest Added Successfully !');
                return redirect('admin/bid/cricket');
            } else {
                $request->session()->flash('error', 'Something Is Wrong Please Try Again !');
                return redirect('admin/bid/cricket');
            }
       
    }

  public function create_league(Request $request)
    {
       
    $response = Leagues::create([
    'game' => $request->game_name,
    'entry_fee' => $request->entry_fee,
    'total_spots' =>$request->total_spots,
    'total_chances' =>1,
    'joined' => 0,
    'start_time' => $request->start_time,
    'end_time' => $request->end_time,
    'result_time' => $request->result_time,
    
]);
            if ($response) {
                $request->session()->flash('success', 'League Added Successfully !');
                return redirect('admin/bid/leagues');
            } else {
                $request->session()->flash('error', 'Something Is Wrong Please Try Again !');
                return redirect('admin/bid/leagues');
            }
       
    }
      public function Create_Coupons(Request $request)
    {
       
    $response = DB::table('coupons')->insert([
    'code' => $request->coupon_id,
    'amount' => $request->amount,
    'total_uses' => $request->uses,
    'validity' => $request->validity,
    
]);
            if ($response) {
                $request->session()->flash('success', 'Tournament Added Successfully !');
                return redirect('admin/bid/tournament');
            } else {
                $request->session()->flash('error', 'Something Is Wrong Please Try Again !');
                return redirect('admin/bid/tournament');
            }
       
    }
     public function Create_tournament(Request $request)
    {
       
    $response = DB::table('tournaments')->insert([
    'title' => $request->title,
    'bit_amount' => $request->entry_fee,
    'no_of_player' => $request->total_players,
    'total_rounds' => $request->total_rounds,
    'round_interval' => $request->interval,
    'start_time' => $request->start_time,
    'prize1' => $request->prize1,
    'prize2' => $request->prize2,
    'prize3' => $request->prize3,
    'prize4' => $request->prize4,
    'round1_bonus' => $request->round1_bonus,
    'round2_bonus' => $request->round2_bonus,
    'round3_bonus' => $request->round3_bonus,
    'round4_bonus' => $request->round4_bonus,
    'round5_bonus' => $request->round5_bonus,
    'round6_bonus' => $request->round6_bonus,
    'round7_bonus' => $request->round7_bonus,
    'round8_bonus' => $request->round8_bonus,
    'round9_bonus' => $request->round9_bonus,
]);
            if ($response) {
                $request->session()->flash('success', 'Tournament Added Successfully !');
                return redirect('admin/bid/tournament');
            } else {
                $request->session()->flash('error', 'Something Is Wrong Please Try Again !');
                return redirect('admin/bid/tournament');
            }
       
    }
 
  public function JoinTournament(Request $request)
    {
        $PlayerData = Userdata::where('playerid', $request->playerid)->first();
        $GamePlayed = $PlayerData['HandGamePlayed'] + 1;
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
            'totalcoin' => $finalTotalAmount,'HandGamePlayed' => $GamePlayed,
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
               'wincoin' => $finalWinAmount,'HandGamePlayed' => $GamePlayed,
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
               'refrelCoin' => $finalWinAmount,'HandGamePlayed' => $GamePlayed,
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
                'wincoin' => $finalWinAmount,'HandGamePlayed' => $GamePlayed,
                'totalcoin'=> "0",
           ));
            }else{
                $finalref=$request->bidamount-($PlayerData['totalcoin']+$PlayerData['wincoin']);
                $UpdateCoin = Userdata::where('playerid', $request->playerid)->update(array(
                'wincoin' => 0,'HandGamePlayed' => $GamePlayed,
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
    
 public function JoinLeague(Request $request)
    {
        $PlayerData = Userdata::where('playerid', $request->playerid)->first();
        $GamePlayed = $PlayerData['HandGamePlayed'] + 1;
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
            'totalcoin' => $finalTotalAmount,'HandGamePlayed' => $GamePlayed,
        ));

        if ($UpdateCoin) {
           $userdata = Userdata::where('playerid', $request->playerid)->first();
           $finalAmount = $userdata['totalcoin']+$userdata['wincoin']+$userdata['refrelCoin'];

        $insert = Gamehistorie::insert([
            "playerid" => $request->playerid,
            "status" => 'Join a League',
             "game_name" => 'League',
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
               'wincoin' => $finalWinAmount,'HandGamePlayed' => $GamePlayed,
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
               'refrelCoin' => $finalWinAmount,'HandGamePlayed' => $GamePlayed,
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
                'wincoin' => $finalWinAmount,'HandGamePlayed' => $GamePlayed,
                'totalcoin'=> "0",
           ));
            }else{
                $finalref=$request->bidamount-($PlayerData['totalcoin']+$PlayerData['wincoin']);
                $UpdateCoin = Userdata::where('playerid', $request->playerid)->update(array(
                'wincoin' => 0,'HandGamePlayed' => $GamePlayed,
                'totalcoin'=> "0",'refrelCoin'=> $PlayerData['totalcoin']-$finalref,
           ));
            }
            

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
    public function add_bots(Request $request)
    {
        $response= $this->JoinTournament($request);
    

            if ($response) {
         
                $request->session()->flash('success', 'Bot Added Successfully !');
                return redirect('admin/bid/tournament_user?league_id='.$request->league_id);
            } else {
                $request->session()->flash('error', 'Something Is Wrong Please Try Again !');
                return redirect('admin/bid/tournament_user?league_id='.$request->league_id);
            }
       
    }


 public function create_pool(Request $request)
{
    $rankFrom = $request->input('rankFrom');
    $rankTo = $request->input('rankTo');
    $prizeAmount = $request->input('prizeAmount');

    $leagueId = $request->input('league_id');

    // Loop through each row of data and save it
    foreach ($rankFrom as $key => $value) {
        $response = League_prize_pools::create([
            'league_id' => $leagueId,
            'rank_from' => $rankFrom[$key],
            'rank_to' => $rankTo[$key],
            'prize_amount' => $prizeAmount[$key],
        ]);
        for($i=$rankFrom[$key];$i<=$rankTo[$key];$i++){
             $response2 = League_rank_prizes::create([
            'league_id' => $leagueId,
            'rank' => $i,
            'prize' => $prizeAmount[$key],
        ]);
        }
        
    }
if ($response) {
                $request->session()->flash('success', 'Prizepool Added Successfully !');
                return redirect('admin/bid/prizepool?league_id='.$leagueId);
            } else {
                $request->session()->flash('error', 'Something Is Wrong Please Try Again !');
                return redirect('admin/bid/prizepool?league_id='.$leagueId);
            }
    // Handle response or redirect as needed
}


    //now edit product brand

    public function edit($id)
    {

        $response = Bid::where('id', $id)->get();
        if ($response) {
            return response(array("data" => $response), 200)->header("Content-Type", "application/json");
        } else {
            return response(array("notice" => "Data Not Delete"), 404)->header("Content-Type", "application/json");
        }
    }
    public function edit_league($id)
    {

        $response = Leagues::where('league_id', $id)->get();
        if ($response) {
            return response(array("data" => $response), 200)->header("Content-Type", "application/json");
        } else {
            return response(array("notice" => "Data Not Delete"), 404)->header("Content-Type", "application/json");
        }
    }

    //now update product brands

    public function update(Request $request, $id)
    {

        $response = Bid::where("id", $id)->update(array(
            "game_type" => $request["game_type"],
             "entry_fee" => $request["entry_fee"],
              "first_prize" => $request["first_prize"],
               "second_prize" => $request["second_prize"],
        ));

        //send response
        if ($response) {
            $request->session()->flash('success', 'Bid Value Updated Successfully !');
            return back();
        } else {
            $request->session()->flash('error', 'Something Is Wrong Pleease Try Again !');
            return back();
        }
    }

    public function delete($id)
    {
        $response = Bid::find($id);
        $response = $response->delete();
        if ($response) {
            return response(array("notice" => "Data Delete Success"), 200)->header("Content-Type", "application/json");
        } else {
            return response(array("notice" => "Data Not Delete"), 404)->header("Content-Type", "application/json");
        }
    }
    public function delete_coupon($id)
    {
        $response =DB::table('coupons')->where('id', $id)->delete();
       
        if ($response) {
            return response(array("notice" => "Data Delete Success"), 200)->header("Content-Type", "application/json");
        } else {
            return response(array("notice" => "Data Not Delete"), 404)->header("Content-Type", "application/json");
        }
    }
     public function delete_league($id)
    {
        $response = Leagues::where("league_id",$id);
        $response = $response->delete();
        if ($response) {
            return response(array("notice" => "Data Delete Success"), 200)->header("Content-Type", "application/json");
        } else {
            return response(array("notice" => "Data Not Delete"), 404)->header("Content-Type", "application/json");
        }
    }
}
