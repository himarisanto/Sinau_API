<?php

namespace App\Http\Controllers\Api;

use App\Models\Jurusan;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class JurusanController extends Controller
{
    public function index()
    {
        try {
            // $jurusans = Jurusan::with('kelas', 'siswa')->get();
            $jurusans = Jurusan::all();
        
            return response()->json([
                'success' => true,
                'message' => 'Successfully retrieved jurusan data',
                'data' => $jurusans
            ], 200);

        }catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to retrieve jurusan data',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function show($id) 
    {
        try {
            $jurusan = Jurusan::with('kelas', 'siswa')->findOrFail($id);

            return response()->json([
                'success' => true,
                'message' => 'Successfully retrieved jurusan data',
                'data' => $jurusan
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve jurusan data',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama_jurusan' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation Failed',
                'errors' => $validator->errors(),
            ], 422);
        }

        try {
            $jurusan = Jurusan::create([
                'nama_jurusan' => $request->input('nama_jurusan'),
            ]);


            return response()->json([
                'success' => true,
                'message' => 'Successfully created jurusan',
                'data' => $jurusan
            ], 201);
        }catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create jurusan',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'nama_jurusan' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation Failed',
                'errors' => $validator->errors(),
            ], 422);
        }

        try {
            $jurusan = Jurusan::findOrFail($id);
            $jurusan->nama_jurusan = $request->input('nama_jurusan');
            $jurusan->save();

            return response()->json([
                'success' => true,
                'message' => 'Successfully updated jurusan',
                'data' => $jurusan
            ], 200);
        }catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update jurusan',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    public function destroy($id)
    {
        try {
            $jurusan = Jurusan::findOrFail($id);
            $jurusan->delete();

            return response()->json([
                'success' => true,
                'message' => 'Successfully deleted jurusan'
            ], 200);
        }catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete jurusan',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
