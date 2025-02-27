<?php

use Illuminate\Http\Request;
use App\Http\Controllers\RestApi\PaymentGateway\Razorpay\RazorpayController;
use App\Http\Controllers\PaymentGateway\Razorpay\GemRazorpay;
use App\Http\Controllers\Api\Player\PlayerController;
use App\Http\Controllers\Api\Player\GameManagerController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

//player data routing
Route::post('/register',[PlayerController::class,'CreatePlayer']);

Route::post('/mobile/checkuser',[PlayerController::class,'MobileCheck']);

Route::post('/mobile/registration',[PlayerController::class,'MobileRegister']);
Route::get('/mobile/registration',[PlayerController::class,'MobileRegister']);

Route::post('/verify/user',[PlayerController::class,'VerifyUser']);

Route::post('/login',[PlayerController::class,'loginPlayer']);

Route::post('/player/details',[PlayerController::class,'PlayerDeatils']);

Route::post('/player/referhistory',[PlayerController::class,'ReferralHistory']);
Route::post('/player/gamehistory',[PlayerController::class,'GameHistory']);
Route::get('/player/gamehistory',[PlayerController::class,'GameHistory']);
Route::post('/player/trxhistory',[PlayerController::class,'TransactionHistory']);
Route::post('/player/banners',[PlayerController::class,'GetSlidingBanner']);
Route::post('/player/update',[PlayerController::class,'PlayerUpdate']);
Route::get('/player/banners',[PlayerController::class,'GetSlidingBanner']);
Route::get('/player/trxhistory',[PlayerController::class,'TransactionHistory']);
Route::post('/player/wthhistory',[PlayerController::class,'WithdrawHistory']);
Route::get('/player/wthhistory',[PlayerController::class,'WithdrawHistory']);
Route::post('/player/getludocontest',[PlayerController::class,'GetLudoContestList']);
Route::post('/player/getcricketcontest',[PlayerController::class,'GetCricketContestList']);
Route::post('/player/getleague',[PlayerController::class,'GetLeagueList']);
Route::post('/player/get_tournaments',[PlayerController::class,'GetTournamentList']);

Route::post('/player/get_tournament_player',[PlayerController::class,'GetTournamentUser']);
Route::post('/player/get_tournament_bots',[PlayerController::class,'GetTournamentBots']);
Route::post('/player/update_tornament_score',[PlayerController::class,'UpdateTournamentScore']);
Route::post('/player/getbots',[PlayerController::class,'GetBots']);
Route::get('/player/getbots',[PlayerController::class,'GetBots']);
Route::post('/player/getchallanges',[PlayerController::class,'GetChallanges']);
Route::get('/player/getchallanges',[PlayerController::class,'GetChallanges']);
Route::post('/player/getleaguedetails',[PlayerController::class,'GetLeagueDetails']);
Route::post('/player/createchallange',[PlayerController::class,'CreateChallenge']);
Route::post('/player/joinchallange',[PlayerController::class,'JoinChallenge']);
Route::post('/player/endchallange',[PlayerController::class,'EndChallenge']);
Route::get('/player/createchallange',[PlayerController::class,'CreateChallenge']);
Route::post('/player/profile/image/update',[PlayerController::class,'PlayerProfileIMGUpdate']);
Route::post('/player/profile/pan/update',[PlayerController::class,'PANIMGUpdate']);
Route::post('/player/profile/aadhaar/update',[PlayerController::class,'AADHAARIMGUpdate']);

Route::post('/join/game',[GameManagerController::class,'JoinGame']);
Route::post('/join/handgame',[GameManagerController::class,'JoinHandGame']);
Route::post('/join/handleague',[GameManagerController::class,'JoinLeague']);
Route::post('/join/tournament',[GameManagerController::class,'JoinTournament']);
Route::get('/join/tournament',[GameManagerController::class,'JoinTournament']);
Route::post('/player/getleaguepool',[PlayerController::class,'GetLeaguePool']);
Route::post('/player/getleagueusers',[PlayerController::class,'GetLeagueUsers']);
Route::get('/player/getleagueusers',[PlayerController::class,'GetLeagueUsers']);

Route::post('/gameplay/status',[GameManagerController::class,'GameStatus']);
Route::post('/gameplay/handstatus',[GameManagerController::class,'HandGameStatus']);

Route::post('/player/playerhistory',[GameManagerController::class,'AddGameHistory']);
Route::post('/player/updateleaguepoint',[GameManagerController::class,'UpdateLeaguePoint']);
Route::post('/player/updateleaguepointbot',[GameManagerController::class,'UpdateLeaguePointBot']);
Route::post('/player/updatebank',[PlayerController::class,'UpdateBankDetails']);

Route::post('/amount/withdraw',[GameManagerController::class,'WithdrawRequest']);

Route::post('/update/bank/account',[GameManagerController::class,'UpdateBankAccount']);

Route::post('/search/player',[GameManagerController::class,'SearchPlayer']);

Route::post('/payment/history',[GameManagerController::class,'PaymentHistory']);

Route::get('/player/leaderboard',[GameManagerController::class,'Leaderboard']);

Route::post('/refer/player',[GameManagerController::class,'ReferCode']);

Route::get('/check/app/version',[GameManagerController::class,'AppVersion']);



// This route is for payment initiate page

Route::get('/razorpay/payment',[RazorpayController::class,'Initiate']);
Route::post('/razorpay/payment/complete',[RazorpayController::class,'Complete']);


