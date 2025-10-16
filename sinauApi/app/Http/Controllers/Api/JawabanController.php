<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Jawaban;
use App\Models\Tugas;
use App\Models\Siswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class JawabanController extends Controller
{
    public function index()
    {
        $jawabans = Jawaban::with([
            'tugas:id,judul',
            'siswa:id,nama',
        ])->get();

        return response()->json([
            'success' => true,
            'message' => 'List Data Jawaban',
            'data' => $jawabans,
        ], 200);
    }

    public function show($id)
    {
        try {
            $jawaban = Jawaban::with([
                'tugas:id,judul',
                'siswa:id,nama',
            ])->find($id);

            if (!$jawaban) {
                return response()->json([
                    'success' => false,
                    'message' => 'Jawaban tidak ditemukan',
                ], 404);
            }

            return response()->json([
                'success' => true,
                'message' => 'Detail Jawaban',
                'data' => $jawaban,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat mengambil data jawaban',
                'error' => $e->getMessage(),
            ], 500);
        }
    }


    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'tugas_id' => 'required|exists:tugas,id',
            'siswa_id' => 'required|exists:siswas,id',
            'isi' => 'nullable|string',
            'file' => 'nullable|string',
            'nilai' => 'nullable|integer|min:0|max:100',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi Gagal',
                'errors' => $validator->errors(),
            ], 422);
        }

        try {
            $tugas = Tugas::find($request->tugas_id);
            $siswa = Siswa::find($request->siswa_id);

            if ($tugas && $siswa) {
                if ($tugas->kelas_id !== null && $tugas->kelas_id != $siswa->kelas_id) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Siswa tidak diperbolehkan mengerjakan tugas di luar kelasnya',
                    ], 403);
                }

                if (isset($tugas->status) && $tugas->status !== 'aktif') {
                    return response()->json([
                        'success' => false,
                        'message' => 'Tugas tidak aktif sehingga tidak dapat dikerjakan',
                    ], 403);
                }
            }

            $jawaban = Jawaban::create($request->only([
                'tugas_id',
                'siswa_id',
                'isi',
                'file',
                'nilai',
            ]));

            return response()->json([
                'success' => true,
                'message' => 'Jawaban berhasil ditambahkan',
                'data' => $jawaban,
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
        $jawaban = Jawaban::find($id);

        if (!$jawaban) {
            return response()->json([
                'success' => false,
                'message' => 'Jawaban tidak ditemukan',
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'isi' => 'nullable|string',
            'file' => 'nullable|string',
            'nilai' => 'nullable|integer|min:0|max:100',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi Gagal',
                'errors' => $validator->errors(),
            ], 422);
        }

        $jawaban->update($request->only([
            'isi',
            'file',
            'nilai',
        ]));

        return response()->json([
            'success' => true,
            'message' => 'Jawaban berhasil diperbarui',
            'data' => $jawaban,
        ], 200);
    }

    public function destroy($id)
    {
        $jawaban = Jawaban::find($id);

        if (!$jawaban) {
            return response()->json([
                'success' => false,
                'message' => 'Jawaban tidak ditemukan',
            ], 404);
        }

        $jawaban->delete();

        return response()->json([
            'success' => true,
            'message' => 'Jawaban berhasil dihapus',
        ], 200);
    }
}
