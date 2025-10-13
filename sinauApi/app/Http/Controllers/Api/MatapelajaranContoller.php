<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Matapelajaran;
use App\Models\Guru;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class MatapelajaranContoller extends Controller
{
    public function index()
    {
        $mapel = Matapelajaran::with('gurus:id,nama')->get();

        return response()->json([
            'status' => true,
            'message' => 'List Matapelajaran',
            'data' => $mapel,
        ], 200);
    }

    public function show() 
    {
        $id = request()->route('id') ?? request('id');
        if (! $id) {
            return response()->json([
                'success' => false,
                'message' => 'ID matapelajaran diperlukan',
            ], 400);
        }

        $mapel = Matapelajaran::with('gurus')->find($id);
        if (! $mapel) {
            return response()->json([
                'success' => false,
                'message' => 'Matapelajaran tidak ditemukan',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Detail Matapelajaran',
            'data' => $mapel,
        ], 200);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama_matapelajaran' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return Response()->json([
                'success' => false,
                'massage' => 'Validasi Gagal',
                'errors' => $validator->errors(),
            ], 422);
        }

        try {
            $mapel = Matapelajaran::create([
                'nama_matapelajaran' => $request->nama_matapelajaran,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Data Matapelajaran Berhasil ditambah',
                'data' => $mapel,
            ], 200);

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
        $validator = Validator::make($request->all(), [
            'nama_matapelajaran' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi Gagal',
                'errors' => $validator->errors(),
            ], 422);
        }

        $mapel = Matapelajaran::find($id);
        if (! $mapel) {
            return response()->json(['success' => false, 'message' => 'Matapelajaran tidak ditemukan'], 404);
        }
        $mapel->update(['nama_matapelajaran' => $request->nama_matapelajaran]);
        return response()->json(['success' => true, 'message' => 'Matapelajaran diperbarui', 'data' => $mapel], 200);
    }

    public function destroy($id)
    {
        $mapel = Matapelajaran::find($id);
        if (! $mapel) {
            return response()->json(['success' => false, 'message' => 'Matapelajaran tidak ditemukan'], 404);
        }
        $mapel->gurus()->detach();
        $mapel->delete();
        return response()->json(['success' => true, 'message' => 'Matapelajaran dihapus'], 200);
    }
}
