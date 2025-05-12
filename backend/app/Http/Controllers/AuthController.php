<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    // Inscription
    public function register(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6',
        ]);

        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
        ]);

        Auth::login($user);

        return response()->json(['user' => $user]);
    }

    // Connexion
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            return response()->json(['user' => $user]);
        }

        return response()->json(['message' => 'Identifiants invalides'], 401);
    }

    // Utilisateur connecté
    public function user(Request $request)
    {
        return response()->json($request->user());
    }

    // Déconnexion
    public function logout(Request $request)
    {
        Auth::logout();
        return response()->json(['message' => 'Déconnecté']);
    }
}
