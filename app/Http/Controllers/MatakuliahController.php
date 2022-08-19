<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Matakuliah;
use Illuminate\Support\Str;

class MatakuliahController extends Controller 
{
    public function index()
    {
        $matkul = Matakuliah::orderBy('id', 'asc')->with('prodis')->get();

        return response()->json([
            'data' => $matkul
        ], 200);
    }

    public function show($id)
    {
        $matkul = Matakuliah::where('id', $id)->with('prodis')->get();

        if(!$matkul){
            return response()->json([
                'msg' => 'Data tidak ditemukan'
            ]);
        }

        return response()->json([
            'matkul' => $matkul
        ], 200);
    }

    public function showBySlug($slug)
    {
        $emodulByMatkul = Matakuliah::where('slug', $slug)->with('emoduls')->get();

        return response()->json([
            'data' => $emodulByMatkul
        ],200);
    }


    public function store(Request $request)
    {
        $this->validate($request, [
            'kode' => 'string|required|max:20',
            'matakuliah' => 'string|required|max:50',
            'prodi_id' => 'required|exists:prodis,id'
        ]);

        $matkul = new Matakuliah;
        $matkul->kode = $request->kode;
        $matkul->matakuliah = $request->matakuliah;
        $matkul->slug = Str::slug($request->matakuliah, '-');
        $matkul->prodi_id = $request->prodi_id;
        $matkul->save();

        return response()->json([
            'message' => 'Data berhasil ditambah',
            'matakuliah' => $matkul
        ], 200);
    }

    public function update(Request $request, $id)
    {
        $matkul = Matakuliah::find($id);

        if($matkul == null) {
            return response()->json([
                'message' => 'Data Tidak ditemukan'
            ]);
        }

        $this->validate($request, [
            'kode' => 'string|required|max:20',
            'matakuliah' => 'string|required|max:50',
            'prodi_id' => 'required|exists:prodis,id'
        ]);

        $input = array(
            $matkul->kode = $request->kode,
            $matkul->matakuliah = $request->matakuliah,
            $matkul->slug = Str::slug($request->matakuliah, '-'),
            $matkul->prodi_id = $request->prodi_id,
        );
        $matkul->fill($input)->save();

        return response()->json([
            'message' => 'Data berhasil di Update',
            'matakuliah' => $matkul
        ], 200);
    }

    public function delete($id)
    {
        $matkul = Matakuliah::find($id);

        if(!$matkul){
            return response()->json([
                'message' => 'Data tidak ditemukan'
            ]);
        }

        $matkul->delete();

        return response()->json([
            'message' => 'Data berhasil dihapus'
        ], 200);
    }
}