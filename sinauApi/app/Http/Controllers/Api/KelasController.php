<?php

namespace App\Http\Controllers\Api;

use App\Models\Siswa;
use App\Models\KelasModel;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use GuzzleHttp\Promise\Create;
use Illuminate\Auth\Events\Validated;
use Illuminate\Support\Facades\Validator;
use PhpParser\Node\Expr\FuncCall;
use Symfony\Component\Console\Helper\TreeNode;

class KelasController extends Controller
{
    public function index()
    {
        $kelas = KelasModel::all();
        return response()->json([
            'status' => true,
            'massage' => 'Data kelas',
            'data' => $kelas
        ], 200);
    }
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama_kelas' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'massage' => $validator->errors()->first()
            ], 400);
        }

        $kelas = KelasModel::create([
            'nama_kelas' => $request->nama_kelas,
        ]);
        return response()->json([
            'status' => true,
            'massage' => 'Data kelas',
            'data' => $kelas
        ],  200);
    }
    
    public function show(string $id)
    {
        $kelas = KelasModel::find($id);

        if (!$kelas) {
            return response()->json([
                'status' => false,
                'massage' => 'Kelas tidak ditemukan',
            ], 400);
        }

        return response()->json([
            'status' => true,
            'massage' => 'Detail kelas',
            'data' => $kelas
        ], 200);
    }

    public function update(Request $request, string $id)
    {
        $kelas = KelasModel::find($id);
        if (!$kelas) {
            return response()->json([
                'status' => false,
                'massage' => 'kelas tidak di temukan'
            ], 400);
        }

        $validator = Validator::make($request->all(), [
            'nama_kelas' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'massage' => $validator->errors()->first()
            ], 400);
        }
        $data = $request->only(['nama_kelas']);
        $kelas->update($data);

        return response()->json([
            'status' => true,
            'massage' => 'Sukses Update kelas',
            'data' => $kelas
        ], 200);
    }
    public function destroy(string $id)
    {
        $kelas = KelasModel::find($id);

        if (!$kelas) {
            return response()->json([
                'status' => false,
                'message' => 'kelas tidak ditemukan'
            ], 404);
        }

        $kelas->delete();
        return response()->json([
            'status' => true,
            'message' => 'Sukses Hapus Data Siswa'
        ], 200);

    }
}
