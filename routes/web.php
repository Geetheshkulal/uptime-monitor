<?php
use App\Http\Controllers\ActivityController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\BillingController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\PremiumPageController;
use App\Http\Controllers\PushNotificationController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\RolePermissionController;
use App\Http\Controllers\TrackingController;
use App\Http\Controllers\IncidentController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\MonitoringController;
use App\Http\Controllers\UserController;
use App\Models\Subscriptions;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SslCheckController;
use App\Http\Controllers\DnsController;
use App\Http\Controllers\PingMonitoringController;
use App\Http\Controllers\PortMonitorController;
use App\Http\Controllers\HttpMonitoringController;
use App\Http\Controllers\CashFreePaymentController;
use App\Http\Controllers\PlanSubscriptionController;
use Illuminate\Http\Request;


Route::get('/', function()
{
    $plans = Subscriptions::all();
    return view('welcome', compact('plans'));
});

Route::get('latestUpdates',function(){return view('pages.latestUpdates');})->name('latest.page');

Route::get('documentation',function()
{
    $plans = Subscriptions::all();
    return view('pages.documentation', compact('plans'));
})->name('documentation.page');


Route::post('/email/verification-notification',function (Request $request) {
    $request->user()->sendEmailVerificationNotification();

    return back()->with('message','Verification Email Sent.');

})->middleware(['auth','throttle:6,1'])->name('verification.send');



Route::middleware(['auth','verified','CheckUserSession'])->group(function () {
    
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/dashboard', [MonitoringController::class, 'MonitoringDashboard'])->middleware('role:user')->name('monitoring.dashboard');
    Route::get('/dashboard/{id}',[TrackingController::class,'NotificationTracker']);
    Route::get('/monitoring/dashboard/update', [MonitoringController::class, 'MonitoringDashboardUpdate'])->name('monitoring.dashboard.update');


    Route::get('/monitoring/add', [MonitoringController::class, 'AddMonitoring'])->middleware('monitor.limit')->name('add.monitoring');
    Route::get('/monitoring/display/{id}/{type}', [MonitoringController::class, 'MonitoringDisplay'])->middleware('monitor.access')->name('display.monitoring');
    Route::get('/monitoring/chart/update/{id}/{type}', [MonitoringController::class, 'MonitoringChartUpdate'])->name('display.chart.update');

    // for delete and edit monitoring
    Route::get('/monitoring/delete/{id}',[MonitoringController::class,'MonitorDelete'])->name('monitoring.delete');
    Route::post('/monitoring/edit/{id}', [MonitoringController::class,'MonitorEdit'])->name('monitoring.update');
    Route::post('/monitor/pause/{id}', [MonitoringController::class, 'pauseMonitor'])->name('monitor.pause');
});

Route::middleware(['auth','verified','CheckUserSession'])->group(function () {

    Route::get('/ssl-check', [SslCheckController::class, 'index'])->middleware('premium_middleware')->name('ssl.check');
    Route::get('/ssl/history', [SslCheckController::class, 'history'])->middleware('premium_middleware')->name('ssl.history');
    Route::post('/ssl-check', [SslCheckController::class, 'check'])->middleware('premium_middleware')->name('ssl.check.domain');

    Route::get('/incidents', [IncidentController::class, 'incidents'])->name('incidents');
    Route::get('/incidents/fetch', [IncidentController::class, 'fetchIncidents'])->name('incidents.fetch'); // Add this for AJAX
    Route::get('/plan-subscription', [PlanSubscriptionController::class, 'planSubscription'])->name('planSubscription');
    Route::post('/dns-check', [DnsController::class, 'checkDnsRecords']);
    Route::post('/add/dns', [DnsController::class,'AddDNS'])->name('add.dns');
    Route::post('/monitoring/ping', [PingMonitoringController::class, 'store'])->name('ping.monitoring.store');


    // for port 
    Route::post('/monitor/port',[PortMonitorController::class,'PortStore'])->name('monitor.port');
    Route::post('/monitoring/http', [HttpMonitoringController::class, 'store'])->name('monitoring.http.store');

    Route::get('cashfree/payments/create', [CashFreePaymentController::class, 'create'])->name('callback');
    Route::post('cashfree/payments/store', [CashFreePaymentController::class, 'store'])->name('store');
    Route::any('cashfree/payments/success', [CashFreePaymentController::class, 'success'])->name('success');
    Route::get('premium',[PremiumPageController::class,'PremiumPage'])->name('premium.page');
});



Route::group(['middleware' => ['auth']], function () {
    // Routes accessible only by superadmin
    Route::get('/admin/dashboard',[AdminController::class,'AdminDashboard'])->middleware('role:superadmin')->name('admin.dashboard');
    Route::get('/admin/display/users', action: [UserController::class,'DisplayUsers'])->middleware('permission:see.users')->name('display.users');
    Route::get('/admin/display/roles', [RoleController::class,'DisplayRoles'])->middleware('permission:see.roles')->name('display.roles');
    Route::get('/admin/display/permissions', [PermissionController::class, 'DisplayPermissions'])->middleware('role:superadmin')->name('display.permissions');
    Route::get('/admin/display/user/{id}', action: [UserController::class,'ShowUser'])->middleware('permission:see.users')->name('show.user');

    // Route::get('/admin/users', [AdminController::class, 'AddUser'])->name('add.user.form');
    Route::post('/admin/add/users', [UserController::class, 'storeUser'])->middleware('permission:add.user')->name('add.user');
    Route::get('/admin/edit/user/{id}', [UserController::class, 'EditUsers'])->middleware('permission:edit.user')->name('edit.user');
    Route::put('/admin/edit/user/{id}', [UserController::class, 'UpdateUsers'])->middleware('permission:edit.user')->name('update.user');
    Route::delete('/admin/delete/user/{id}', [UserController::class, 'DeleteUser'])->middleware('permission:delete.user')->name('delete.user');

    Route::get('/admin/add/roles', [RoleController::class, 'AddRole'])->middleware(middleware: 'permission:add.role')->name('add.role');
    Route::post('/roles', [RoleController::class, 'StoreRole'])->middleware('permission:add.role')->name('store.role');

    Route::get('/admin/delete/role/{id}', [RoleController::class, 'DeleteRole'])->middleware('permission:delete.role')->name('delete.role');
    Route::get('/admin/edit/role/{id}', [RoleController::class, 'EditRole'])->middleware('permission:edit.role')->name('edit.role');
    Route::put('/admin/update/role/{id}', [RoleController::class, 'UpdateRole'])->middleware('permission:edit.role')->name('update.role');


    Route::get('admin/add/permission', [PermissionController::class, 'AddPermission'])->middleware('role:superadmin')->name('add.permission');
    Route::post('admin/store/permission', [PermissionController::class, 'StorePermission'])->middleware('role:superadmin')->name('store.permission');

    Route::get('/admin/delete/permission/{id}', [PermissionController::class, 'DeletePermission'])->middleware('role:superadmin')->name('delete.permission');
    Route::get('/admin/display/activity', [ActivityController::class,'DisplayActivity'])->middleware('permission:see.activity')->name('display.activity');

    
    Route::get('/admin/edit/permissions/{id}', [PermissionController::class, 'EditPermission'])->middleware('role:superadmin')->name('edit.permission');
    Route::put('/admin/update/permissions/{id}', [PermissionController::class, 'UpdatePermission'])->middleware('role:superadmin')->name('update.permission');

    Route::get('/roles/{id}/permissions', [RolePermissionController::class, 'EditRolePermissions'])->middleware('permission:edit.role.permissions')->name('edit.role.permissions');
    Route::post('/roles/{id}/permissions', [RolePermissionController::class, 'UpdateRolePermissions'])->middleware('permission:edit.role.permissions')->name('update.role.permissions');


    Route::get('/billing',[BillingController::class,'Billing'])->middleware('role:superadmin')->name('billing');
    Route::post('/edit/billing/{id}',[BillingController::class,'EditBilling'])->middleware('role:superadmin')->name('edit.billing');

    
});


Route::post('/subscribe', [PushNotificationController::class , 'subscribe']);


Route::get('/track/{token}.png', [TrackingController::class, 'pixel'])->withoutMiddleware(['web', 'verified', 'auth', \App\Http\Middleware\VerifyCsrfToken::class]);


require __DIR__.'/auth.php';

