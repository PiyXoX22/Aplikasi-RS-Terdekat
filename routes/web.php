<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HospitalController;
use App\Http\Controllers\Api\ServiceController;


Route::get('/', function () {
    return view('welcome');
});
Route::get('/map', function () {
    return view('map');
});
Route::get('/docs', function () {
    return view('docs');
});
// Route::get('/gtw', function () {
//     return view('gtw');
// });
// Route::get('/gtau', function () {
//     return view('gtau');
// });
// Route::get('/tugas', function () {
//     return view('tugas');
// });
Route::middleware(['apikey','throttle:60,1'])->group(function () {

    Route::get('/places/nearby', [HospitalController::class, 'nearby']);
    Route::post('/route', [HospitalController::class, 'route']);

});
