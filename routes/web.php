<?php

use App\Http\Controllers\AgencyController;
use App\Http\Controllers\AgencyDetailController;
use App\Http\Controllers\DataController;
use App\Http\Controllers\IncidentController;
use App\Http\Controllers\InstanceController;
use App\Http\Controllers\InstanceDetailController;
use App\Http\Controllers\StatisticController;
use App\Models\Incident;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
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
        $today = Carbon::today();
        $user = Auth::user();

        $data['page_title'] = 'overview';

        // Base query
        $query = Incident::whereDate('request_datetime', $today);

        // Adjust query for non-admin users
        if ($user->role !== 'admin') {
            $query->where('responder_id', $user->id);
        }

        // Get counts
        $data['all_incident'] = $query->count();
        $data['queue_incident'] = $query->where('status', 'requested')->count();
        $data['processed_incident'] = $query->where('status', 'processed')->count();
        $data['completed_incident'] = $query->where('status', 'completed')->count();

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
    });

    Route::prefix('queue')->name('queue.')->group(function () {
        Route::get('/', [IncidentController::class, 'queue'])->name('index');
    });

    Route::prefix('statistic')->name('statistic.')->group(function () {
        Route::get('/', [StatisticController::class, 'index'])->name('index');
        Route::get('/chart-data', [StatisticController::class, 'getIncidentChartData']);
    });

    Route::prefix('data')->name('data.')->group(function () {
        Route::get('/', [DataController::class, 'index'])->name('index');
        Route::get('/users/{id}/detail', [DataController::class, 'getUserDetail']);
    });
});


require __DIR__.'/auth.php';
