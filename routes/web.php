<?php
use GuzzleHttp\Client;
use App\Http\Controllers\IncidentController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\MonitoringController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SslCheckController;
use App\Http\Controllers\HttpMonitoringController;


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

    Route::get('/monitoring/add', [MonitoringController::class, 'AddMonitoring'])->name('add.monitoring');
    
    Route::get('/monitoring/display', [MonitoringController::class, 'MonitoringDisplay'])->name('display.monitoring');


});
Route::middleware('auth')->group(function () {
    Route::get('/ssl-check', [SslCheckController::class, 'index'])->name('ssl.check');
    Route::post('/ssl-check', [SslCheckController::class, 'check'])->name('ssl.check.domain');

    Route::get('/incidents', [IncidentController::class, 'incidents'])->name('incidents');

    Route::post('/monitoring/http', [HttpMonitoringController::class, 'store'])->name('monitoring.http.store');
});

require __DIR__.'/auth.php';
