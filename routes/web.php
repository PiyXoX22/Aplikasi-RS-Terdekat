<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HospitalController;
use App\Http\Controllers\Api\ServiceController;


Route::get('/', function () {
    return view('welcome');
});
Route::get('/hospitals/nearby', [HospitalController::class, 'nearby']);
Route::post('/route', [HospitalController::class, 'route']);