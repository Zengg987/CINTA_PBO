<?php

namespace App\Http\Controllers\web;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;


class homeController extends Controller
{
    public function show()
    {
        return Inertia::render('Home');
    }

    public function profile()
    {
        return Inertia::render('profile');
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
}
