<?php

use App\Http\Controllers\AgencyController;
use App\Http\Controllers\AgencyDetailController;
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
    Route::get('/overview', function () {
        $data['page_title'] = 'overview';
        return view('overview.index', $data);
    })->name('overview');

    Route::resource('agencies', AgencyController::class);
    Route::get('agencies/{agency}/agency-details/create', [AgencyDetailController::class, 'create'])->name('agency-details.create');
    Route::post('agencies/{agency}/agency-details/store', [AgencyDetailController::class, 'store'])->name('agency-details.store');
    Route::get('agencies/{agency}/agency-details/edit/{agencyDetail}', [AgencyDetailController::class, 'edit'])->name('agency-details.edit');
    Route::put('agencies/{agency}/agency-details/update/{agencyDetail}', [AgencyDetailController::class, 'update'])->name('agency-details.update');
    Route::delete('agencies/{agency}/agency-details/delete/{agencyDetail}', [AgencyDetailController::class, 'destroy'])->name('agency-details.destroy');
});


require __DIR__.'/auth.php';
