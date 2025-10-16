<?php

namespace App\Http\Controllers\Api;

use App\Models\Siswa;
use App\Models\KelasModel;
use App\Models\Tugas;
use App\Models\Jawaban;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class SiswaController extends Controller
{
    public function index()
    {
        $siswas = Siswa::with(['kelas', 'gurus:id,nama'])->get();

        $siswas->each(function ($siswa) {
            $siswa->gurus->makeHidden('pivot');
        });

        return response()->json([
            'success' => true,
            'message' => 'List Data Siswa',
            'data' => $siswas,
        ], 200);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama' => 'required|string|max:255',
            'nisn' => 'required|string|unique:siswas,nisn',
            'no_absen' => 'required|string|max:10',
            'kelas_id' => 'required|exists:kelas_models,id',
            'jurusan' => 'required|string|max:255',
            'foto' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'jenis_kelamin' => 'required|in:L,P',
            'tanggal_lahir' => 'required|date',
            'guru_ids' => 'sometimes|array',
            'guru_ids.*' => 'exists:gurus,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors(),
            ], 422);
        }

        try {
            $fotoName = null;
            if ($request->hasFile('foto')) {
                $fotoName = time() . '_' . $request->file('foto')->getClientOriginalName();
                $request->file('foto')->storeAs('public/images', $fotoName);
            }

            $siswa = Siswa::create([
                'nama' => $request->nama,
                'nisn' => $request->nisn,
                'no_absen' => $request->no_absen,
                'kelas_id' => $request->kelas_id,
                'jurusan' => $request->jurusan,
                'foto' => $fotoName,
                'jenis_kelamin' => $request->jenis_kelamin,
                'tanggal_lahir' => $request->tanggal_lahir,
            ]);

            if ($request->has('guru_ids')) {
                $siswa->gurus()->attach($request->guru_ids);
            }

            $siswa->load(['kelas', 'gurus']);

            return response()->json([
                'success' => true,
                'message' => 'Data siswa berhasil ditambahkan',
                'data' => $siswa,
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan sistem',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function show($id)
    {
        $siswa = Siswa::with(['kelas', 'gurus'])->find($id);

        if (!$siswa) {
            return response()->json([
                'success' => false,
                'message' => 'Data siswa tidak ditemukan',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Detail Data Siswa',
            'data' => $siswa,
        ], 200);
    }
    public function ambilTugas($id)
    {
        $siswa = Siswa::find($id);

        if (!$siswa) {
            return response()->json([
                'success' => false,
                'message' => 'Data siswa tidak ditemukan',
            ], 404);
        }

        if (!$siswa->kelas_id) {
            return response()->json([
                'success' => true,
                'message' => 'Siswa belum terdaftar di kelas manapun',
                'data' => [],
            ], 200);
        }

        $tugas = Tugas::where('kelas_id', $siswa->kelas_id)
            ->where('status', 'aktif')
            ->get();

        return response()->json([
            'success' => true,
            'message' => 'Daftar tugas untuk kelas siswa',
            'data' => $tugas,
        ], 200);
    }

    public function update(Request $request, $id)
    {
        $siswa = Siswa::find($id);

        if (!$siswa) {
            return response()->json([
                'success' => false,
                'message' => 'Data siswa tidak ditemukan',
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'nama' => 'sometimes|required|string|max:255',
            'nisn' => 'sometimes|required|string|unique:siswas,nisn,' . $id,
            'no_absen' => 'sometimes|required|string|max:10',
            'kelas_id' => 'required|exists:kelas_models,id',
            'jurusan' => 'sometimes|required|string|max:255',
            'foto' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'jenis_kelamin' => 'sometimes|required|in:L,P',
            'tanggal_lahir' => 'sometimes|required|date',
            'guru_ids' => 'sometimes|array',
            'guru_ids.*' => 'exists:gurus,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors(),
            ], 422);
        }

        try {
            $data = $request->only([
                'nama',
                'nisn',
                'no_absen',
                'kelas_id',
                'jurusan',
                'jenis_kelamin',
                'tanggal_lahir'
            ]);

            if ($request->hasFile('foto')) {
                if ($siswa->foto && Storage::exists('public/images/' . $siswa->foto)) {
                    Storage::delete('public/images/' . $siswa->foto);
                }

                $fotoName = time() . '_' . $request->file('foto')->getClientOriginalName();
                $request->file('foto')->storeAs('public/images', $fotoName);
                $data['foto'] = $fotoName;
            }

            $siswa->update($data);

            if ($request->has('guru_ids')) {
                $siswa->gurus()->sync($request->guru_ids);
            }

            $siswa->load(['kelas', 'gurus']);

            return response()->json([
                'success' => true,
                'message' => 'Data siswa berhasil diperbarui',
                'data' => $siswa,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan sistem',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function destroy($id)
    {
        $siswa = Siswa::find($id);

        if (!$siswa) {
            return response()->json([
                'success' => false,
                'message' => 'Data siswa tidak ditemukan',
            ], 404);
        }

        try {
            if ($siswa->foto && Storage::exists('public/images/' . $siswa->foto)) {
                Storage::delete('public/images/' . $siswa->foto);
            }
            $siswa->gurus()->detach();
            $siswa->delete();

            return response()->json([
                'success' => true,
                'message' => 'Data siswa berhasil dihapus',
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan sistem',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
