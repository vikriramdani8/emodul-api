<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class UserController extends Controller 
{
    public function index()
    {
        $user = User::orderBy('role_id','desc')->with('prodi','role')->get();

        return response()->json([
            "data" => $user
        ], 200);
    }

    public function show($id)
    {
        $user = User::where('id',$id)->with('prodi','role')->get();

        return response()->json([
            'data' => $user
        ], 200);
    }

    public function store(Request $request)
    {
        $this -> validate($request, [ 
            'nomor_induk' => 'required|max:15|string',
            'nama_lengkap' => 'required|min:5|string',
            'username' => 'required|string',
            'password' => 'required|min:5|string',
            'prodi_id' =>  'required|exists:prodis,id',
            'role_id' => 'required|exists:roles,id'
        ]);

        $user = new User;
        $user->nomor_induk = $request->input('nomor_induk');
        $user->nama_lengkap = $request->input('nama_lengkap');
        $user->username = $request->input('username');
        $plainPassword = $request->input('password');
        $user->password = app('hash')->make($plainPassword);
        $user->prodi_id = $request->input('prodi_id');
        $user->role_id = $request->input('role_id');
        $user->save();
        
        return response()->json([
            'message' => 'data berhasil disimpan',
            'data' => $user
        ]);
    }

    public function update(Request $request, $id)
    {
        $this -> validate($request, [ 
            'nomor_induk' => 'required|max:15|string',
            'nama_lengkap' => 'required|min:5|string',
            'username' => 'required|string',
            'password' => 'required|min:5|string',
            'prodi_id' =>  'required|exists:prodis,id',
            'role_id' => 'required|exists:roles,id'
        ]);

        $user = User::find($id);
        if($user == null){
            return reponse()->json([
                'message' => 'Id Tidak Ditemukan'
            ]);
        }

        $input = array(
            $user->nomor_induk = $request->input('nomor_induk'),
            $user->nama_lengkap = $request->input('nama_lengkap'),
            $user->username = $request->input('username'),
            $plainPassword = $request->input('password'),
            $user->password = app('hash')->make($plainPassword),
            $user->prodi_id = $request->input('prodi_id'),
            $user->role_id = $request->input('role_id'),
        );
        $user->fill($input);
        $user->save();

        return response()->json([
            'message' => 'Data berhasil di update',
            'data' => $user
        ]);
        
    }

    public function delete($id)
    {
        $user = User::find($id);

        $user->delete();

        return response()->json([
            'message' => 'Data Berhasil Dihapus'
        ]);
    }

    public function resetPassword($id)
    {
        $user = User::find($id);      
        $input = array(
            $user->nomor_induk = $user->nomor_induk,
            $user->nama_lengkap = $user->nama_lengkap,
            $user->username = $user->username,
            $plainPassword = $user->nomor_induk,
            $user->password = app('hash')->make($plainPassword),
            $user->role_id = $user->role_id,
            $user->prodi_id = $user->prodi_id,
        );
        $user->fill($input);
        $user->save();

        return response()->json([
            'message' => 'Reset password berhasil',
            'data' => $user,
        ], 200);
    }
}