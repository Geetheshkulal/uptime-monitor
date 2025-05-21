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
use App\Http\Controllers\SslCheckController;
use App\Http\Controllers\DnsController;
use App\Http\Controllers\PingMonitoringController;
use App\Http\Controllers\PortMonitorController;
use App\Http\Controllers\HttpMonitoringController;
use App\Http\Controllers\CashFreePaymentController;
use App\Http\Controllers\PlanSubscriptionController;
// for ticket and comments
use App\Http\Controllers\TicketController;
use App\Http\Controllers\BlockController;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Http\Controllers\Auth\EmailVerificationPromptController;
use App\Http\Controllers\Auth\VerifyEmailController;
use App\Http\Controllers\Auth\EmailVerificationNotificationController;

use App\Http\Controllers\StatusPageController;
use App\Http\Controllers\PublicStatusPageController;
use App\Http\Controllers\CouponController;
use App\Http\Controllers\ChangelogController;
use App\Http\Controllers\TrafficLogController;

Route::get('/Product_documentation', function () {
    return view('pages.CheckMySiteDocumentation');
})->name('product.documentation')->middleware('blockIp');


Route::get('/status',[StatusPageController::class,'index'])->middleware('role:user|subuser')->middleware('permission:see.statuspage')->name('status');

Route::get('/status-page/{hash}', [PublicStatusPageController::class, 'show'])  
     ->name('public.status');
     Route::prefix('user')->group(function () {
        Route::get('/status-settings', [UserController::class, 'statusPageSettings'])
             ->name('user.status-settings');
        Route::post('/status-settings', [UserController::class, 'updateStatusPageSettings'])
             ->name('user.status-settings.update');
    });
Route::middleware('auth','blockIp')->group(function () {
    Route::get('verify-email', EmailVerificationPromptController::class)
                ->name('verification.notice');

    Route::post('email/verification-notification', [EmailVerificationNotificationController::class, 'store'])
                ->middleware('throttle:6,1')
                ->name('verification.send');

});

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


// email verify and register route
Route::post('/email/verification-notification',function (Request $request) {
    $request->user()->sendEmailVerificationNotification();

    return back()->with('message','Verification Email Sent.');
})->middleware(['auth','throttle:6,1'])->name('verification.send');
// end



Route::get('/changelog',[ChangelogController::class,'ChangelogPage']);

Route::middleware(['auth','verified','CheckUserSession','blockIp'])->group(function () {
    
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->middleware('role:user')->name('profile.destroy');

    Route::get('/dashboard', [MonitoringController::class, 'MonitoringDashboard'])->middleware('role:user|subuser')->middleware('permission:see.monitors')->name('monitoring.dashboard');
    Route::get('/dashboard/{id}',[TrackingController::class,'NotificationTracker']);
    Route::get('/monitoring/dashboard/update', [MonitoringController::class, 'MonitoringDashboardUpdate'])->name('monitoring.dashboard.update');


    Route::get('/monitoring/add', [MonitoringController::class, 'AddMonitoring'])->middleware('monitor.limit')->middleware('permission:add.monitor')->name('add.monitoring');
    Route::get('/monitoring/display/{id}/{type}', [MonitoringController::class, 'MonitoringDisplay'])->middleware('monitor.access')->middleware('permission:see.monitor.details')->name('display.monitoring');
    Route::get('/monitoring/chart/update/{id}/{type}', [MonitoringController::class, 'MonitoringChartUpdate'])->name('display.chart.update');

    // for delete and edit monitoring
    Route::get('/monitoring/delete/{id}',[MonitoringController::class,'MonitorDelete'])->middleware('permission:delete.monitor')->name('monitoring.delete');
    Route::post('/monitoring/edit/{id}', [MonitoringController::class,'MonitorEdit'])->middleware('permission:edit.monitor')->name('monitoring.update');
    Route::post('/monitor/pause/{id}', [MonitoringController::class, 'pauseMonitor'])->name('monitor.pause');
    
    
    Route::patch('/user/update/billing', [UserController::class, 'UpdateBillingInfo'])->name('update.billing.info');
});

Route::middleware(['auth','verified','CheckUserSession'])->group(function () {

    Route::get('/ssl-check', [SslCheckController::class, 'index'])->middleware('premium_middleware')->name('ssl.check');
    Route::get('/ssl/history', [SslCheckController::class, 'history'])->middleware('premium_middleware')->name('ssl.history');
    Route::post('/ssl-check', [SslCheckController::class, 'check'])->middleware('premium_middleware')->name('ssl.check.domain');

    Route::get('/incidents', [IncidentController::class, 'incidents'])->middleware('role:user|subuser')->middleware('permission:see.incidents')->name('incidents');
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
    Route::any('cashfree/payments/webhook', [CashFreePaymentController::class, 'webhook'])->name('webhook');
    Route::any('cashfree/payments/status', [CashFreePaymentController::class, 'status'])->name('payment.status');

    Route::post('/apply-coupon', [CouponController::class, 'apply']);
    Route::post('/remove-coupon', [CouponController::class, 'remove'])->name('coupon.remove');
    

    // for super admin coupon
    Route::get('/coupons', [CouponController::class, 'DisplayCoupons'])->name('display.coupons');
    Route::post('/coupons', [CouponController::class, 'CouponStore'])->name('coupons.store');
    Route::put('/coupons/{id}', [CouponController::class, 'CouponUpdate'])->name('coupons.update');
    Route::delete('/coupons/{id}', [CouponController::class, 'destroy'])->name('coupons.destroy');
    Route::get('/claimed-users/{coupon_id}', [CouponController::class, 'showClaimedUsers'])->name('view.claimed.users');


    Route::get('premium',[PremiumPageController::class,'PremiumPage'])->middleware('premium_middleware')->middleware('role:user')->name('premium.page');
});



Route::group(['middleware' => ['auth']], function () {
    // Routes accessible only by superadmin
    Route::get('/admin/dashboard',[AdminController::class,'AdminDashboard'])->middleware('role:superadmin')->name('admin.dashboard');
    Route::get('/admin/display/users', action: [UserController::class,'DisplayUsers'])->middleware('permission:see.users')->name('display.users');
    Route::get('/admin/display/roles', action: [RoleController::class,'DisplayRoles'])->middleware('permission:see.roles')->name('display.roles');
    Route::get('/admin/display/permissions', [PermissionController::class, 'DisplayPermissions'])->middleware('role:superadmin')->name('display.permissions');
    Route::get('/admin/display/user/{id}', action: [UserController::class,'ShowUser'])->middleware('permission:see.users')->name('show.user');
   

    // Route::get('/admin/users', [AdminController::class, 'AddUser'])->name('add.user.form');
    Route::post('/admin/add/users', [UserController::class, 'storeUser'])->middleware('permission:add.user')->name('add.user');
    Route::get('/admin/edit/user/{id}', [UserController::class, 'EditUsers'])->middleware('permission:edit.user')->name('edit.user');
    Route::put('/admin/edit/user/{id}', [UserController::class, 'UpdateUsers'])->middleware('permission:edit.user')->name('update.user');
    Route::delete('/admin/delete/user/{id}', [UserController::class, 'DeleteUser'])->middleware('permission:delete.user')->name('delete.user'); 
    Route::post('/admin/restore/user/{id}',[UserController::class, 'RestoreUser'])->name('restore.user');

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

    Route::get('/display/tickets',[TicketController::class,'ViewTicketsUser'])->name('display.tickets');
    Route::get('/raise/tickets',[TicketController::class,'RaiseTicketsPage'])->name('raise.tickets');
    Route::post('/store/tickets',[TicketController::class,'StoreTicket'])->name('store.tickets');

    Route::get('/admin/tickets',[TicketController::class,'TicketsView'])->middleware('role:superadmin')->name('tickets');
    Route::get('/display/tickets/{id}', [TicketController::class, 'ShowTicket'])->name('display.tickets.show');
    Route::put('/display/tickets/{id}', [TicketController::class, 'UpdateTicket'])->name('tickets.update');
    Route::post('/admin/comments', [TicketController::class, 'CommentStore'])->name('admin.comments.store');
    Route::get('/tickets/comments/update/{id}', [TicketController::class, 'CommentPageUpdate'])->name('tickets.comments.update');

    Route::get('/display/subsusers', [UserController::class, 'DisplaySubUsers'])->middleware('premium_middleware')->name('display.sub.users');

    Route::post('/add/subsusers', [UserController::class, 'StoreSubUser'])->middleware('premium_middleware')->name('add.sub.user');
    Route::delete('/delete/subsuser/${id}', [UserController::class, 'DeleteSubUser'])->middleware('premium_middleware')->name('delete.sub.user');


    Route::get('/sub-user/{id}/edit-permissions', [UserController::class, 'EditSubUserPermissions'])->name('edit.sub.user.permissions');
    Route::post('/sub-user/{id}/update-permissions', [UserController::class, 'UpdateSubUserPermissions'])->name('update.sub.user.permissions');

    Route::post('add/changelog',[ChangelogController::class,'AddChangelog'])->name('add.changelog');
    Route::delete('/changelog/{id}', [ChangelogController::class, 'destroy'])->name('changelog.destroy');
    Route::put('/changelogs/{changelog}', [ChangelogController::class, 'update'])->name('changelog.update');

    Route::get('/admin/trafficLog',[TrafficLogController::class,'TrafficLogView'])->middleware('role:superadmin')->name('display.trafficLog');
    

    Route::get('/changelog', [ChangelogController::class, 'ChangelogPage'])->name('changelog.page');

    Route::post('block/ip/${ip}',[BlockController::class,'BlockIP'])->name('block.ip');
    Route::post('unblock/ip/${ip}',[BlockController::class,'UnblockIP'])->name('unblock.ip');

});


Route::post('/subscribe', [PushNotificationController::class , 'subscribe']);


Route::get('/track/{token}.png', [TrackingController::class, 'pixel'])->withoutMiddleware(['web', 'verified', 'auth', \App\Http\Middleware\VerifyCsrfToken::class]);

require __DIR__.'/auth.php';

