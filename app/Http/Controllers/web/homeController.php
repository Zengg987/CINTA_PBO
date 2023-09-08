<?php

namespace App\Http\Controllers\web;

use App\Http\Controllers\Controller;
use App\Models\berita;
use App\Models\profile;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;


class homeController extends Controller
{
    public function show()
    {
        return Inertia::render('Home');
    }



    public function registerPost(Request $request)
    {
        $request->validate(
            [
                'name' => 'required',
                'username' => 'required',
                'password' => 'required|max:8',
                'email' => 'required'
            ],
            [
                'name.required' => 'nama tidak boleh kosong bambang',
                'username.required' => 'Isi Dongo',
                'password.required' => 'Tidak isi saya hack pc nya',
                'email.required' => 'ada pw ga ada email? sehat?',
            ]
        );

        User::create([
            'name' => $request->name,
            'username' => $request->username,
            'password' => bcrypt($request->password),
            'email' => $request->email
        ]);



        return back();
    }

    //mengarah ke dashboard
    public function dashboard()
    {
        return inertia::render('dashboard');
    }

    public function user()
    {
        $data =  User::latest()->get();
        return inertia::render('user', [
            'data' => $data
        ]);
    }

    public function profile(){
        $data = profile::first();
        return inertia::render('profile', [
            'profile' => $data
        ]);
    }

    public function profileEditShow(){
        $profile = profile::first();
        return response()->json($profile);

    }

    public function userBioSimpan(Request $request)
{
    $profile = Profile::first();

    // Cek apakah ada file gambar baru yang dikirimkan dalam permintaan
    if ($request->hasFile('gambar')) {
        $imageFilename = $profile->gambar;

        // Hapus gambar yang sudah ada jika ada
        if ($imageFilename && Storage::disk('public')->exists($imageFilename)) {
            Storage::disk('public')->delete($imageFilename);
        }

        // Simpan gambar baru ke storage 'public'
        $gambar = $request->file('gambar')->store('profile', 'public');
    } else {
        // Jika tidak ada file gambar yang dikirimkan, gunakan gambar yang sudah ada
        $gambar = $profile->gambar;
    }

    if ($profile) {
        $profile->update([
            'gambar' => $gambar,
            'biodata' => $request->biodata
        ]);
    }
}


    public function userPost(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'username' => 'required',
            'password' => 'required|max:8',
            'email' => 'required'
        ]);
        User::updateOrCreate(['id' => $request->id], [
            'name' => $request->name,
            'username' => $request->username,
            'password' => bcrypt($request->password),
            'email' => $request->email
        ]);
    }

    public function userEdit($id)
    {
        $data = User::where('id', $id)->first();
        return response()->json($data);
    }

    public function userHapus($id)
    {
        if ($id) {
            $hapus = User::find($id);
            $hapus->delete();
        }
        return back();
    }



    public function getBerita(){
        $berita1 = berita::limit(1)->get();
        $berita2 = berita::latest()->get();
        $trending = berita::get();

        return response()->json([
            'berita1' => $berita1,
            'berita2' => $berita2,
            'trending' => $trending
        ]);

    }
}
