<?php

namespace App\Http\Controllers\Api;

use App\Models\Guru;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Auth\Events\Validated;
use Illuminate\Support\Facades\Validator;

class GuruController extends Controller
{
    public function index() 
    {
        $gurus = Guru::all();

        return response()->json([
            'status' => true,
            'massage' => 'List Data Guru',
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
                'massage' => $validator->errors()->first()
            ], 400);
        }

        $guru = Guru::create([
            'nama' => $request->nama,
            'nip' => $request->nip,
            'jenis_kelamin' => $request->jenis_kelamin,
            'alamat' => $request->alamat,
            'tanggal_lahir' => $request->tanggal_lahir,
        ]);

        return response()->json([
            'status' => true,
            'massage' => 'Sekses tambah data Guru',
            'data' => $guru,
        ]);
    }

    public function show(string $id) 
    {
        $guru = Guru::find($id);

        if (!$guru) {
            return response()->json([
                'status' => false,
                'massage' => 'Guru tidak ditemukan',
            ], 400);
        }

        return response()->json([
            'status' => true,
            'massage' => 'Detail data Guru',
            'data' => $guru
        ], 200);
    }

    public function update(Request $request, string $id)
    {
        $guru = Guru::find($id);
        if (!$guru) {
            return response()->json([
                'status' => false,
                'massage' => 'Guru tidak di temukan'
            ], 400);
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
                'massage' => $validator->errors()->first()
            ], 400);
        }
        $guru->update($request->only([
            'nama',
            'nip',
            'jenis_kelamin',
            'alamat',
            'tanggal_lahir',
        ]));

        return response()->json([
            'status' => true,
            'massage' => 'Sukses Update Data Guru',
            'data' => $guru
        ], 200);
    }

    public function destroy(string $id) 
    {
        $guru = Guru::find($id);

        if (!$guru) {
            return response()->json([
                'status' => false,
                'massage' => 'Guru tidak ditemukan',
            ], 400);
        }
        $guru->delete();

        return response()->json([
            'status' => true,
            'massage' => 'Sukses Hapus data Guru'
        ], 200);
    }
}
