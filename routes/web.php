<?php

use App\Http\Controllers\AgencyController;
use App\Http\Controllers\AgencyDetailController;
use App\Http\Controllers\DataController;
use App\Http\Controllers\IncidentController;
use App\Http\Controllers\InstanceController;
use App\Http\Controllers\InstanceDetailController;
use App\Models\Incident;
use Illuminate\Support\Facades\Route;

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

Route::get('/', function () {
    return redirect(route('login'));
});

Route::middleware('auth:web')->group(function () {
    // Overview
    Route::get('/overview', function () {
        $data['page_title'] = 'overview';
        return view('overview.index', $data);
    })->name('overview');

    // Instance & Detail
    Route::resource('instances', InstanceController::class);
    Route::get('instances/{instance}/instance-details/create', [InstanceDetailController::class, 'create'])->name('instance-details.create');
    Route::post('instances/{instance}/instance-details/store', [InstanceDetailController::class, 'store'])->name('instance-details.store');
    Route::get('instances/{instance}/instance-details/edit/{instanceDetail}', [InstanceDetailController::class, 'edit'])->name('instance-details.edit');
    Route::put('instances/{instance}/instance-details/update/{instanceDetail}', [InstanceDetailController::class, 'update'])->name('instance-details.update');
    Route::delete('instances/{instance}/instance-details/delete/{instanceDetail}', [InstanceDetailController::class, 'destroy'])->name('instance-details.destroy');

    Route::prefix('incidents')->name('incidents.')->group(function () {
        Route::get('/', [IncidentController::class, 'index'])->name('index');
        Route::get('/queue', [IncidentController::class, 'queue'])->name('queue');
    });

    Route::prefix('data')->name('data.')->group(function () {
        Route::get('/', [DataController::class, 'index'])->name('index');
        Route::get('/users/{id}/detail', [DataController::class, 'getUserDetail']);
    });
});


require __DIR__.'/auth.php';
