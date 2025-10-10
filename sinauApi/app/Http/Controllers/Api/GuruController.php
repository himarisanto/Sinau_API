<?php

namespace App\Http\Controllers\Api;

use App\Models\Guru;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class GuruController extends Controller
{
    public function index()
    {
        $gurus = Guru::with('siswas')->get();

        return response()->json([
            'status' => true,
            'message' => 'List Data Guru',
            'data' => $gurus,
        ], 200);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama' => 'required|string',
            'nip' => 'required|string|unique:gurus,nip',
            'jenis_kelamin' => 'required|string',
            'alamat' => 'required|string',
            'tanggal_lahir' => 'required|date',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => $validator->errors()->first(),
            ], 400);
        }

        $guru = Guru::create($request->all());

        return response()->json([
            'status' => true,
            'message' => 'Sukses tambah data guru',
            'data' => $guru,
        ], 200);
    }

    public function show(string $id)
    {
        $guru = Guru::with('siswas')->find($id);

        if (!$guru) {
            return response()->json([
                'status' => false,
                'message' => 'Guru tidak ditemukan',
            ], 404);
        }

        return response()->json([
            'status' => true,
            'message' => 'Detail Data Guru',
            'data' => $guru,
        ], 200);
    }

    public function update(Request $request, string $id)
    {
        $guru = Guru::find($id);
        if (!$guru) {
            return response()->json([
                'status' => false,
                'message' => 'Guru tidak ditemukan',
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'nama' => 'required|string',
            'nip' => 'required|string|unique:gurus,nip,' . $id,
            'jenis_kelamin' => 'required|string',
            'alamat' => 'required|string',
            'tanggal_lahir' => 'required|date',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => $validator->errors()->first(),
            ], 400);
        }

        $guru->update($request->all());

        return response()->json([
            'status' => true,
            'message' => 'Sukses update data guru',
            'data' => $guru,
        ], 200);
    }

    public function destroy(string $id)
    {
        $guru = Guru::find($id);
        if (!$guru) {
            return response()->json([
                'status' => false,
                'message' => 'Guru tidak ditemukan',
            ], 404);
        }

        $guru->delete();

        return response()->json([
            'status' => true,
            'message' => 'Sukses hapus data guru',
        ], 200);
    }
}
