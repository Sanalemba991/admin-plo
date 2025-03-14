<?php

namespace App\Http\Controllers\Home;

use App\Http\Controllers\Controller;
use App\Models\Withdraw\Withdraw;
use App\Models\Transaction\Transaction;
use App\Models\Player\Userdata;
use App\Models\Kyc\Kycdetail;
use App\Models\Gamehistory\Gamehistorie;
use App\Models\Player\Roomdata;
use App\Models\WebSetting\Websetting;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function Index(){
        $owneranl['owner_investmen'] = Gamehistorie::sum('bid_amount');
        $owneranl['Withdrawable'] = Withdraw::where("status","1")->sum('amount');
       // return  $owneranl['owner_investmen'];
      // $Withdraw = DB::table('withdraws')->join('userdatas','withdraws.userid','userdatas.userid')->get();
       $Withdraw = Withdraw::latest()->paginate(10);
       $TopPlayer = Userdata::orderBy('totalcoin','ASC')->take(1)->first();
       $Transaction = Transaction::latest()->paginate(10);
       $LiveMatch = Roomdata::count();
       $Userdata = Userdata::latest()->paginate(10);
       $totalUser = Userdata::count();
       $currentDate = Carbon::now();
       $TodaysDate = $currentDate->toDateString();
       $Todays = Userdata::whereDate('created_at', $TodaysDate)->count();
       $WalletAmount = Userdata::sum('refrelCoin');
       $AllTransaction = Transaction::count();
       $TotalTransaction = Transaction::where("status","success")->sum('amount');
       $TotalSentMoney = Userdata::where("used_refer_code",null)->count();
       //total withdraw request
       $TotalApprovedWithRequest = Withdraw::where("status","1")->count();
       $TotalPendingWithRequest = Withdraw::where("status","0")->count();
       $TotalRejectWithRequest = Withdraw::where("status","2")->count();
       //total kyc details
       $TotalApprovedKyc = Userdata::where("userid",'NOT FILLED')->orWhere('userid', '=', null)->count();
       $TotalPendingKyc = Kycdetail::where("verification_status","0")->count();
       $TotalRejectKyc = Kycdetail::where("verification_status","2")->count();
       $websetting = Websetting::first();

       $WinningAmount = Userdata::sum('wincoin');
       $TotalfinalAmount = $WalletAmount+$WinningAmount;
       $FinalAmount = $TotalTransaction-$TotalfinalAmount;
       $OwnerAmount = $TotalTransaction;

       return view('admin.index',compact('owneranl','Todays','Withdraw','Transaction','Userdata','totalUser','OwnerAmount','WinningAmount','WalletAmount','AllTransaction','TotalSentMoney','TotalApprovedWithRequest','TotalRejectWithRequest','TotalPendingWithRequest','TotalApprovedKyc','TotalPendingKyc','TotalRejectKyc','TopPlayer','websetting','LiveMatch'));
    }

    public function GetLeaderboard(){
        $userdata = Userdata::select('*')
    ->orderBy(DB::raw('CAST(wincoin AS UNSIGNED)'), 'DESC')
    ->paginate(10);
        return view('admin.Home.Leaderboard',compact('userdata'));
    }
}
