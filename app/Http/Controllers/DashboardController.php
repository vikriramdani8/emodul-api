<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Matakuliah;
use App\Models\Emodul;
use App\Models\User;
use App\Models\Prodi;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function index()
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

    public function getEmodulByProdi() 
    {
        $prodi = Prodi::orderBy('id','asc')->with('emoduls', 'matakuliahs')->get();
        
        return response()->json([
            'data' => $prodi
        ], 200);
    }

    public function getModulBySlug($slug)
    {
        $prodi = Prodi::where('slug', $slug)->with('emoduls', 'matakuliahs')->get();

        return response()->json([
            'data' => $prodi
        ], 200);
    }
}