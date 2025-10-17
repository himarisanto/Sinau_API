<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Tugas;
use App\Models\Guru;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TugasController extends Controller
{
    public function index()
    {
        $tugas = Tugas::all();

        return response()->json([
            'success' => true,
            'message' => 'List Data Tugas',
            'data' => $tugas,
        ], 200);
    }

    public function show($id)
    {
        $tugas = Tugas::with(['guru:id,nama', 'kelas:id,nama_kelas'])->find($id);

        if (!$tugas) {
            return response()->json([
                'success' => false,
                'message' => 'Tugas tidak ditemukan',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Detail Tugas',
            'data' => $tugas,
        ], 200);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'judul' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'status' => 'required|string|in:aktif,nonaktif',
            'deadline' => 'nullable|date',
            'guru_id' => 'nullable|exists:gurus,id',
            'kelas_id' => 'nullable|exists:kelas_models,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi Gagal',
                'errors' => $validator->errors(),
            ], 422);
        }

        try {
            if ($request->filled('guru_id') && $request->filled('kelas_id')) {
                $guru = Guru::find($request->guru_id);
                $kelasId = (int) $request->kelas_id;
                $teaches = false;

                if ($guru) {
                    if (isset($guru->kelas_id) && $guru->kelas_id == $kelasId) {
                        $teaches = true;
                    }

                    try {
                        if (method_exists($guru, 'kelas') && $guru->kelas()->getQuery()->from === 'kelas_models') {
                            if ($guru->kelas()->where('kelas_models.id', $kelasId)->exists()) {
                                $teaches = true;
                            }
                        }
                    } catch (\Exception $ex) {
                    }
                }

                if (!$teaches) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Guru tidak mengajar di kelas yang dipilih',
                    ], 403);
                }
            }

            $tugas = Tugas::create($request->only([
                'judul',
                'deskripsi',
                'status',
                'deadline',
                'guru_id',
                'kelas_id',
            ]));

            return response()->json([
                'success' => true,
                'message' => 'Tugas berhasil ditambahkan',
                'data' => $tugas,
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan sistem',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function update(Request $request, $id)
    {
        $tugas = Tugas::find($id);

        if (!$tugas) {
            return response()->json([
                'success' => false,
                'message' => 'Tugas tidak ditemukan',
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'judul' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'status' => 'required|string|in:aktif,nonaktif',
            'guru_id' => 'nullable|exists:gurus,id',
            'kelas_id' => 'nullable|exists:kelas_models,id',
            'deadline' => 'nullable|date',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi Gagal',
                'errors' => $validator->errors(),
            ], 422);
        }

        $tugas->update($request->only([
            'judul',
            'deskripsi',
            'status',
            'deadline',
            'guru_id',
            'kelas_id',
        ]));

        // validate guru-kelas consistency if both present
        if ($request->filled('guru_id') && $request->filled('kelas_id')) {
            $guru = Guru::find($request->guru_id);
            if (!$guru || !$guru->kelas()->where('kelas_models.id', $request->kelas_id)->exists()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Guru tidak mengajar di kelas yang dipilih',
                ], 403);
            }
        }

        return response()->json([
            'success' => true,
            'message' => 'Tugas berhasil diperbarui',
            'data' => $tugas,
        ], 200);
    }

    public function destroy($id)
    {
        $tugas = Tugas::find($id);

        if (!$tugas) {
            return response()->json([
                'success' => false,
                'message' => 'Tugas tidak ditemukan',
            ], 404);
        }

        $tugas->delete();

        return response()->json([
            'success' => true,
            'message' => 'Tugas berhasil dihapus',
        ], 200);
    }
}
