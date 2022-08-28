<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\Prodi;


class ProdiController extends Controller 
{
    // public function __construct()
    // {
    //     $this->middleware('auth:api');
    // }

    public function index()
    {
        $prodi = Prodi::orderBy('id', 'ASC')->with('matakuliahs')->get();

        return response()->json([
            'data' => $prodi
        ], 200);
    }

    public function dashboard()
    {
        
    }

    public function show($id)
    {
        $prodi = Prodi::find($id);

        return response()->json([
            'data' => $prodi,
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
        
        $prodi = new Prodi;
        $prodi->jenjang = $request->jenjang;
        $prodi->prodi = $request->prodi;
        $prodi->slug = Str::slug($request->prodi, '-');
        
        if($request->hasFile('image')) {
            $file = $request->image;
            $fileName = Str::slug($request->prodi, '-') . '_' . $file->getClientOriginalName();
            $file->move('storage/images', $fileName);

            $prodi->image = $fileName;
        }

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

        if($request->hasFile('image')) {
            if($prodi->image != null) {
                $currentImage = 'storage/images/' . $prodi->image;
                if(file_exists($currentImage)) {
                    unlink($currentImage);
                }
            }

            $newFile = $request->image;
            $fileName = Str::slug($request->prodi, '-') . '_' . $newFile->getClientOriginalName();
            $newFile->move('storage/images', $fileName);

            $prodi->image = $fileName;
        }
        
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
        if($prodi->image != null) {
            unlink('storage/images/' . $prodi->image);
        }

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