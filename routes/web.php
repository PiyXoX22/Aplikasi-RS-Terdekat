<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HospitalController;
use App\Http\Controllers\Api\ServiceController;


Route::get('/', function () {
    return view('welcome');
});
Route::get('/gtw', function () {
    return view('gtw');
});
Route::get('/gtau', function () {
    return view('gtau');
});
Route::get('/tugas', function () {
    return view('tugas');
});
Route::get('/hospitals/nearby', [HospitalController::class, 'nearby']);
Route::get('/places/nearby', [HospitalController::class, 'nearby']);
Route::post('/route', [HospitalController::class, 'route']);