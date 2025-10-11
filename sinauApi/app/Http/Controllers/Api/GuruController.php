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
        $gurus = Guru::with(['siswas'])->get();

        $gurus->each(function ($gurus) {
            $gurus->siswas->makeHidden('pivot');
        });


        return response()->json([
            'success' => true,
            'message' => 'Data guru berhasil diambil',
            'data' => $gurus,
        ], 200);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama' => 'required|string|max:255',
            'nip' => 'required|string|unique:gurus,nip',
            'jenis_kelamin' => 'required|in:L,P',
            'alamat' => 'required|string',
            'tanggal_lahir' => 'required|date',
            'mata_pelajaran' => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors(),
            ], 422);
        }

        $guru = Guru::create($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Data guru berhasil ditambahkan',
            'data' => $guru,
        ], 201);
    }

    public function show($id)
    {
        $guru = Guru::with('siswas')->find($id);

        if (!$guru) {
            return response()->json([
                'success' => false,
                'message' => 'Data guru tidak ditemukan',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Detail data guru',
            'data' => $guru,
        ], 200);
    }

    public function update(Request $request, $id)
    {
        $guru = Guru::find($id);
        if (!$guru) {
            return response()->json([
                'success' => false,
                'message' => 'Data guru tidak ditemukan',
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'nama' => 'required|string|max:255',
            'nip' => 'required|string|unique:gurus,nip,' . $id,
            'jenis_kelamin' => 'required|in:L,P',
            'alamat' => 'required|string',
            'tanggal_lahir' => 'required|date',
            'mata_pelajaran' => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors(),
            ], 422);
        }

        $guru->update($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Data guru berhasil diperbarui',
            'data' => $guru,
        ], 200);
    }

    public function destroy($id)
    {
        $guru = Guru::withCount('siswas')->find($id);
        
        if (!$guru) {
            return response()->json([
                'success' => false,
                'message' => 'Data guru tidak ditemukan',
            ], 404);
        }

        if ($guru->siswas_count > 0) {
            $guru->siswas()->detach();
        }

        $guru->delete();

        return response()->json([
            'success' => true,
            'message' => 'Data guru berhasil dihapus',
        ], 200);
    }
}