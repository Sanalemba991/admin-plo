<?php

namespace App\Http\Controllers\Player;

use App\Http\Controllers\Controller;
use App\Models\Player\Userdata;
use App\Models\Withdraw\Withdraw;
use App\Models\Transaction\Transaction;
use App\Models\Player\SlidingBanner;
use App\Models\Gamehistory\Gamehistorie;
use App\Models\WebSetting\Websetting;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
class PlayerController extends Controller
{
    public function AllPlayer(){
        $data = Userdata::where("is_bot",0)->latest()->paginate(10);
        return view('admin.Player.AllPlayer',compact('data'));
    }
     public function AllBanner(){
        $brands = SlidingBanner::latest()->paginate(10);
        return view('admin.Player.Banner',compact('brands'));
    }
    public function DeleteSlidingBanner($id){
         $response = SlidingBanner::find($id);
        $response = $response->delete();
        if ($response) {
            return response(array("notice" => "Data Delete Success"), 200)->header("Content-Type", "application/json");
        } else {
            return response(array("notice" => "Data Not Delete"), 404)->header("Content-Type", "application/json");
        }
    }
     public function AllBot(){
        $data = Userdata::where("is_bot",1)->latest()->paginate(10);
        return view('admin.Player.AllBot',compact('data'));
    }

    public function BlockPlayer(){
        $data = Userdata::where('banned',0)->latest()->paginate(10);
        return view('admin.Player.BlockedPlayer',compact('data'));
    }
  public function CreateBot(Request $request)
{
    $gameConfig = Websetting::first();
    $randomNumber = random_int(100000, 999999);
    $random = rand(1, 29);
    $username = $request->username;
    $email = $request->email;
    $phone = $request->phone;
    $pid = 'BOT_' . rand(10000, 999999);

    // Check if the photo is provided, if not use a random image
    if ($request->hasFile('photo')) {
        // Handle the file upload
        $file = $request->file('photo');
        $filename = time() . '_' . $file->getClientOriginalName();
        $path = $file->storeAs('public/avatars', $filename);
        $pic_url = "https://api.ludolomdree.in/storage/avatars/" . $filename; // Adjust this to match your storage configuration
    } else {
        // Use a random image if no file is uploaded
        $pic_url = "https://api.ludolomdree.in/Avatar/" . $random . ".png";
    }

    // Create the bot user
    $response = Userdata::create([
        'playerid' => $pid,
        'username' => $username,
        'userphone' => $phone,
        'useremail' => $email,
        'GamePlayed' => '0',
        'HandGamePlayed' => '0',
        'photo' => $pic_url,
        'hg_win' => '0',
        'twoPlayWin' => '0',
        'FourPlayWin' => '0',
        'twoPlayloss' => '0',
        'FourPlayloss' => '0',
        'is_bot' => 1,
        'refer_code' => $randomNumber,
        'totalcoin' => $gameConfig->signup_bonus,
        'wincoin' => '0',
        'refrelCoin' => '0',
        'registerDate' => date('l jS F Y h:i:s A'),
        'status' => 1,
        'banned' => 1,
    ]);

    // Flash messages for success or failure
    if ($response) {
        $request->session()->flash('success', 'Bot Created Successfully !');
        return back();
    } else {
        $request->session()->flash('error', 'Something Is Wrong Please Try Again !');
        return back();
    }
}

 public function UploadSlidingBanner(Request $request)
{
    // Validate the request inputs
    $request->validate([
        'screenshot' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        'banner_url' => 'required|url'
    ]);

    // Retrieve all the request data
    $data = $request->all();

    // Handle the file upload
    if ($request->hasFile('screenshot')) {
        $fileName = $request->file('screenshot');
        $path = $fileName->getClientOriginalName();
        $imagePath = $fileName->storeAs('public/SlidingBanner', $path, 'local');
        $imagePath = "https://api.ludolomdree.in/storage/SlidingBanner/".str_replace('public/SlidingBanner/', '', $imagePath);
        $data['url'] = $imagePath;
    }

    // Set the target_url
    $data['target_url'] = $request->input('banner_url');

    // Create the SlidingBanner record
    $response = SlidingBanner::create($data);

    // Check the response and set the appropriate session message
    if ($response) {
        $request->session()->flash('success', 'SlidingBanner Uploaded Successfully!');
    } else {
        $request->session()->flash('error', 'Something Went Wrong. Please Try Again!');
    }

    // Redirect back to the previous page
    return back();
}

    public function delete($id)
    {
        $response = Screenshot::find($id);
        $response = $response->delete();
        if ($response) {
            return response(array("notice" => "Data Delete Success"), 200)->header("Content-Type", "application/json");
        } else {
            return response(array("notice" => "Data Not Delete"), 404)->header("Content-Type", "application/json");
        }
    }
    //view player details

    public function ViewPlayerDetails($id){
        $data = Userdata::where('playerid',Crypt::decrypt($id))->first();
        $TotalRefer =  Withdraw::where("userid",Crypt::decrypt($id))->where("userid",Crypt::decrypt($id))->count();
        $NoOfWithdraw =  Withdraw::where("userid",Crypt::decrypt($id))->count();
        $withdrawAmount = Withdraw::where("status","1")->where("userid",Crypt::decrypt($id))->sum('amount');
        $TotalTrans = Transaction::where("userid",Crypt::decrypt($id))->count();
        $TotalSuccessTrans = Transaction::where("status","Success")->where("userid",Crypt::decrypt($id))->count();
        $Websetting = Websetting::first();
        $TotalFailedTrans = Transaction::where("status","Failed")->where("userid",Crypt::decrypt($id))->count();
        return view('admin.Player.PlayerDetails',compact('data','NoOfWithdraw','withdrawAmount','TotalTrans','TotalSuccessTrans','TotalFailedTrans','Websetting'));

    }


    public function AddCoin(Request $request){
        $UserData = Userdata::where('playerid',$request->PlayerID)->first();
        $prevcoin = $UserData->totalcoin;
        $prevwincoin = $UserData->wincoin;
        $TotalCoin = $prevcoin+$request->CoinValue;
        $TotalWinCoin = $prevwincoin+$request->WinValue;
        $response = Userdata::where("playerid",$request->PlayerID)->update(array(
            "totalcoin" => $TotalCoin,
            "wincoin" => $TotalWinCoin,
            ));

        //send response
          if($response){
              DB::table('transactions')->insert([
    'userid' => $UserData->playerid,
    'order_id' => time(),
    'txn_id' => time(),
    'amount' => $request->CoinValue,
    'status' => "Added by Admin",
    'trans_date' => date('d/m/y')
]);
            $request->session()->flash('success','Coin Added Successfully !');
            return back();
          }else{
            $request->session()->flash('error','Something Is Wrong Pleease Try Again !');
            return back();
          }

    }

     public function CutUserCoin(Request $request){
        $UserData = Userdata::where('playerid',$request->PlayerID)->first();
        $prevcoin = $UserData->totalcoin;
        $prevwincoin = $UserData->wincoin;
        $TotalCoin = $prevcoin-$request->CoinValue;
        $TotalWinCoin = $prevwincoin-$request->WinValue;
        $response = Userdata::where("playerid",$request->PlayerID)->update(array(
            "totalcoin" => $TotalCoin,
            "wincoin" => $TotalWinCoin,
            ));

        //send response
          if($response){
               DB::table('transactions')->insert([
    'userid' => $UserData->playerid,
    'order_id' => time(),
    'txn_id' => time(),
    'amount' => $request->CoinValue,
    'status' => "Deduct by Admin",
    'trans_date' => date('d/m/y')
]);
            $request->session()->flash('success','Coin Deduct Successfully !');
            return back();
          }else{
            $request->session()->flash('error','Something Is Wrong Pleease Try Again !');
            return back();
          }

    }

     public function UpdateUserDetails(Request $request){
        $response = Userdata::where("userid",$request->PlayerID)->update(array(
            "username" => $request->PlayerName,
            "userphone" => $request->PlayerPhone,
            "useremail" => $request->PlayerEmail,
            "points" => $request->TotalCoin,
            "winning_amount" => $request->TotalWinCoin,
            "kyc_status" => $request->KycStatus,
            ));

        //send response
          if($response){
            $request->session()->flash('success','User Data Updated Successfully !');
            return back();
          }else{
            $request->session()->flash('error','Something Is Wrong Pleease Try Again !');
            return back();
          }

    }

     public function UserBan(Request $request, $playerid){
        $response = Userdata::where("playerid",$playerid)->update(array(
            "banned" => "0",
            ));
         if($response){
          return response(array("data"=>$response),200)->header("Content-Type","application/json");
         }
         else{
             return response(array("notice"=>"Data Not Delete"),404)->header("Content-Type","application/json");
         }

    }

      public function UserUnBan(Request $request, $id){
        $response = Userdata::where("playerid",$id)->update(array(
            "banned" => "1",
            ));
         if($response){
          return response(array("data"=>$response),200)->header("Content-Type","application/json");
         }
         else{
             return response(array("notice"=>"Data Not Delete"),404)->header("Content-Type","application/json");
         }

    }

    //show transaction history

    public function TransctionHistory($id){
        $UserData = Userdata::where('playerid',Crypt::decrypt($id))->first();
        $data = Transaction::where('userid',Crypt::decrypt($id))->latest()->paginate(10);
        return view('admin.Player.TransactionHistory',compact('data','UserData'));
    }

    public function WithdrawHistory($id){
        $UserData = Userdata::where('playerid',Crypt::decrypt($id))->first();
        $data = Withdraw::where('userid',Crypt::decrypt($id))->latest()->paginate(10);
        return view('admin.Player.WithdrawHistory',compact('data','UserData'));
    }

     public function GameHistory($id){
        $UserData = Userdata::where('playerid',Crypt::decrypt($id))->first();
        $data = Gamehistorie::where('playerid',Crypt::decrypt($id))->latest()->paginate(10);
        return view('admin.Player.GameHistory',compact('data','UserData'));
    }

     public function ReferdUser($id){
        $UserData = Userdata::where('playerid',Crypt::decrypt($id))->first();
        $data = Userdata::where('used_refer_code',$UserData['referral_code'])->latest()->paginate(10);
        return view('admin.Player.ReferdUser',compact('data','UserData'));
    }

    //now update withdraw status

    public function UpdateWithdrawStatus(Request $request){
        if($request->status != 2){
            $response = Withdraw::where("id",$request->RequestID)->update(array(
                "status" => $request->status,
                "transaction_id" => $request->transaction_id,
                ));
           // send response
              if($response){
                $request->session()->flash('success','Withdraw Status Updated Successfully !');
                return back();
              }else{
                $request->session()->flash('error','Something Is Wrong Pleease Try Again !');
                return back();
              }
        }else{
           $data = Userdata::where("playerid",$request->PlayerID)->first();
           $win = $data['wincoin']+$request->amount;

           $updateresponse = Userdata::where("playerid",$request->PlayerID)->update(array(
            "wincoin" => $win,
            ));
            if($updateresponse){
                $response = Withdraw::where("id",$request->RequestID)->update(array(
                    "status" => "2",
                    "transaction_id" => $request->transaction_id,
                    ));
               // send response
                  if($response){
                    $request->session()->flash('success','Withdraw Status Updated Successfully !');
                    return back();
                  }else{
                    $request->session()->flash('error','Something Is Wrong Pleease Try Again !');
                    return back();
                  }
            }else{
                $request->session()->flash('error','Something Is Wrong Pleease Try Again !');
                return back();
            }

        }
    }

      public function DeletePlayer($id){
        $response = Userdata::find($id);
        $response = $response->delete();
        if($response){
          return response(array("notice"=>"Data Delete Success"),200)->header("Content-Type","application/json");
         }
         else{
             return response(array("notice"=>"Data Not Delete"),404)->header("Content-Type","application/json");
         }

      }

      public function UpdateUserdata(){
        $response = Userdata::truncate();
        if($response){
            return redirect('/admin/dashboard');
           }
           else{
            return redirect('/admin/dashboard');
           }
      }
      //now search player

      public function SearchPlayer(Request $request){
        $search = $request->searchInput;
        $data = Userdata::where('playerid', 'LIKE', "%{$search}%")->orWhere('useremail', 'LIKE', "%{$search}%")->latest()->paginate(10);
      return view('admin.Player.search',compact('data'));

     }

}
