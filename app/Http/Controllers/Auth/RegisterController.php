<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Kawasan;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{
    public function showRegisterForm()
    {
        $kawasan = Kawasan::orderBy('kode_kawasan')->get();

        return view('auth.register', compact('kawasan'));
    }

    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'unique:users,email'],
            'no_hp' => ['required', 'string', 'max:20'],
            'kawasan_id' => ['required', 'exists:kawasan,id'],
            'password' => ['required', 'min:8', 'confirmed'],
        ]);

        User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'no_hp' => $validated['no_hp'],
            'kawasan_id' => $validated['kawasan_id'],
            'role' => 'warga',
            'password' => Hash::make($validated['password']),
        ]);

        return redirect()->route('login')->with('success', 'Pendaftaran berhasil. Silakan masuk.');
    }
}
