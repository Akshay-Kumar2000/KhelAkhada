<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Admin\UserController;

use App\Http\Controllers\Admin\EventController;
use App\Http\Controllers\Admin\RolesController;
use App\Http\Controllers\Admin\PermissionController;
use App\Http\Controllers\Admin\CreatorController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\EventStageController;
use App\Http\Controllers\Admin\BannerController;
use App\Http\Controllers\Admin\OrganiserController;
use App\Http\Controllers\Admin\TransactionController;
use App\Http\Controllers\Admin\SeatCategoryController;
use App\Http\Controllers\Admin\StageSeatSettingController;

use App\Http\Controllers\Admin\PayoutController;
use App\Http\Controllers\Admin\AdminReportController;

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
Route::get('/', [HomeController::class,'index']);
Route::get('/get-schedule-streams', [HomeController::class,'scheduleStreams']);

Route::get('admin/login', [LoginController::class,'showLoginForm'])->name('login');
Route::post('admin/login', [LoginController::class,'login']);
Route::post('register', [RegisterController::class,'register']);

Route::get('password/forget',  function () { 
	return view('pages.forgot-password'); 
})->name('password.forget');
Route::post('password/email', [ForgotPasswordController::class,'sendResetLinkEmail'])->name('password.email');
Route::get('password/reset/{token}', [ResetPasswordController::class,'showResetForm'])->name('password.reset');
Route::post('password/reset', [ResetPasswordController::class,'reset'])->name('password.update');


Route::group(['middleware' => 'auth'], function(){
	// logout route
	Route::get('/logout', [LoginController::class,'logout']);
	Route::get('/clear-cache', [HomeController::class,'clearCache']);

	// dashboard route  
	Route::group(['middleware' => 'can:access_dashboard', 'prefix' => 'admin'], function(){
		Route::get('/dashboard', [DashboardController::class,'index'])->name('dashboard');
		Route::get('/dashboard/get-income-date-wise', [DashboardController::class,'getIncomeDateWise']);
	});

	//only those have manage_user permission will get access
	Route::group(['middleware' => 'can:manage_user', 'prefix' => 'admin'], function(){
		Route::get('/users', [UserController::class,'index']);
		Route::get('/user/get-list', [UserController::class,'getUserList']);
		Route::get('/user/status/{status}/{uid}', [UserController::class,'changeStatus']);
		Route::get('/user/delete/{id}', [UserController::class,'delete']);
		Route::get('/user/statement/{id}', [UserController::class,'statement']);
	});

	//only those have manage_creator permission will get access
	Route::group(['middleware' => 'can:manage_creator', 'prefix' => 'admin'], function(){
		Route::get('/creators', [CreatorController::class,'index']);
		Route::get('/creator/get-list', [CreatorController::class,'getCreatorList']);
		Route::get('/creator/create', [CreatorController::class,'create']);
		Route::post('/creator/create', [CreatorController::class,'store'])->name('create-creator');
		Route::get('/creator/{id}', [CreatorController::class,'edit']);
		Route::post('/creator/update', [CreatorController::class,'update']);
		Route::get('/creator/settings/{id}', [CreatorController::class,'settings']);
		Route::post('/creator/update-settings', [CreatorController::class,'updateSettings']);
		Route::get('/creator/profile/{id}', [CreatorController::class,'userProfile']);
		Route::get('/creator/revenue/{id}', [CreatorController::class,'revenue']);
		
		Route::get('/creator/status/{status}/{uid}', [CreatorController::class,'changeStatus']);
		Route::get('/creator/payout/{id}', [PayoutController::class,'index']);
		Route::post('/creator/add-payout', [PayoutController::class,'create']);
		
	});
Route::post('/save-token', [App\Http\Controllers\HomeController::class, 'saveToken'])->name('save-token');

Route::post('/send-notification', [App\Http\Controllers\HomeController::class, 'sendNotification'])->name('send.notification');
	//only those have creator_payout permission will get access
	Route::group(['middleware' => 'can:creator_payout', 'prefix' => 'admin'], function(){
		Route::get('/creator/payout/{id}', [PayoutController::class,'index']);
		Route::post('/creator/add-payout', [PayoutController::class,'create']);
	});

	//only those have manage_role permission will get access
	Route::group(['middleware' => 'can:manage_role|manage_user', 'prefix' => 'admin'], function(){
		Route::get('/roles', [RolesController::class,'index']);
		Route::get('/role/get-list', [RolesController::class,'getRoleList']);
		Route::post('/role/create', [RolesController::class,'create']);
		Route::get('/role/edit/{id}', [RolesController::class,'edit']);
		Route::post('/role/update', [RolesController::class,'update']);
		Route::get('/role/delete/{id}', [RolesController::class,'delete']);
	});


	//only those have manage_permission permission will get access
	Route::group(['middleware' => 'can:manage_permission|manage_user', 'prefix' => 'admin'], function(){
		Route::get('/permission', [PermissionController::class,'index']);
		Route::get('/permission/get-list', [PermissionController::class,'getPermissionList']);
		Route::post('/permission/create', [PermissionController::class,'create']);
		Route::get('/permission/update', [PermissionController::class,'update']);
		Route::get('/permission/delete/{id}', [PermissionController::class,'delete']);
	});

	// get permissions
	Route::get('admin/get-role-permissions-badge', [PermissionController::class,'getPermissionBadgeByRole']);

	//only those have manage_event permission will get access
	Route::group(['middleware' => 'can:manage_event', 'prefix' => 'admin'], function(){
		Route::get('/events', [EventController::class,'index'])->name('events');
		Route::get('/event/create', [EventController::class,'create']);
		Route::get('/get-stage-categories/{sid}', [EventStageController::class,'categories']);
		Route::post('/event/create-event', [EventController::class,'store'])->name('create-event');
		
		Route::get('/event/stop-event/{eid}', [EventController::class,'forceStop']);
		Route::get('/event/detail/{id}', [EventController::class,'details']);
	});

	//only those have manage_banner permission will get access
	Route::group(['middleware' => 'can:manage_banner', 'prefix' => 'admin'], function(){
		Route::get('/banners', [BannerController::class,'index'])->name('banners');
		Route::get('/banner/create', [BannerController::class,'create']);
		Route::post('/banner/create-banner', [BannerController::class,'store'])->name('create-banner');
		Route::get('/banner/status/{status}/{uid}', [BannerController::class,'changeStatus']);
		Route::get('/banner/edit/{uid}', [BannerController::class,'edit']);
		Route::post('/banner/edit-banner', [BannerController::class,'update'])->name('edit-banner');
	});

	//only those have manage_organiser permission will get access
	Route::group(['middleware' => 'can:manage_organiser', 'prefix' => 'admin'], function(){
		Route::get('/organisers', [OrganiserController::class,'index'])->name('organisers');
		Route::get('/mails', [OrganiserController::class,'mails'])->name('mails');
		Route::get('/organiser/create', [OrganiserController::class,'create']);
		Route::post('/organiser/create-organiser', [OrganiserController::class,'store'])->name('create-organiser');
		Route::get('/organiser/status/{status}/{uid}', [OrganiserController::class,'changeStatus']);
		Route::get('/organiser/edit/{uid}', [OrganiserController::class,'edit']);
		Route::post('/organiser/edit-organiser', [OrganiserController::class,'update'])->name('edit-organiser');
	});

	//only those have manage_stage permission will get access
	Route::group(['middleware' => 'can:manage_stage', 'prefix' => 'admin'], function(){
		Route::get('/stages', [EventStageController::class,'index'])->name('stages');
		Route::get('/stage/create', [EventStageController::class,'create']);
		Route::post('/stage/create-organiser', [EventStageController::class,'store'])->name('create-stage');
		Route::get('/stage/status/{status}/{uid}', [EventStageController::class,'changeStatus']);
		Route::get('/stage/edit/{uid}', [EventStageController::class,'edit']);
		Route::post('/stage/edit-stage', [EventStageController::class,'update'])->name('edit-stage');

		Route::get('/stage/settings/{uid}', [StageSeatSettingController::class,'settings']);
		Route::post('/stage/update-setting', [StageSeatSettingController::class,'update'])->name('update-setting');
	});

	//only those have manage_seat_category permission will get access
	Route::group(['middleware' => 'can:manage_seat_category', 'prefix' => 'admin'], function(){
		Route::get('/seat-categories', [SeatCategoryController::class,'index'])->name('seat-categories');
		Route::get('/seat-category/create', [SeatCategoryController::class,'create']);
		Route::post('/seat-category/create-seat-category', [SeatCategoryController::class,'store'])->name('create-seat-category');
		Route::get('/seat-category/status/{status}/{uid}', [SeatCategoryController::class,'changeStatus']);
		Route::get('/seat-category/edit/{uid}', [SeatCategoryController::class,'edit']);
		Route::post('/seat-category/edit-seat-category', [SeatCategoryController::class,'update'])->name('edit-seat-category');
	});

	//only those have manage_transaction permission will get access
	Route::group(['middleware' => 'can:manage_transaction', 'prefix' => 'admin'], function(){
		Route::get('/transactions', [TransactionController::class,'index'])->name('transactions');
	});






	// permission examples
    Route::get('/admin/permission-example', function () {
    	return view('admin.permission/permission-example'); 
    });
    // API Documentation
    Route::get('/rest-api', function () { return view('api'); });
    // Editable Datatable
	Route::get('/table-datatable-edit', function () { 
		return view('pages.datatable-editable'); 
	});

    // Themekit demo pages
	Route::get('/calendar', function () { return view('pages.calendar'); });
	Route::get('/charts-amcharts', function () { return view('pages.charts-amcharts'); });
	Route::get('/charts-chartist', function () { return view('pages.charts-chartist'); });
	Route::get('/charts-flot', function () { return view('pages.charts-flot'); });
	Route::get('/charts-knob', function () { return view('pages.charts-knob'); });
	Route::get('/forgot-password', function () { return view('pages.forgot-password'); });
	Route::get('/form-addon', function () { return view('pages.form-addon'); });
	Route::get('/form-advance', function () { return view('pages.form-advance'); });
	Route::get('/form-components', function () { return view('pages.form-components'); });
	Route::get('/form-picker', function () { return view('pages.form-picker'); });
	Route::get('/invoice', function () { return view('pages.invoice'); });
	Route::get('/layout-edit-item', function () { return view('pages.layout-edit-item'); });
	Route::get('/layouts', function () { return view('pages.layouts'); });

	Route::get('/navbar', function () { return view('pages.navbar'); });
	Route::get('/profile', function () { return view('pages.profile'); });
	Route::get('/project', function () { return view('pages.project'); });
	Route::get('/view', function () { return view('pages.view'); });

	Route::get('/table-bootstrap', function () { return view('pages.table-bootstrap'); });
	Route::get('/table-datatable', function () { return view('pages.table-datatable'); });
	Route::get('/taskboard', function () { return view('pages.taskboard'); });
	Route::get('/widget-chart', function () { return view('pages.widget-chart'); });
	Route::get('/widget-data', function () { return view('pages.widget-data'); });
	Route::get('/widget-statistic', function () { return view('pages.widget-statistic'); });
	Route::get('/widgets', function () { return view('pages.widgets'); });

	// themekit ui pages
	Route::get('/alerts', function () { return view('pages.ui.alerts'); });
	Route::get('/badges', function () { return view('pages.ui.badges'); });
	Route::get('/buttons', function () { return view('pages.ui.buttons'); });
	Route::get('/cards', function () { return view('pages.ui.cards'); });
	Route::get('/carousel', function () { return view('pages.ui.carousel'); });
	Route::get('/icons', function () { return view('pages.ui.icons'); });
	Route::get('/modals', function () { return view('pages.ui.modals'); });
	Route::get('/navigation', function () { return view('pages.ui.navigation'); });
	Route::get('/notifications', function () { return view('pages.ui.notifications'); });
	Route::get('/range-slider', function () { return view('pages.ui.range-slider'); });
	Route::get('/rating', function () { return view('pages.ui.rating'); });
	Route::get('/session-timeout', function () { return view('pages.ui.session-timeout'); });
	Route::get('/pricing', function () { return view('pages.pricing'); });


	// new inventory routes
	Route::get('/pos', function () { return view('inventory.pos'); });
	Route::get('/products', function () { return view('inventory.product.list'); });
	Route::get('/products/create', function () { return view('inventory.product.create'); }); 
	Route::get('/categories', function () { return view('inventory.category.index'); }); 
	Route::get('/sales', function () { return view('inventory.sale.list'); });
	Route::get('/sales/create', function () { return view('inventory.sale.create'); }); 
	Route::get('/purchases', function () { return view('inventory.purchase.list'); });
	Route::get('/purchases/create', function () { return view('inventory.purchase.create'); }); 
	Route::get('/customers', function () { return view('inventory.people.customers'); }); 
	Route::get('/suppliers', function () { return view('inventory.people.suppliers'); }); 
	
});


Route::get('/register', function () { return view('pages.register'); });
Route::get('/login-1', function () { return view('pages.login'); });
