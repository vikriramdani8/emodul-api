<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\Prodi;


class ProdiController extends Controller 
{
    

    public function index()
    {
        $prodi = Prodi::orderBy('id', 'ASC')->with('matakuliahs')->get();

        return response()->json([
            'data' => $prodi
        ], 200);
    }

    public function show($id)
    {
        $prodi = Prodi::find($id);

        if ($prodi == null) {
            return response()->json([
                'message' => 'data tidak ditemukan'
            ]);
        }

        return response()->json([
            'data' => $prodi
        ], 200);
    }

    public function showBySlug($slug) 
    {
        $prodi = Prodi::where('slug', $slug)->with('matakuliahs')->get();

        return response()->json([
            'data' => $prodi,
        ], 200);
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'jenjang' => 'required|string|max:2',
            'prodi' => 'required|string',
        ]);
        

        // $input = $request->all();
        // $prodi = Prodi::create($input);
        $prodi = new Prodi;
        $prodi->jenjang = $request->jenjang;
        $prodi->prodi = $request->prodi;
        $prodi->slug = Str::slug($request->prodi, '-');
        $prodi->save();

        return response()->json([
            'data' => $prodi,
            'message' => 'Data berhasil disimpan',
        ],200);
        
    }

    public function update(Request $request, $id)
    {
        $prodi = Prodi::find($id);

        if ($prodi == null) {
            return response()->json([
                'message' => 'Data prodi tidak ditemukan',
            ]);
        }

        $this->validate($request, [
            'jenjang' => 'required|string|max:2',
            'prodi' => 'required|string'
        ]);
        
        $input = array(
            $prodi->jenjang = $request->jenjang,
            $prodi->prodi = $request->prodi,
            $prodi->slug = Str::slug($request->prodi),
        );
        $prodi->fill($input)->save();
        

        return response()->json([
            'data' => $prodi,
            'message' => 'Data berhasil di update'
        ],200);
    }

    public function delete($id)
    {
        $prodi = Prodi::find($id);

        if($prodi == null){
            return response()->json([
                'message' => 'Data tidak ditemukan'
            ]);
        }

        $prodi->delete();

        return response()->json([
            'message' => 'Data berhasil di hapus'
        ],200);
    }
}