<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Prodi;
use Illuminate\Http\Request;

class ProdiController extends Controller
{
    public function index() {
        // mengambil data dari tabel prodi dan menyimpanya pada variabel $prodis
        $prodis = Prodi::all();
        $success['data'] = $prodis;
        return $this->sendResponse($success, 'Data prodi.');
    }
    public function store(Request $request) {
        //membuat validasi semua field wajib diisi
        $validasi = $request->validate([
            'nama' => 'required|min:5|max:20',
            'foto'=> 'required|file|image|max:5000'
        ]);

        $ext = $request->foto->getClientOriginalExtension();
        $nama_file = "foto-" . time() .".". $ext;
        //nama file baru: foto 1234343.png
        $path = $request->foto->storeAs('public' , $nama_file);

        //melakukan insert data
        $prodi = new Prodi();
        $prodi->nama = $validasi['nama'];
        $prodi->foto = $nama_file;

        //jika berhasil maka simpan data dengan methode $post->save()
        if($prodi->save()) {
            $success['data'] = $prodi;
            return $this->sendResponse($success, 'Data prodi berhasil disimpan,');
        } else {
            return $this->sendError('Error.' , ['error' => 'Data prodi gagal disimpan.']);
        }
    }
}
