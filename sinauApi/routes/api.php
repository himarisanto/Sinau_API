<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\GuruController;
use App\Http\Controllers\Api\KelasController;
use App\Http\Controllers\Api\SiswaController;
use App\Http\Controllers\Api\TugasController;
use App\Http\Controllers\Api\MateriController;
use App\Http\Controllers\Api\JawabanController;
use App\Http\Controllers\Api\JurusanController;
use App\Http\Controllers\Api\MatapelajaranContoller;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/siswa', [SiswaController::class, 'index']);
Route::get('/siswa/{id}', [SiswaController::class, 'show']);
Route::post('/siswa', [SiswaController::class, 'store']);
Route::post('/siswa/{id}', [SiswaController::class, 'update']);
Route::delete('/siswa/{id}', [SiswaController::class, 'destroy']);
Route::get('/siswa/{id}/tugas', [SiswaController::class, 'ambilTugas']);
Route::get('/siswa/kelas/{kelas_id}', [SiswaController::class, 'filterByKelas']);


Route::get('/guru', [GuruController::class, 'index']);
Route::get('/guru/{id}', [GuruController::class, 'show']);
Route::post('/guru', [GuruController::class, 'store']);
Route::put('/guru/{id}', [GuruController::class, 'update']);
Route::delete('/guru/{id}', [GuruController::class, 'destroy']);

Route::get('/kelas', [KelasController::class, 'index']);
Route::get('/kelas/{id}', [KelasController::class, 'show']);
Route::post('/kelas', [KelasController::class, 'store']);
Route::put('/kelas/{id}', [KelasController::class, 'update']);
Route::delete('/kelas/{id}', [KelasController::class, 'destroy']);

Route::get('/matapelajaran', [MatapelajaranContoller::class, 'index']);
Route::get('/matapelajaran/{id}', [MatapelajaranContoller::class, 'show']);
Route::post('/matapelajaran', [MatapelajaranContoller::class, 'store']);
Route::put('/matapelajaran/{id}', [MatapelajaranContoller::class, 'update']);
Route::delete('/matapelajaran/{id}', [MatapelajaranContoller::class, 'destroy']);

Route::prefix('materi')->group(function () {
    Route::get('/', [MateriController::class, 'index']);
    Route::get('/{id}', action: [MateriController::class, 'show']);
    Route::post('/', [MateriController::class, 'store']);
    Route::put('/{id}', [MateriController::class, 'update']);
    Route::delete('/{id}', [MateriController::class, 'destroy']);
});

Route::get('/tugas', [TugasController::class, 'index']);
Route::get('/tugas/{id}', [TugasController::class, 'show']);
Route::post('/tugas', [TugasController::class, 'store']);
Route::put('/tugas/{id}', [TugasController::class, 'update']);
Route::delete('/tugas/{id}', [TugasController::class, 'destroy']);

Route::get('/jawaban', [JawabanController::class, 'index']);
Route::get('/jawaban/{id}', [JawabanController::class, 'show']);
Route::post('/jawaban', [JawabanController::class, 'store']);
Route::put('/jawaban/{id}', [JawabanController::class, 'update']);
Route::delete('/jawaban/{id}', [JawabanController::class, 'destroy']);

Route::get('/jurusan', [JurusanController::class, 'index']);
Route::get('/jurusan/{id}', [JurusanController::class, 'show']);
Route::post('/jurusan', [JurusanController::class, 'store']);
Route::put('/jurusan/{id}', [JurusanController::class, 'update']);
Route::delete('/jurusan/{id}', [JurusanController::class, 'destroy']);