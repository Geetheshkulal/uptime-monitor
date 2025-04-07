<?php
use App\Http\Controllers\AdminController;
use GuzzleHttp\Client;
use App\Http\Controllers\IncidentController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\MonitoringController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SslCheckController;
use App\Http\Controllers\DnsController;
use App\Http\Controllers\PingMonitoringController;
use App\Http\Controllers\PortMonitorController;

use App\Http\Controllers\HttpMonitoringController;
use App\Http\Controllers\CashFreePaymentController;
use App\Http\Controllers\PlanSubscriptionController;
use App\Http\Controllers\ServerHealthController;
use Illuminate\Support\Facades\Http;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');



Route::middleware('auth')->group(function () {
    
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/monitoring/dashboard', [MonitoringController::class, 'MonitoringDashboard'])->middleware('role:user')->name('monitoring.dashboard');
    // notification
    Route::get('/notifications/read', function() {
        auth()->user()->unreadNotifications->markAsRead();
        return back();
    })->name('notifications.read');
    
    Route::get('/monitoring/dashboard/update', [MonitoringController::class, 'MonitoringDashboardUpdate'])->name('monitoring.dashboard.update');


    Route::get('/monitoring/add', [MonitoringController::class, 'AddMonitoring'])->middleware('monitor.limit')->name('add.monitoring');
    
    Route::get('/monitoring/display/{id}/{type}', [MonitoringController::class, 'MonitoringDisplay'])->middleware('monitor.access')->name('display.monitoring');
    
    Route::get('/monitoring/chart/update/{id}/{type}', [MonitoringController::class, 'MonitoringChartUpdate'])->name('display.chart.update');

    // for delete and edit monitoring
    Route::get('/monitoring/delete/{id}',[MonitoringController::class,'MonitorDelete'])->name('monitoring.delete');
    Route::post('/monitoring/edit/{id}', [MonitoringController::class,'MonitorEdit'])->name('monitoring.update');

    Route::post('/monitor/pause/{id}', [MonitoringController::class, 'pauseMonitor'])->name('monitor.pause');
});

Route::middleware('auth')->group(function () {
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

    Route::get('premium',function(){return view('pages.premium');})->name('premium.page');
});



Route::group(['middleware' => ['auth']], function () {
    // Routes accessible only by superadmin
    Route::get('/server-health', [ServerHealthController::class, 'check'])->name('server.health');
    Route::get('/admin/dashboard',[AdminController::class,'AdminDashboard'])->middleware('role:superadmin')->name('admin.dashboard');
    Route::get('/admin/display/users', action: [AdminController::class,'DisplayUsers'])->middleware('permission:see.users')->name('display.users');
    Route::get('/admin/display/roles', [AdminController::class,'DisplayRoles'])->middleware('permission:see.roles')->name('display.roles');
    Route::get('/admin/display/permissions', [AdminController::class, 'DisplayPermissions'])->middleware('role:superadmin')->name('display.permissions');
    Route::get('/admin/display/user/{id}', action: [AdminController::class,'ShowUser'])->middleware('permission:see.users')->name('show.user');

    // Route::get('/admin/users', [AdminController::class, 'AddUser'])->name('add.user.form');
    Route::post('/admin/add/users', [AdminController::class, 'storeUser'])->middleware('permission:add.user')->name('add.user');
    Route::get('/admin/edit/user/{id}', [AdminController::class, 'EditUsers'])->middleware('permission:edit.user')->name('edit.user');
    Route::put('/admin/edit/user/{id}', [AdminController::class, 'UpdateUsers'])->middleware('permission:edit.user')->name('update.user');
    Route::delete('/admin/delete/user/{id}', [AdminController::class, 'DeleteUser'])->middleware('permission:delete.user')->name('delete.user');

    Route::get('/admin/add/roles', [AdminController::class, 'AddRole'])->middleware('permission:add.role')->name('add.role');
    Route::post('/roles', [AdminController::class, 'StoreRole'])->middleware('permission:add.role')->name('store.role');

    Route::get('/admin/delete/role/{id}', [AdminController::class, 'DeleteRole'])->middleware('permission:delete.role')->name('delete.role');
    Route::get('/admin/edit/role/{id}', [AdminController::class, 'EditRole'])->middleware('permission:edit.role')->name('edit.role');
    Route::put('/admin/update/role/{id}', [AdminController::class, 'UpdateRole'])->middleware('permission:edit.role')->name('update.role');


    Route::get('admin/add/permission', [AdminController::class, 'AddPermission'])->middleware('role:superadmin')->name('add.permission');
    Route::post('admin/store/permission', [AdminController::class, 'StorePermission'])->middleware('role:superadmin')->name('store.permission');

    Route::get('/admin/delete/permission/{id}', [AdminController::class, 'DeletePermission'])->middleware('role:superadmin')->name('delete.permission');

    Route::get('/admin/display/activity', [AdminController::class,'DisplayActivity'])->middleware('permission:see.activity')->name('display.activity');

    
    Route::get('/admin/edit/permissions/{id}', [AdminController::class, 'EditPermission'])->middleware('role:superadmin')->name('edit.permission');
    Route::put('/admin/update/permissions/{id}', [AdminController::class, 'UpdatePermission'])->middleware('role:superadmin')->name('update.permission');

    Route::get('/roles/{id}/permissions', [AdminController::class, 'EditRolePermissions'])->middleware('permission:edit.role.permissions')->name('edit.role.permissions');
    Route::post('/roles/{id}/permissions', [AdminController::class, 'UpdateRolePermissions'])->middleware('permission:edit.role.permissions')->name('update.role.permissions');

});

require __DIR__.'/auth.php';

