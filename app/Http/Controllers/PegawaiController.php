<?php

namespace App\Http\Controllers;

use App\Models\Bagian;
use App\Models\Pegawai;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Models\User;
use RealRashid\SweetAlert\Facades\Alert;

class PegawaiController extends Controller
{
    public function index()
    {
        $pegawai = Pegawai::with('user')->get();
        return view('pegawai.index', compact('pegawai'));
    }

    public function create()
    {
        $bagians = Bagian::all();
        return view('pegawai.create', compact('bagians'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_pegawai' => 'required',
            'bagian_id' => 'required|exists:bagians,id',
            'email' => 'required|email|unique:users,email',
            'foto' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'nik' => 'required|numeric|unique:pegawais,nik',
            'alamat' => 'required',
            'umur' => 'required|numeric',
            'tanggal_lahir' => 'required|date',
            'tempat_lahir' => 'required',
            'jenis_kelamin' => 'required|in:laki-laki,perempuan',
        ], [
            'nama_pegawai.required' => 'Nama pegawai harus diisi.',
            'nik.required' => 'NIK harus diisi.',
            'nik.numeric' => 'NIK harus berupa angka.',
            'nik.unique' => 'NIK sudah terdaftar.',
            'alamat.required' => 'Alamat harus diisi.',
            'umur.required' => 'Umur harus diisi.',
            'umur.numeric' => 'Umur harus berupa angka.',
            'tanggal_lahir.required' => 'Tanggal lahir harus diisi.',
            'tanggal_lahir.date' => 'Tanggal lahir harus berupa tanggal.',
            'tempat_lahir.required' => 'Tempat lahir harus diisi.',
            'jenis_kelamin.required' => 'Jenis kelamin harus diisi.',
            'jenis_kelamin.in' => 'Jenis kelamin harus laki-laki atau perempuan.',
        ]);

        $foto = $request->file('foto');
        $fileName = Str::uuid() . '.' . $foto->getClientOriginalExtension();

        Storage::disk('public')->putFileAs('foto_pegawai', $foto, $fileName);

        $newRequest = $request->all();
        $newRequest['foto'] = $fileName;

        // Pegawai::create([
        //     'nama_pegawai' => $request->nama_pegawai,
        //     'nik' => $request->nik,
        //     'alamat' => $request->alamat,
        //     'umur' => $request->umur,
        //     'tanggal_lahir' => $request->tanggal_lahir,
        //     'tempat_lahir' => $request->tempat_lahir,
        //     'jenis_kelamin' => $request->jenis_kelamin,
        // ]);

        $newData = Pegawai::create($newRequest);
        $user = User::create([
            'name' => $newData->nama_pegawai,
            'email' => $request->email,
            'password' => Hash::make('password'),
            'pegawai_id' => $newData->id,
        ]);
        $newData->user_id = $user->id;
        $newData->save();

        Alert::success('Berhasil', 'Data berhasil ditambahkan.');
        return redirect()->route('pegawai.index');
    }

    public function edit(String $id)
    {
        $pegawai = Pegawai::find($id);
        $bagians = Bagian::all();
        return view('pegawai.edit', compact('pegawai', 'bagians'));
    }

    public function update(Request $request, Pegawai $pegawai)
    {
        $request->validate([
            'nama_pegawai' => 'required',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'nik' => 'required|numeric|unique:pegawais,nik,' . $pegawai->id,
            'alamat' => 'required',
            'umur' => 'required|numeric',
            'tanggal_lahir' => 'required|date',
            'tempat_lahir' => 'required',
            'jenis_kelamin' => 'required|in:laki-laki,perempuan',
        ], [
            'nama_pegawai.required' => 'Nama pegawai harus diisi.',
            'nik.required' => 'NIK harus diisi.',
            'nik.numeric' => 'NIK harus berupa angka.',
            'nik.unique' => 'NIK sudah terdaftar.',
            'alamat.required' => 'Alamat harus diisi.',
            'umur.required' => 'Umur harus diisi.',
            'umur.numeric' => 'Umur harus berupa angka.',
            'tanggal_lahir.required' => 'Tanggal lahir harus diisi.',
            'tanggal_lahir.date' => 'Tanggal lahir harus berupa tanggal.',
            'tempat_lahir.required' => 'Tempat lahir harus diisi.',
            'jenis_kelamin.required' => 'Jenis kelamin harus diisi.',
            'jenis_kelamin.in' => 'Jenis kelamin harus laki-laki atau perempuan.',
        ]);

        $fileName = $pegawai->foto;
        $foto = $request->file('foto');

        if ($foto) {
            $fileName = Str::uuid() . '.' . $foto->getClientOriginalExtension();
            Storage::disk('public')->putFileAs('foto_pegawai', $foto, $fileName);
        } else {
            $fileName = $pegawai->foto;
        }

        $newRequest = $request->except('nik');
        $newRequest['foto'] = $fileName;

        // $pegawai->update($request->all());

        // $pegawai->update([
        //     'nama_pegawai' => $request->nama_pegawai,
        //     'alamat' => $request->alamat,
        //     'umur' => $request->umur,
        //     'tanggal_lahir' => $request->tanggal_lahir,
        //     'tempat_lahir' => $request->tempat_lahir,
        //     'jenis_kelamin' => $request->jenis_kelamin,
        // ]);

        $pegawai->update($newRequest);

        Alert::success('Berhasil', 'Data berhasil diupdate.');

        return redirect()->route('pegawai.index');
    }

    public function destroy(String $id)
    {
        // 
        $pegawai = Pegawai::find($id);

        if ($pegawai->foto != null) {
            Storage::disk('public')->delete('foto_pegawai/' . $pegawai->foto);
        }

        $pegawai->delete();
        Alert::success('Berhasil', 'Data berhasil dihapus.');
        return redirect()->route('pegawai.index');
    }
}
