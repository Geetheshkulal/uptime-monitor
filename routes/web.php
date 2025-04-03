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

    Route::get('/monitoring/dashboard', [MonitoringController::class, 'MonitoringDashboard'])->name('monitoring.dashboard');
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



Route::group(['middleware' => ['role:superadmin']], function () {
    // Routes accessible only by superadmin

    Route::get('/admin/display/users', action: [AdminController::class,'DisplayUsers'])->name('display.users');
    Route::get('/admin/display/roles', [AdminController::class,'DisplayRoles'])->name('display.roles');
    Route::get('/admin/display/permissions', function(){return view('');})->name('display.permissions');
    
    Route::get('/admin/display/user/{id}', action: [AdminController::class,'ShowUser'])->name('show.user');


    Route::get('/admin/edit/user/{id}', [AdminController::class, 'EditUsers'])->name('edit.user');
    Route::put('/admin/edit/user/{id}', [AdminController::class, 'UpdateUsers'])->name('update.user');
    Route::delete('/admin/delete/user/{id}', [AdminController::class, 'DeleteUser'])->name('delete.user');

    Route::get('/admin/add/roles', [AdminController::class, 'AddRole'])->name('add.role');
    Route::post('/roles', [AdminController::class, 'StoreRole'])->name('store.role');

    Route::get('/admin/delete/role/{id}', [AdminController::class, 'DeleteRole'])->name('delete.role');
    Route::get('/admin/edit/role/{id}', [AdminController::class, 'EditRole'])->name('edit.role');
    Route::put('/admin/update/role/{id}', [AdminController::class, 'UpdateRole'])->name('update.role');

});

require __DIR__.'/auth.php';

