<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;

class prodiController extends Controller{

    public function index() {
        $kampus ="Universitas Multi Data Palembang";
        return view("prodi.index")->with('prodis' , $prodis);
    }

    public function allJoinFacade()
    {
        $kampus = "Universitas Multi Data Palembang";
        $result = DB::select('select mahasiswas.*, prodis.nama as nama_prodi from prodis, mahasiswas where prodis.id = mahasiswas.prodi_id');
        return view('prodi.index',['allmahasiswaprodi' => $result, 'kampus' => $kampus]);
    }

    public function alljoinElq () {
        $prodis = Prodi::with('mahasiswa')->get();
        foreach($prodis as $prodi){
            echo "<h3>{$prodi -> nama}";
            echo "<hr>Mahasiswa: ";
            foreach($prodi -> mahasiswa as $mhs){
                echo $mhs->nama .",";
            }
            echo "<hr>";
        }
    }


   public function create(){
return view('prodi.create');
}

public function store(Request $request){
//dump($request);
//echo $request->nama;

$validateData = $request->validate([
'nama' => 'required|min:5|max:20',
'foto' => 'required|file|image|max:5000',
]);
//dump($validateData);
//echo $validateData['nama'];
$ext = $request->foto->getClientOriginalExtension();
//rename nama file
$nama_file = "foto-" . time() . "." .$ext;
$path = $request->foto->storeAs('public', $nama_file);

$prodi = new Prodi();
$prodi->nama = $validateData['nama'];
$prodi->foto = $nama_file;
$prodi -> save();

session()->flash('info',"Data prodi $prodi->nama berhasil disimpan ke database");
return redirect()->route('prodi.create');
}

public function show(Prodi $prodi){
return view('prodi.show', ['prodi' => $prodi]);
}

public function update(Request $request, Prodi $prodi){
$validateData = $request->validate([
'nama'=> 'required|min:5|max:20',
]);

Prodi::where('id', $prodi->id)->update($validateData);
session()->flash('info',"Data Prodi $prodi->nama berhasil disimpan ke database");;
return redirect()->route('prodi.index');
}

public function edit(Prodi $prodi){
return view('prodi.edit', ['prodi' => $prodi]);
}

public function destroy(Prodi $prodi){
$prodi->delete();
return redirect()->route('prodi.index')->with("info". "Prodi $prodi->nama berhasil dihapus.");
}



}
