<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\IncidentController;
use App\Http\Controllers\InstanceController;
use App\Http\Controllers\UserBioController;
use App\Http\Controllers\UserContactController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// AUTH
Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);
Route::get('login/{provider}', [AuthController::class, 'redirectToProvider']);
Route::get('login/{provider}/callback', [AuthController::class, 'handleProviderCallback']);

Route::middleware('auth:api')->group(function () {
    Route::post('logout', [AuthController::class, 'logout']);
    Route::post('refresh', [AuthController::class, 'refresh']);
    Route::get('me', [AuthController::class, 'me']);

    // User Bio
    Route::post('user-bio', [UserBioController::class, 'upsertUserBio']);
    Route::get('user-bio', [UserBioController::class, 'getUserBio']);

    // User Contacts
    Route::get('user-contacts', [UserContactController::class, 'getUserContact']);
    Route::post('user-contacts', [UserContactController::class, 'store']);
    Route::put('user-contacts/{id}', [UserContactController::class, 'update']);
    Route::delete('user-contacts/{id}', [UserContactController::class, 'destroy']);

    // Incidents
    Route::get('incidents', [IncidentController::class, 'apiGetAllInstances']);
    Route::get('incidents/group-by-status', [IncidentController::class, 'apiGetMyIncidentByStatus']);
    Route::post('incidents', [IncidentController::class, 'apiStoreIncident']);

    // Instances
    Route::get('instances', [InstanceController::class, 'apiGetAllInstances']);
    Route::get('instances/group-by-detail', [InstanceController::class, 'apiGetInstancesByDetail']);
    Route::post('instances', [InstanceController::class, 'apiStoreIncident']);
});
