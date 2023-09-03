<?php

namespace App\Http\Controllers\web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;
use App\Models\berita;

class beritaController extends Controller
{
    public function index(){
        $berita=berita::latest()->get();
        return inertia::render('berita',[
            'berita' => $berita
        ]);
    }
    public function beritaSimpan(Request $request){
        $request->validate([
            'judul' => 'required',
            'isi' => 'required'
            
        ]);
        berita::updateOrCreate(['id' => $request->id], [
            'judul' => $request->judul,
            'isi' => $request->isi,
            'penulis' => Auth()->User()->name
        ]);
        return back();
    }
public function beritaGambar(Request $request){
    $request->validate([
        'gambar'=>'required',
    ]);
    $gambar = $request->file('gambar')->store('berita', 'public');
    if ($request->id) {
        $upload = berita::find($request->id);
        $upload->update([
            'gambar' => $gambar
        ]);
    }
    return back();
}
}