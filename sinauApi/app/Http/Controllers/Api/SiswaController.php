<?php

namespace App\Http\Controllers\Api;

use App\Models\Siswa;
use Faker\Provider\Base;
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
        return response()->json([
            'Status' => true,
            'Massage' => 'List data Siswa',
            'data'=> Siswa::all()
        ], 200);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // dd('tes');
        $validator = Validator::make($request->all(), [
            'nama' => 'required|string',
            'nisn' => 'required|string',
            'no_absen' => 'required|string',
            'kelas' => 'required|string',
            'jurusan' => 'required|string',
            'foto' => 'required|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => $validator->errors()->first()
            ], 400);
        }

        if ($request->hasFile('foto')) {
            $fotoPath = $request->file('foto')->store('images');
            $fotoName = basename($fotoPath);
        }

        $siswa = Siswa::create([
            'nama' => $request->nama,
            'nisn' => $request->nisn,
            'no_absen' => $request->no_absen,
            'kelas' => $request->kelas,
            'jurusan' => $request->jurusan,
            'foto' => $fotoName,
        ]);

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

        return response()->json([
            'status' => true,
            'message' => 'Detail Data Siswa',
            'data' => $siswa
        ], 200);
    }
    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
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
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => $validator->errors()->first()
            ], 400);
        }

        $data = $request->only(['nama', 'nisn', 'no_absen', 'kelas', 'jurusan']);

        if ($request->hasFile('foto')) {
            if ($siswa->foto && Storage::exists("images/{$siswa->foto}")) {
                Storage::delete("images/{$siswa->foto}");
            }

            $fotoPath = $request->file('foto')->store('images');
            $data['foto'] = basename($fotoPath);
        }

        $siswa->update($data);

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
                'status' => true,
                'massage' => 'Siswa tidak di temukan'
            ]);
        }
        if ($siswa->foto && Storage::exists("images/{$siswa->foto}")) {
            Storage::delete("images/{$siswa->foto}");
        }

        $siswa->delete();

        return response()->json([
            'status' => true,
            'massage' => 'Sukses Hapus data Siswa',
        ]);
    }
}
