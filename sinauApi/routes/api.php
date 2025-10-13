<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\GuruController;
use App\Http\Controllers\Api\KelasController;
use App\Http\Controllers\Api\SiswaController;
use App\Http\Controllers\Api\MateriController;
use App\Http\Controllers\Api\MatapelajaranContoller;

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

Route::get('/matapelajaran', [MatapelajaranContoller::class, 'index']);
Route::get('/matapelajaran/{id}', [MatapelajaranContoller::class, 'show']);
Route::post('/matapelajaran', [MatapelajaranContoller::class, 'store']);
Route::post('/matapelajaran/{id}', [MatapelajaranContoller::class, 'update']);
Route::delete('/matapelajaran/{id}', [MatapelajaranContoller::class, 'destroy']);

Route::prefix('materi')->group(function () {
    Route::get('/', [MateriController::class, 'index']);        
    Route::get('/{id}', [MateriController::class, 'show']);      
    Route::post('/', [MateriController::class, 'store']);        
    Route::put('/{id}', [MateriController::class, 'update']);
    Route::delete('/{id}', [MateriController::class, 'destroy']);
});

