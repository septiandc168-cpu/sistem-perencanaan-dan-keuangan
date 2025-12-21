<?php

namespace App\Http\Controllers;

use App\Models\Bagian;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;
use RealRashid\SweetAlert\Toaster;

class BagianController extends Controller
{
    public function index()
    {
        $bagians = Bagian::all();
        $title = 'Konfirmasi Hapus Data Bagian';
        $text = "Data akan dihapus secara permanen, Lanjutkan?";
        confirmDelete($title, $text);
        return view('bagian.index', compact('bagians'));

    }

    public function show(String $id)
    {
        $bagian = Bagian::find($id);
        return view('bagian.show', compact('bagian'));
    }

    public function destroy(String $id)
    {
        $bagian = Bagian::find($id);
        $bagian->delete();

        // Alert::success('Berhasil', 'Data berhasil dihapus');
        toast('Data berhasil dihapus', 'success');
        return redirect()->route('bagian.index');
    }
}
