<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Tugas;

class TugasController extends Controller
{
    public function index()
    {
        return Tugas::with(['guru', 'kelas'])->get();
    }

    public function show($id)
    {
        return Tugas::with(['guru', 'kelas'])->findOrFail($id);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'judul' => 'required|string',
            'deskripsi' => 'nullable|string',
            'status' => 'required|string',
            'guru_id' => 'nullable|exists:gurus,id',
            'kelas_id' => 'nullable|exists:kelas_models,id',
        ]);

        $tugas = Tugas::create($data);

        return response($tugas, 201);
    }

    public function update(Request $request, $id)
    {
        $tugas = Tugas::findOrFail($id);

        $data = $request->validate([
            'judul' => 'sometimes|required|string',
            'deskripsi' => 'nullable|string',
            'status' => 'sometimes|required|string',
            'guru_id' => 'nullable|exists:gurus,id',
            'kelas_id' => 'nullable|exists:kelas_models,id',
        ]);

        $tugas->update($data);

        return $tugas;
    }

    public function destroy($id)
    {
        $tugas = Tugas::findOrFail($id);
        $tugas->delete();

        return response()->json(['message' => 'deleted']);
    }
}
