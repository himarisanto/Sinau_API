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
        $kelas = KelasModel::all();
        return response()->json([
            'status' => true,
            'message' => 'Data kelas',
            'data' => $kelas,
        ], 200);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama_kelas' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => $validator->errors()->first(),
            ], 400);
        }

        $kelas = KelasModel::create([
            'nama_kelas' => $request->nama_kelas,
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Data kelas berhasil ditambahkan',
            'data' => $kelas,
        ], 200);
    }

    public function show(string $id)
    {
        $kelas = KelasModel::find($id);

        if (!$kelas) {
            return response()->json([
                'status' => false,
                'message' => 'Kelas tidak ditemukan',
            ], 404);
        }

        return response()->json([
            'status' => true,
            'message' => 'Detail kelas',
            'data' => $kelas,
        ], 200);
    }

    public function update(Request $request, string $id)
    {
        $kelas = KelasModel::find($id);
        if (!$kelas) {
            return response()->json([
                'status' => false,
                'message' => 'Kelas tidak ditemukan',
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'nama_kelas' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => $validator->errors()->first(),
            ], 400);
        }

        $kelas->update(['nama_kelas' => $request->nama_kelas]);

        return response()->json([
            'status' => true,
            'message' => 'Sukses update kelas',
            'data' => $kelas,
        ], 200);
    }

    public function destroy(string $id)
    {
        $kelas = KelasModel::find($id);
        if (!$kelas) {
            return response()->json([
                'status' => false,
                'message' => 'Kelas tidak ditemukan',
            ], 404);
        }

        $kelas->delete();

        return response()->json([
            'status' => true,
            'message' => 'Sukses hapus data kelas',
        ], 200);
    }
}
