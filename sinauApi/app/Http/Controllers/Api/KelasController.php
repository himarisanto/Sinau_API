<?php

namespace App\Http\Controllers\Api;

use App\Models\KelasModel;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class KelasController extends Controller
{
    public function index()
    {
        $kelas = KelasModel::withCount('siswas')->get();

        return response()->json([
            'success' => true,
            'message' => 'Data kelas berhasil diambil',
            'data' => $kelas,
        ], 200);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama_kelas' => 'required|string|max:50|unique:kelas_models,nama_kelas',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors(),
            ], 422);
        }

        $kelas = KelasModel::create([
            'nama_kelas' => $request->nama_kelas,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Data kelas berhasil ditambahkan',
            'data' => $kelas,
        ], 201);
    }

    public function show($id)
    {
        $kelas = KelasModel::with('siswas')->find($id);

        if (!$kelas) {
            return response()->json([
                'success' => false,
                'message' => 'Data kelas tidak ditemukan',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Detail data kelas',
            'data' => $kelas,
        ], 200);
    }

    public function update(Request $request, $id)
    {
        $kelas = KelasModel::find($id);
        if (!$kelas) {
            return response()->json([
                'success' => false,
                'message' => 'Data kelas tidak ditemukan',
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'nama_kelas' => 'required|string|max:50|unique:kelas_models,nama_kelas,' . $id,
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors(),
            ], 422);
        }

        $kelas->update([
            'nama_kelas' => $request->nama_kelas,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Data kelas berhasil diperbarui',
            'data' => $kelas,
        ], 200);
    }

    public function destroy($id)
    {
        $kelas = KelasModel::withCount('siswas')->find($id);

        if (!$kelas) {
            return response()->json([
                'success' => false,
                'message' => 'Data kelas tidak ditemukan',
            ], 404);
        }

        if ($kelas->siswas_count > 0) {
            return response()->json([
                'success' => false,
                'message' => 'Tidak dapat menghapus kelas yang masih memiliki siswa',
            ], 422);
        }

        $kelas->delete();

        return response()->json([
            'success' => true,
            'message' => 'Data kelas berhasil dihapus',
        ], 200);
    }
}
