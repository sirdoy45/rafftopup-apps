<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class DashboardSettingController extends Controller
{
    // Menampilkan halaman pengaturan akun pelanggan
    public function account()
    {
        $user = Auth::user();

        return view('pages.dashboard-account', [
            'user' => $user,
        ]);
    }

    // Proses update data akun pelanggan
    public function update(Request $request, $redirect)
    {
        $rules = [
            'name' => 'required|max:255',
            'email' => 'required|email|max:255',
            'img_profile' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:10048',
            'oldImage' => 'nullable|string',
        ];

        $data = $request->validate($rules);
        
        /** @var \App\Models\User $user */
        $user = Auth::user();

        // Update foto profil jika ada
        if ($request->hasFile('img_profile')) {
            if ($request->oldImage && Storage::disk('public')->exists($request->oldImage)) {
                Storage::disk('public')->delete($request->oldImage);
            }

            $data['img_profile'] = $request->file('img_profile')->store('assets/user-photo-profile', 'public');
        }

        // Update data
        $user->name = $data['name'];
        $user->email = $data['email'];
        $user->img_profile = $data['img_profile'] ?? $user->img_profile;

        // Reset password jika diisi
        if ($request->filled('password')) {
            $request->validate([
                'password' => 'confirmed|min:6',
            ]);
            $user->password = Hash::make($request->password);
        }

        $user->save();

        return redirect()->route($redirect)->with('success', 'Akun berhasil diperbarui.');
    }
}
