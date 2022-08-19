<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Models\Emodul;
use App\Models\Module;
use App\Models\User;
use App\Models\Matakuliah;
use App\Models\Prodi;
use File;

class EmodulController extends Controller 
{
    public function dashboard()
    {
        $emodul = Emodul::orderBy('id', 'asc')->get();
        $user = User::orderBy('id', 'asc')->get();
        $prodi = Prodi::orderBy('id', 'asc')->get();
        $matkul = Matakuliah::orderBy('id', 'asc')->get();

        return response()->json([
            'emodul' => $emodul,
            'user' => $user,
            'prodi' => $prodi,
            'matkul' => $matkul
        ], 200);
    }


    public function index()
    {
        $emodul = Emodul::orderBy('id', 'asc')->with('modules', 'prodis', 'matakuliahs')->get();

        return response()->json([
            'data' => $emodul
        ]);
    }

    public function showBySlug($slug)
    {
        $emodul = Emodul::where('slug', $slug)->with('modules')->get();

        return response()->json([
            'data' => $emodul
        ],200);
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'title' => 'required|string',
            'deskripsi' => 'required|string',
            'dosen' => 'required|string',
            'prodi_id' => 'required|exists:prodis,id',
            'matakuliah_id' => 'required|exists:matakuliahs,id',
            'module' => 'required'
        ]);

        $emodul = new Emodul();
        $emodul->title = $request->title;
        $emodul->slug = Str::slug($emodul->title, '-');
        $emodul->deskripsi = $request->deskripsi;
        $emodul->dosen = $request->dosen;
        $emodul->prodi_id = $request->prodi_id;
        $emodul->matakuliah_id = $request->matakuliah_id;
        $emodul->save();

        $module = new Module;
        $emodulFile = [];

        foreach ($request->module as $file) {
            $fileName = $file->getClientOriginalName();
            // $file->move(storage_path('app/public/emoduls'), $fileName);
            $file->move('storage/emoduls', $fileName);

            $emodulFile = [
                'module' => $fileName,
            ];

            $emodul->modules()->create($emodulFile);
        }

        return response()->json([
            'message' => 'Data berhasil disimpan',
            'data' => $emodul,
            'files' => $module,
        ], 200);
    }

    // public function update(Request $request, $id)
    // {
    //     $this->validate($request, [
    //         'title' => 'required|string',
    //         'deskripsi' => 'required|string',
    //         'dosen' => 'required|string',
    //         'prodi_id' => 'required|exists:prodis,id',
    //         'matakuliah_id' => 'required|exists:matakuliahs,id',
    //         'module' => 'required'
    //     ]);

    //     $emodul = new Emodul();
    //     $emodul->title = $request->title;
    //     $emodul->deskripsi = $request->deskripsi;
    //     $emodul->dosen = $request->dosen;
    //     $emodul->prodi_id = $request->prodi_id;
    //     $emodul->matakuliah_id = $request->matakuliah_id;
    //     $emodul->fill();
    //     $emodul->save();

    //     $module = new Module;
    //     $emodulFile = [];

    //     foreach ($request->module as $file) {
    //         $fileName = $file->getClientOriginalName();
    //         $file->move('files/emodul', $fileName);

    //         $emodulFile = [
    //             'module' => $fileName,
    //         ];

    //         $emodul->modules()->fill($emodulFile);
    //     }

    //     return response()->json([
    //         'message' => 'Data berhasil disimpan',
    //         'data' => $emodul,
    //         'files' => $module,
    //     ], 200);
    // }

    public function delete($id)
    {
        $emodul = Emodul::find($id);
        $modules = Module::where('emodul_id', $id)->pluck('module');

        foreach($modules as $module){
            // $destinationPath = storage_path('app/public/emoduls/');
            // unlink($destinationPath . $module);
            $publicPath = 'storage/emoduls/';
            unlink($publicPath . $module);
        }
        
        $emodul->delete();
        $emodul->modules()->delete();

        return response()->json([
            'message' => 'Data berhasil dihapus',
        ], 200);
    }
}