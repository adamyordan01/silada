<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class ChangePasswordController extends Controller
{
    public function index()
    {
        return view('change-password.index');
    }

    public function update(Request $request)
    {
        $request->validate([
            'current_password' => ['required'],
            'password' => ['required', 'min:8', 'confirmed']
        ], [
            'current_password.required' => 'Password saat ini tidak boleh kosong',
            'password.required' => 'Password tidak boleh kosong',
            'password.min' => 'Password minimal 8 karakter',
            'password.confirmed' => 'Password tidak sama'
        ]);

        if (Hash::check($request->current_password, auth()->user()->password)) {
            auth()->user()->update(['password' => bcrypt($request->password)]);
            
            return back()->with('success', 'Password anda berhasil di ubah');
        }

        throw ValidationException::withMessages([
            'current_password' => 'Password anda saat ini salah.'
        ]);
    }
}
