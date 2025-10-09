<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\GuruController;
use App\Http\Controllers\Api\KelasController;
use App\Http\Controllers\Api\SiswaController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/siswa', [SiswaController::class, 'index']);
Route::get('/siswa/{id}', [SiswaController::class, 'show']);
Route::post('/siswa', [SiswaController::class, 'store']);
Route::post('/siswa/{id}', [SiswaController::class, 'update']);
Route::delete('/siswa/{id}', [SiswaController::class, 'destroy']);

Route::get('/guru', [GuruController::class, 'index']);
Route::get('/guru/{id}', [GuruController::class, 'show']);
Route::post('/guru', [GuruController::class, 'store']);
Route::post('/guru/{id}', [GuruController::class, 'update']);
Route::delete('/guru/{id}', [GuruController::class, 'destroy']);

Route::get('/kelas', [KelasController::class, 'index']);      
Route::get('/kelas/{id}', [KelasController::class, 'show']); 
Route::post('/kelas', [KelasController::class, 'store']);  
Route::put('/kelas/{id}', [KelasController::class, 'update']);
Route::delete('/kelas/{id}', [KelasController::class, 'destroy']); 
