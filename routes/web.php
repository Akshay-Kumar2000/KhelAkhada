<?php
//die('<h1>Website is under maintenance mode. Please wait for some time. </h1>');

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\User\LoginController;
use App\Http\Controllers\Auth\User\RegisterController;
use App\Http\Controllers\Auth\User\ForgotPasswordController;
use App\Http\Controllers\User\DashboardController;
use App\Http\Controllers\User\KycController;
use App\Http\Controllers\User\ContestController;
use App\Http\Controllers\User\TransactionController;
use App\Http\Controllers\User\WithdrawRequestController;
use App\Http\Controllers\User\PaymentController;
use App\Http\Controllers\User\ChallengeController;
use App\Http\Controllers\User\UserResultController;
use App\Http\Controllers\User\ComplaintController;
use App\Http\Controllers\User\WebhookController;


use App\Http\Controllers\Auth\AdminLoginController;

use App\Http\Controllers\HomeController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\AdminChallengeController;
use App\Http\Controllers\Admin\RolesController;
use App\Http\Controllers\Admin\PermissionController;
use App\Http\Controllers\Admin\AdminWithdrawRequestController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\EventStageController;
use App\Http\Controllers\Admin\BannerController;
use App\Http\Controllers\Admin\AdminTransactionController;
use App\Http\Controllers\Admin\PayoutController;
use App\Http\Controllers\Admin\AdminComplaintController;
use App\Http\Controllers\Admin\AdminReportController;
use App\Http\Controllers\Admin\ManualPaymentController;
use App\Http\Controllers\Admin\RoomCodeController;
use App\Http\Controllers\Admin\SettingController;
use App\UserData;
use App\Setting;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Route::get('/aa', function () {
//     Artisan::call('make:model Log');
//     dd("Confirm");
// });
Route::any('request_response', 'HomeController@request_response')->name('request_response');
if (Setting::where('id', 3)->first()->field_value == 'yes') {
    Route::get('/{any}', function () {
        return view('maintain');
    })->where('any', '^(?!admin/).*$');
}


// this is done by me
Route::get('/', function (Request $request) {
    if(isset($request->pgstatus)){
        if($request->pgstatus == "success"){
            $payment = new PaymentController();
            $payment->upiStatusCheck();
        }
    }

    $status = Setting::where('id', 3)->first();
    $kyc = 0;

    if (Auth::check()) { // Ensure the user is authenticated
        $user_id = Auth::user()->id;
        $user_kyc = UserData::where('user_id', $user_id)->first();
        if ($user_kyc) {
            $kyc = $user_kyc->verify_status;
        }
    }

    if ($status && $status->field_value == "no") {
        // Logic if status field_value is "no"
    }

    return view('front.welcome', compact('kyc'));
})->name('front.apnaludo');


// this is done by previous developer
// Route::get('/', function (Request $request) {
//     // Handle payment status check if present
//     if (isset($request->pgstatus)) {
//         if ($request->pgstatus == "success") {
//             $payment = new PaymentController();
//             return $payment->upiStatusCheck(); // Return the response from upiStatusCheck
//         } elseif ($request->pgstatus == "fail") {
//             // Handle payment failure logic
//             return view('front.payment-fail'); // Render a payment fail view
//         }
//     }

//     // Other logic
//     $status = Setting::where('id', 3)->first();
//     $kyc = 0;

//     if (Auth::check()) { // Ensure the user is authenticated
//         $user_id = Auth::user()->id;
//         $user_kyc = UserData::where('user_id', $user_id)->first();
//         if ($user_kyc) {
//             $kyc = $user_kyc->verify_status;
//         }
//     }

//     if ($status && $status->field_value == "no") {
//         // Logic if status field_value is "no"
//     }

//     return view('front.welcome', compact('kyc'));
// })->name('front.apnaludo');

Route::get('/int', function () {
    return Hash::make('Ghjyf@#$%^&*85274');
});

Route::get('/about-us', function () {
    return view('front.about-us');
})->name('front.about-us');

Route::get('/how-to-play', function () {
    return view('front.how-to-play');
})->name('front.how-to-play');

Route::get('/referral', function () {
    return view('front.referral');
})->name('front.referral');

Route::get('/contact-us', function () {
    return view('front.contact-us');
})->name('front.contact-us');

Route::get('/responsible-gaming', function () {
    return view('front.responsible-gaming');
})->name('front.responsible-gaming');

Route::get('/terms-and-conditions', function () {
    return view('front.terms-and-conditions');
})->name('front.terms-and-conditions');

Route::get('/privacy-policy', function () {
    return view('front.privacy-policy');
})->name('front.privacy-policy');

Route::get('/game-rules', function () {
    return view('front.game-rules');
})->name('front.game-rules');

Route::get('/openbet', function () {
    return view('user.openbet');
})->name('user.openbet');

Route::get('/runningbet', function () {
    return view('user.runningbet');
})->name('user.runningbet');

Route::get('/refund-and-cancellation-policy', function () {
    return view('front.refund-and-cancellation-policy');
})->name('front.refund-and-cancellation-policy');

Route::get('/create-fake-data', function () {
    Artisan::call('schedule:run');
});
Route::get('/create-fake-data2', [ChallengeController::class, 'create_fakechanllenges']);
Route::post('/roomcode_add', [ChallengeController::class, 'add_rommcode']);
Route::get('/roomcode_automatic_generate/{id}', [ChallengeController::class, 'add_rommcode_automatic']);
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class,'doLoginUser'])->name('login');

Route::any('/payment-gateway-webhook', 'User\PaymentController@paymentGatewayResWebhook');

Route::group(['middleware' => 'auth:web'], function () {
    // logout route
    Route::get('/logout', [LoginController::class, 'logout'])->name('logout');

    Route::get('/ajax_open_battle', [ChallengeController::class, 'ajax_open_battle'])->name('ajax_open_battle');
    Route::get('/ajax_running_battle', [ChallengeController::class, 'ajax_running_battle'])->name('ajax_running_battle');
    Route::get('/profile', [DashboardController::class, 'profile'])->name('profile');
    Route::get('/wallet', [DashboardController::class, 'wallet'])->name('wallet');

    Route::any('/check_aadhar', [KycController::class, 'check_aadhar']);
    Route::any('/complete-kyc/step1', [KycController::class, 'step1'])->name('kyc.step1');
    Route::any('/complete-kyc/save-step-1', [KycController::class, 'saveStep1'])->name('kyc.saveStep1');
    Route::any('/complete-kyc/step2', [KycController::class, 'step2'])->name('kyc.step2');
    Route::any('/complete-kyc/save-step-2', [KycController::class, 'saveStep2'])->name('kyc.saveStep2');
    Route::any('/complete-kyc/step3', [KycController::class, 'step3'])->name('kyc.step3');
    Route::any('/complete-kyc/save-step3', [KycController::class, 'saveStep3'])->name('kyc.saveStep3');
    Route::any('/complete-kyc/kyc-submit', [KycController::class, 'kyc_submit'])->name('kyc.kyc_submit');
    Route::any('/complete-kyc/approve', [KycController::class, 'kyc_approve']);




    //Contest Module
    // Route::get('/contests', [ContestController::class],'index')->name('contests');
    // Route::get('/contest-results', [ContestController::class],'contestResults')->name('contest-results');
    // Route::get('/join-contest', [ContestController::class],'joinContest')->name('join-contest');
    // Route::get('/contest/{slug}', [ContestController::class],'contestDetails');
    // Route::get('/contest/{slug}/{rcode}', [ContestController::class],'contestRoomCode');
    // Route::get('/contest-submit-result', [ContestController::class],'submitResult')->name('contest-submit-result');
    // Route::get('/contests', [ContestController::class],'index')->name('contests');
    Route::get('/get-user-details', [UserController::class, 'getUserDetails']);

    //Paykun gateway
    Route::get('/add-money-chk', [PaymentController::class, 'addMoneyChk'])->name('add-money-chk');
    Route::post('/add-money-chk', [PaymentController::class, 'createOrderChk'])->name('add-money-chk');
    Route::get('/add-money', [PaymentController::class, 'addMoney'])->name('add-money');
    Route::post('/add-money', [PaymentController::class, 'createOrder'])->name('add-money');
    // Route::post('/add-money-phonepe', [PaymentController::class, 'createOrdernew'])->name('add-money-new');
    Route::get('/payment-gateway-paykun-ok', [PaymentController::class, 'paymentGatewayPaykunPostSuccess'])->name('payment-gateway-paykun-ok');
    Route::get('/payment-gateway-paykun-fail', [PaymentController::class, 'paymentGatewayPaykunPostFail'])->name('payment-gateway-paykun-fail');
    Route::get('/upi-gateway-res', [PaymentController::class, 'upiGatewayRes'])->name('upi-gateway-res');
    Route::post('/upi-gateway-res-post', [PaymentController::class, 'upiGatewayResPost'])->name('upi-gateway-res-post');

    Route::post('/new-upi-gateway-response', [PaymentController::class, 'upitel_recharge_status'])->name('new-upi-gateway-response');

    // Route for adding money via PhonePe
    Route::post('/add-money-phonepe', [PaymentController::class, 'createOrdernew'])->name('add-money-new');

    // Route for Paytm callback
    Route::post('/paytm-callback', [PaymentController::class, 'paytmCallback']);

    //Cashfree gateway
    Route::post('/payment-gateway-cashfree-res', 'User\PaymentController@paymentGatewayRes')->name('payment-gateway-cashfree-res');
    Route::post('/webhook-response', 'User\WebhookController@paymentGatewayRes')->name('webhook-response');

    //Withdraw request
    Route::get('/withdraw-request', [WithdrawRequestController::class, 'index'])->name('withdraw-request');

    Route::get('/upi-withdraw', [WithdrawRequestController::class, 'upiWithdraw'])->name('upi-withdraw');
    Route::post('/upi-withdraw', [WithdrawRequestController::class, 'upiWithdrawPost'])->name('upi-withdraw');
    Route::post('/check-upi', [WithdrawRequestController::class, 'checkupis'])->name('check-upi');

    Route::get('/bank-withdraw', [WithdrawRequestController::class, 'bankWithdraw'])->name('bank-withdraw');
    Route::post('/bank-withdraw', [WithdrawRequestController::class, 'bankWithdrawPost'])->name('bank-withdraw');

    // these are the new routes for the withdrawl process
    Route::get('/universal-withdraw', [WithdrawRequestController::class,'universalWithdraw']);
    Route::post('/universal-withdraw', [WithdrawRequestController::class,'POSTuniversalWithdraw'])->name('universal-withdraw');
    Route::get('/universal-withdraw', [WithdrawRequestController::class,'universalWithdraw']);
    Route::post('/universal-withdraw', [WithdrawRequestController::class,'POSTuniversalWithdraw'])->name('universal-withdraw');
    Route::get('/edit-bank', [WithdrawRequestController::class,'editBankDetail']);
    Route::get('/editBankDeatil/{type}', [WithdrawRequestController::class,'editBankTypeDetail']);
    Route::post('/editBankDeatil/{type}', [WithdrawRequestController::class,'POSTeditBankTypeDetail']);


    Route::get('/transactions', [TransactionController::class, 'index'])->name('transactions');
    Route::get('/referral-history', [TransactionController::class, 'referral'])->name('referral-history');
    Route::get('/game-history', [TransactionController::class, 'gameHistory'])->name('game-history');
    Route::get('/leaders', [TransactionController::class, 'leaderBord'])->name('leaders');
    //Challenges Routes
    Route::get('/challenges', [ChallengeController::class, 'index'])->name('challenges');
    Route::get('/challenge-listing', [ChallengeController::class, 'challengeListing'])->name('challenge-listing');
    Route::get('/dashboard/challenges', [ChallengeController::class, 'index']);
    Route::get('/quick-challenges', [ChallengeController::class, 'quickChallenges'])->name('quick-challenges');
    Route::post('/cancel-challenge', [ChallengeController::class, 'cancelChallenge'])->name('cancel-challenge');
    Route::post('/play-challenge', [ChallengeController::class, 'playChallenge'])->name('play-challenge');
    Route::post('/start-challenge', [ChallengeController::class, 'startChallenge'])->name('start-challenge');
    Route::post('/accept-challenge', [ChallengeController::class, 'acceptChallenge'])->name('accept-challenge');
    Route::post('/deny-challenge', [ChallengeController::class, 'cancelChallengeReq'])->name('deny-challenge');
    Route::post('/chk-room-id', [UserResultController::class, 'roomCode'])->name('chk-room-id');
    Route::post('/submit-result', [UserResultController::class, 'create'])->name('submit-result');
    Route::post('/cancel-challenge-req', [ChallengeController::class, 'cancelChallengeReq'])->name('cancel-challenge-req');
    Route::post('/create-challenge', [ChallengeController::class, 'create'])->name('create-challenge');
    Route::post('/get-room-code', [UserResultController::class, 'getRoomCode'])->name('get-room-code');
    Route::get('/challenge-detail/{chid}', [UserResultController::class, 'challengeDetail'])->name('challenge-detail');
    Route::post('/get-room-code', [UserResultController::class, 'getRoomCode'])->name('get-room-code');
    Route::get('/get-room-code-chk', [UserResultController::class, 'getRoomCodeChk'])->name('get-room-code-chk');
    Route::get('/challenge-detail-chk/{chid}', [UserResultController::class, 'challengeDetailChk'])->name('challenge-detail-chk');

    Route::post('/change-unique-id', [DashboardController::class, 'changeUniqueId'])->name('change-unique-id');
    Route::post('/use-referral-code', [DashboardController::class, 'useReferralCode'])->name('use-referral-code');
    Route::get('/reffer-earn', [DashboardController::class, 'referralCode'])->name('reffer-earn');

    Route::get('/prizes', [PaymentController::class, 'prizes'])->name('prizes');
    Route::get('/prize-results', [PaymentController::class], 'prizeResults')->name('prize-results');

    Route::get('/support', [ComplaintController::class, 'support'])->name('support');
    Route::post('/support', [ComplaintController::class, 'create'])->name('support');
    Route::get('/notification', [ComplaintController::class, 'notification'])->name('notification');
    Route::get('/legal', [ComplaintController::class, 'legal'])->name('legal');

});

//--------- Admin Routes -----------//
Route::get('admin/login', [AdminLoginController::class, 'showLoginForm'])->name('admin-login');
Route::post('admin/login', [AdminLoginController::class, 'postLogin'])->name('admin-login');


Route::group(['middleware' => 'auth'], function () {
    // logout route
    Route::get('/logout', [LoginController::class, 'logout'])->name('logout');
    Route::get('/clear-cache', [HomeController::class, 'clearCache']);


    //kyc controller

    Route::get('/admin/kyc-pending', 'Admin\KYCController@kyc_pending');
    Route::get('/admin/kyc-details/{id}', 'Admin\KYCController@kyc_view');
    Route::get('/admin/kyc-approved', 'Admin\KYCController@kyc_approved');
    Route::get('/admin/kyc-verify/{id}', 'Admin\KYCController@kyc_verify');
    Route::get('/admin/kyc-reject/{id}', 'Admin\KYCController@kyc_rejected');


    // dashboard route
    Route::group(['middleware' => 'can:access_dashboard', 'prefix' => 'admin'], function () {
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('admin-dashboard');
        Route::get('/dashboard/get-income-date-wise', [AdminDashboardController::class, 'getIncomeDateWise']);
    });
    Route::group(['middleware' => 'can:access_admin', 'prefix' => 'admin'], function () {
        Route::get('/admin', [AdminController::class, 'index']);
        Route::get('/admin/get-list', [AdminController::class, 'getAdminList']);
        Route::any('/admin/create', [AdminController::class, 'create']);
        Route::any('/admin/store', [AdminController::class, 'store']);
        Route::get('/admin/edit/{id}', [AdminController::class, 'edit']);
        Route::post('/admin/update', [AdminController::class, 'update']);
        Route::get('/admin/delete/{id}', [AdminController::class, 'delete']); });

    //only those have manage_user permission will get access
    Route::group(['middleware' => 'can:manage_user', 'prefix' => 'admin'], function () {
        Route::get('/users', [UserController::class, 'index']);
        Route::get('/user/create', [UserController::class, 'create'])->name('add-user');
        Route::get('/user/edit/{id}', [UserController::class, 'edit'])->name('edit-user');
        Route::post('/user/store', [UserController::class, 'store'])->name('create-user');
        Route::post('/user/update', [UserController::class, 'update'])->name('update-user');
        Route::get('/user/get-list', [UserController::class, 'getUserList']);
        Route::get('/user/status/{status}/{uid}', [UserController::class, 'changeStatus']);
        Route::get('/user/delete/{id}', [UserController::class, 'delete']);
        Route::get('/user/statement/{id}', [UserController::class, 'statement']);
        Route::get('/user/wallet/{id}', [UserController::class, 'wallet'])->name('update-wallet');
        Route::post('/user/update-wallet', [UserController::class, 'updateWallet'])->name('update-wallet');
    });

    //only those have manage_user permission will get access
    Route::group(['middleware' => 'can:manage_challenge', 'prefix' => 'admin'], function () {
        Route::get('/challenges', [AdminChallengeController::class, 'index']);
        Route::get('/user/status/{status}/{uid}', [AdminChallengeController::class, 'changeStatus']);
        Route::get('/user/delete/{id}', [AdminChallengeController::class, 'delete']);
        Route::get('/challenge/{id}', [AdminChallengeController::class, 'details']);

        Route::post('/cancel-game', [AdminChallengeController::class, 'cancelGame'])->name('cancel-admin-game');
        Route::post('/set-game-winner', [AdminChallengeController::class, 'gameWinner'])->name('set-game-winner');
        Route::get('challenge/make-winner/{chid}/{uid}', [AdminChallengeController::class, 'makeWinner']);

        Route::get('/challenge/roomcode/{id}', [AdminChallengeController::class, 'roomCode']);
        Route::post('/challenge/update-roomcode', [AdminChallengeController::class, 'updateRoomCode'])->name('update-roomcode');
    });

    //only those have manage_role permission will get access
    Route::group(['middleware' => 'can:manage_role|manage_user', 'prefix' => 'admin'], function () {
        Route::get('/roles', [RolesController::class, 'index']);
        Route::get('/role/get-list', [RolesController::class, 'getRoleList']);
        Route::post('/role/create', [RolesController::class, 'create']);
        Route::get('/role/edit/{id}', [RolesController::class, 'edit']);
        Route::post('/role/update', [RolesController::class, 'update']);
        Route::get('/role/delete/{id}', [RolesController::class, 'delete']);
    });


    //only those have manage_permission permission will get access
    Route::group(['middleware' => 'can:manage_permission|manage_user', 'prefix' => 'admin'], function () {
        Route::get('/permission', [PermissionController::class, 'index']);
        Route::get('/permission/get-list', [PermissionController::class, 'getPermissionList']);
        Route::post('/permission/create', [PermissionController::class, 'create']);
        Route::get('/permission/update', [PermissionController::class, 'update']);
        Route::get('/permission/delete/{id}', [PermissionController::class, 'delete']);
    });

    // get permissions
    Route::get('admin/get-role-permissions-badge', [PermissionController::class, 'getPermissionBadgeByRole']);

    //only those have manage_transaction permission will get access
    Route::group(['middleware' => 'can:manage_transaction', 'prefix' => 'admin'], function () {
        Route::get('/transactions', [AdminTransactionController::class, 'index']);
        Route::get('/transactions/approve/{id}', [AdminTransactionController::class, 'approveTxn']);
    });

    //only those have manage_transaction permission will get access
    Route::group(['middleware' => 'can:manage_manual_payment', 'prefix' => 'admin'], function () {
        Route::get('/manual-payments', [ManualPaymentController::class, 'index']);
    });

    Route::group(['middleware' => 'can:manage_withdraw', 'prefix' => 'admin'], function () {
        Route::get('/withdraw-requests', [AdminWithdrawRequestController::class, 'index'])->name('withdraw-requests');
        Route::get('/accept-request/{rid}', [AdminWithdrawRequestController::class, 'acceptRequest'])->name('accept-request');
        Route::get('/accept-request-mannual/{rid}', [AdminWithdrawRequestController::class, 'acceptRequestMannual'])->name('accept-request-mannual');

        Route::get('/decline-request/{rid}', [AdminWithdrawRequestController::class, 'declineRequest'])->name('decline-request');
    });

    Route::group(['middleware' => 'can:manage_complaint', 'prefix' => 'admin'], function () {
        Route::get('/user-complaints', [AdminComplaintController::class, 'index'])->name('user-complaints');
        Route::get('/complaint-action/{rid}', [AdminComplaintController::class, 'complaintAction'])->name('complaint-action');
    });

    Route::group(['middleware' => 'can:manage_report', 'prefix' => 'admin'], function () {
        Route::get('/reports', [AdminReportController::class, 'index']);
    });

    Route::group(['middleware' => 'can:manage_roomcode', 'prefix' => 'admin'], function () {
        Route::get('/room-codes', [RoomCodeController::class, 'index']);
        Route::get('/room-code/create', [RoomCodeController::class, 'create'])->name('add-room-code');
        Route::post('/room-code/create', [RoomCodeController::class, 'store'])->name('create-room-code');
        Route::get('/room-code/status/{status}/{id}', [RoomCodeController::class, 'changeStatus']);
    });

    Route::group(['middleware' => 'can:manage_setting', 'prefix' => 'admin'], function () {
        Route::get('/settings', [SettingController::class, 'index']);
        Route::post('/settings/update', [SettingController::class, 'update'])->name('update-settings');
    });

    Route::get('/ajax_chalange', [ChallengeController::class, 'ajax_chalange']);
});

