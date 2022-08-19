<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class AuthController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login', 'register']]);
    }

    public function register(Request $request)
    {
        $this->validate($request, [
            'nomor_induk' => 'required|max:15',
            'nama_lengkap' => 'required|max:100',
            'username' => 'required|string|max:100',
            'password' => 'required|string|max:100',
            'prodi_id' => 'required|exists:prodis,id',
            'role_id' => 'required|exists:roles,id',
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

        return response()->json($user, 200);
    }

    public function login(Request $request)
    {
        $this->validate($request, [
            'username' => 'required|string',
            'password' => 'required|string'
        ]);
        
        $credentials = $request->only(['username', 'password']);
        
        if(! $token = Auth::attempt($credentials)){
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $id = auth()->id();
        $user = auth()->user()->with('prodi', 'role')->find($id);

        // dd(auth()->user());

        return response()->json([
            'user' => $user,
            'token' => $token
        ], 200);
    }

    public function logout()
    {
        auth()->logout();

        return response()->json([
            'message' => 'Logout Successfully'
        ], 200);
    }
}