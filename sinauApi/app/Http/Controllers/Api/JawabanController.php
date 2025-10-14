<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Jawaban;

class JawabanController extends Controller
{
    public function index()
    {
        return Jawaban::with(['tugas', 'siswa'])->get();
    }

    public function show($id)
    {
        return Jawaban::with(['tugas', 'siswa'])->findOrFail($id);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'tugas_id' => 'required|exists:tugas,id',
            'siswa_id' => 'required|exists:siswas,id',
            'isi' => 'nullable|string',
            'file' => 'nullable|string',
            'nilai' => 'nullable|integer',
            'komentar' => 'nullable|string',
        ]);

        $jawaban = Jawaban::create($data);

        return response($jawaban, 201);
    }

    public function update(Request $request, $id)
    {
        $jawaban = Jawaban::findOrFail($id);

        $data = $request->validate([
            'isi' => 'nullable|string',
            'file' => 'nullable|string',
            'nilai' => 'nullable|integer',
            'komentar' => 'nullable|string',
        ]);

        $jawaban->update($data);

        return $jawaban;
    }

    public function destroy($id)
    {
        $jawaban = Jawaban::findOrFail($id);
        $jawaban->delete();

        return response()->json(['message' => 'deleted']);
    }
}
