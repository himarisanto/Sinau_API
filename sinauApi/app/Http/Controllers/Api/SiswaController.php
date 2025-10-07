<?php

namespace App\Http\Controllers\Api;

use App\Models\Siswa;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class SiswaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $siswas = Siswa::all()->map(function ($siswa) {
            $siswa->foto_url = $siswa->foto ? asset('storage/images/' . $siswa->foto) : null;
            return $siswa;
        });

        return response()->json([
            'status' => true,
            'message' => 'List Data Siswa',
            'data' => $siswas
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama' => 'required|string',
            'nisn' => 'required|string|unique:siswas,nisn',
            'no_absen' => 'required|string',
            'kelas' => 'required|string',
            'jurusan' => 'required|string',
            'foto' => 'required|image|mimes:jpg,jpeg,png|max:2048',
            'jenis_kelamin'=> 'required',
            'tanggal_lahir' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => $validator->errors()->first()
            ], 400);
        }

        $fotoPath = $request->file('foto')->storePublicly('images', 'public');
        $fotoName = basename($fotoPath);

        $siswa = Siswa::create([
            'nama' => $request->nama,
            'nisn' => $request->nisn,
            'no_absen' => $request->no_absen,
            'kelas' => $request->kelas,
            'jurusan' => $request->jurusan,
            'foto' => $fotoName,
            'jenis_kelamin'=> $request->jenis_kelamin,
            'tanggal_lahir' => $request->tanggal_lahir,

        ]);
        $siswa->foto_url = asset('storage/images/' . $siswa->foto);

        return response()->json([
            'status' => true,
            'message' => 'Sukses Tambah Data Siswa',
            'data' => $siswa
        ], 200);
    }


    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $siswa = Siswa::find($id);

        if (!$siswa) {
            return response()->json([
                'status' => false,
                'message' => 'Siswa tidak ditemukan'
            ], 404);
        }
        $siswa->foto_url = $siswa->foto ? asset('storage/images/' . $siswa->foto) : null;

        return response()->json([
            'status' => true,
            'message' => 'Detail Data Siswa',
            'data' => $siswa
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $siswa = Siswa::find($id);

        if (!$siswa) {
            return response()->json([
                'status' => false,
                'message' => 'Siswa tidak ditemukan'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'nama' => 'sometimes|required|string',
            'nisn' => 'sometimes|required|string|unique:siswas,nisn,' . $siswa->id,
            'no_absen' => 'sometimes|required|string',
            'kelas' => 'sometimes|required|string',
            'jurusan' => 'sometimes|required|string',
            'foto' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'jenis_kelamin'=> 'required',
            'tanggal_lahir' => 'required',

        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => $validator->errors()->first()
            ], 400);
        }

        $data = $request->only(['nama', 'nisn', 'no_absen', 'kelas', 'jurusan', 'jenis_kelamin', 'tanggal_lahir']);

        if ($request->hasFile('foto')) {
            if ($siswa->foto && Storage::disk('public')->exists("images/{$siswa->foto}")) {
                Storage::disk('public')->delete("images/{$siswa->foto}");
            }

            $fotoPath = $request->file('foto')->store('images', 'public');
            $data['foto'] = basename($fotoPath);
        }

        $siswa->update($data);
        $siswa->foto_url = $siswa->foto ? asset('storage/images/' . $siswa->foto) : null;

        return response()->json([
            'status' => true,
            'message' => 'Sukses Update Data Siswa',
            'data' => $siswa
        ], 200);
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $siswa = Siswa::find($id);

        if (!$siswa) {
            return response()->json([
                'status' => false,
                'message' => 'Siswa tidak ditemukan'
            ], 404);
        }
        if ($siswa->foto && Storage::disk('public')->exists("images/{$siswa->foto}")) {
            Storage::disk('public')->delete("images/{$siswa->foto}");
        }

        $siswa->delete();

        return response()->json([
            'status' => true,
            'message' => 'Sukses Hapus Data Siswa'
        ], 200);
    }
}
