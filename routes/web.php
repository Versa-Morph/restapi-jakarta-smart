<?php

use App\Http\Controllers\AgencyController;
use App\Http\Controllers\AgencyDetailController;
use App\Http\Controllers\DataController;
use App\Http\Controllers\IncidentController;
use App\Http\Controllers\InstanceController;
use App\Http\Controllers\InstanceDetailController;
use App\Http\Controllers\StatisticController;
use App\Models\Incident;
use App\Models\InstanceDetail;
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
        $data['user'] = $user;

        $baseQuery = Incident::whereDate('request_datetime', $today);

        if ($user->role !== 'admin') {
            $baseQuery->where('responder_id', $user->id);
            $data['instance'] = InstanceDetail::where('id', $user->instance_detail_id)->get();
        }

        $data['all_incident'] = $baseQuery->count();

        $incidentQuery = clone $baseQuery;
        $queueQuery = clone $baseQuery;
        $processedQuery = clone $baseQuery;
        $completedQuery = clone $baseQuery;

        $data['incidents'] = $incidentQuery->where('status', '!=', 'completed')->get();
        $data['queue_incident'] = $queueQuery->where('status', 'requested')->count();
        $data['processed_incident'] = $processedQuery->where('status', 'processed')->count();
        $data['completed_incident'] = $completedQuery->where('status', 'completed')->count();

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
        Route::post('/{id}/complete', [IncidentController::class, 'complete'])->name('complete');
    });

    Route::prefix('queue')->name('queue.')->group(function () {
        Route::get('/', [IncidentController::class, 'queue'])->name('index');
        Route::post('/{id}/accept', [IncidentController::class, 'accept'])->name('accept');
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
