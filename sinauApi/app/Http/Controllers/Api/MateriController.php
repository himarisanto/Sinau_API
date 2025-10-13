<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Materi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class MateriController extends Controller
{
    public function index()
    {
        $materis = Materi::with('matapelajaran:id,nama_matapelajaran')->get();

        return response()->json([
            'status' => true,
            'message' => 'List Data Materi Pelajaran',
            'data' => $materis,
        ], 200);
    }

    public function show($id)
    {
        $materi = Materi::with('matapelajaran:id,nama_matapelajaran')->find($id);

        if (! $materi) {
            return response()->json([
                'success' => false,
                'message' => 'Materi tidak ditemukan',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Detail Materi',
            'data' => $materi,
        ], 200);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'judul' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'konten' => 'required|string',
            'id_matapelajaran' => 'required|exists:matapelajarans,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi Gagal',
                'errors' => $validator->errors(),
            ], 422);
        }

        try {
            $materi = Materi::create($request->only([
                'judul',
                'deskripsi',
                'konten',
                'id_matapelajaran',
            ]));

            return response()->json([
                'success' => true,
                'message' => 'Materi berhasil ditambahkan',
                'data' => $materi,
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
        $materi = Materi::find($id);
        if (! $materi) {
            return response()->json([
                'success' => false,
                'message' => 'Materi tidak ditemukan',
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'judul' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'konten' => 'required|string',
            'id_matapelajaran' => 'required|exists:matapelajarans,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi Gagal',
                'errors' => $validator->errors(),
            ], 422);
        }

        $materi->update($request->only([
            'judul',
            'deskripsi',
            'konten',
            'id_matapelajaran',
        ]));

        return response()->json([
            'success' => true,
            'message' => 'Materi berhasil diperbarui',
            'data' => $materi,
        ], 200);
    }

    public function destroy($id)
    {
        $materi = Materi::find($id);

        if (! $materi) {
            return response()->json([
                'success' => false,
                'message' => 'Materi tidak ditemukan',
            ], 404);
        }

        $materi->delete();

        return response()->json([
            'success' => true,
            'message' => 'Materi berhasil dihapus',
        ], 200);
    }
}
