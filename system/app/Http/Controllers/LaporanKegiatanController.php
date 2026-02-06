<?php

namespace App\Http\Controllers;

use App\Models\LaporanKegiatan;
use App\Models\RencanaKegiatan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;

class LaporanKegiatanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $laporans = LaporanKegiatan::with('rencanaKegiatan')
            ->orderBy('updated_at', 'desc')
            ->get();
        // Provide SweetAlert delete configuration so the frontend can
        // show a confirmation dialog when links with
        // `data-confirm-delete` are clicked.
        $confirm = [
            'title' => 'Konfirmasi Hapus',
            'text' => 'Data akan terhapus secara permanen. Lanjutkan?',
            'icon' => config('sweetalert.confirm_delete_icon', 'warning'),
            'showCancelButton' => config('sweetalert.confirm_delete_show_cancel_button', true),
            'confirmButtonText' => config('sweetalert.confirm_delete_confirm_button_text', 'Yes, delete it!'),
            'cancelButtonText' => config('sweetalert.confirm_delete_cancel_button_text', 'Cancel'),
            'confirmButtonColor' => config('sweetalert.confirm_delete_confirm_button_color', null),
            'cancelButtonColor' => config('sweetalert.confirm_delete_cancel_button_color', '#d33'),
            'showCloseButton' => config('sweetalert.confirm_delete_show_close_button', false),
            'showLoaderOnConfirm' => config('sweetalert.confirm_delete_show_loader_on_confirm', true),
        ];

        session()->flash('alert.delete', json_encode($confirm, JSON_UNESCAPED_SLASHES));

        return view('laporan_kegiatan.index', compact('laporans'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $rencanaKegiatanId = $request->get('rencana_kegiatan_id');

        if (!$rencanaKegiatanId) {
            return redirect()->route('rencana_kegiatan.index')
                ->with('error', 'Rencana kegiatan tidak ditemukan');
        }

        $rencanaKegiatan = RencanaKegiatan::where('uuid', $rencanaKegiatanId)->firstOrFail();

        // Check if rencana kegiatan is completed
        if ($rencanaKegiatan->status !== RencanaKegiatan::STATUS_SELESAI) {
            return redirect()->route('rencana_kegiatan.show', $rencanaKegiatan)
                ->with('error', 'Laporan hanya bisa dibuat untuk rencana kegiatan dengan status "Selesai"');
        }

        // Check if laporan already exists
        if ($rencanaKegiatan->hasLaporan()) {
            return redirect()->route('laporan_kegiatan.show', $rencanaKegiatan->laporanKegiatan)
                ->with('error', 'Laporan untuk rencana kegiatan ini sudah ada');
        }

        return view('laporan_kegiatan.create', compact('rencanaKegiatan'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'rencana_kegiatan_id' => 'required|exists:rencana_kegiatans,uuid',
            'pelaksanaan_kegiatan' => 'required|string|min:10',
            'hasil_kegiatan' => 'required|string|min:10',
            'kendala' => 'nullable|string',
            'evaluasi' => 'nullable|string',
            'dokumentasi' => 'nullable|array|max:5',
            'dokumentasi.*' => 'image|mimes:jpg,jpeg,png|max:2048',
        ], [
            'rencana_kegiatan_id.required' => 'Rencana kegiatan wajib dipilih.',
            'rencana_kegiatan_id.exists' => 'Rencana kegiatan tidak valid.',
            'pelaksanaan_kegiatan.required' => 'Pelaksanaan kegiatan wajib diisi.',
            'pelaksanaan_kegiatan.min' => 'Pelaksanaan kegiatan minimal 10 karakter.',
            'hasil_kegiatan.required' => 'Hasil kegiatan wajib diisi.',
            'hasil_kegiatan.min' => 'Hasil kegiatan minimal 10 karakter.',
            'dokumentasi.max' => 'Maksimal 5 file dokumentasi.',
            'dokumentasi.*.image' => 'Dokumentasi harus berupa gambar.',
            'dokumentasi.*.mimes' => 'Format dokumentasi harus jpg, jpeg, atau png.',
            'dokumentasi.*.max' => 'Ukuran maksimal file dokumentasi 2MB.',
        ]);

        $rencanaKegiatan = RencanaKegiatan::findOrFail($request->rencana_kegiatan_id);

        // Double check if laporan can be created
        if (!LaporanKegiatan::canCreateFor($rencanaKegiatan)) {
            throw ValidationException::withMessages([
                'rencana_kegiatan_id' => 'Laporan tidak dapat dibuat untuk rencana kegiatan ini. ' .
                    ($rencanaKegiatan->status !== RencanaKegiatan::STATUS_SELESAI
                        ? 'Status rencana kegiatan harus "Selesai".'
                        : 'Laporan sudah ada.')
            ]);
        }

        // Handle file uploads
        $dokumentasiPaths = [];
        if ($request->hasFile('dokumentasi')) {
            foreach ($request->file('dokumentasi') as $file) {
                $dokumentasiPaths[] = $file->store('laporan_kegiatan/dokumentasi', 'public');
            }
        }

        $laporan = LaporanKegiatan::create([
            'rencana_kegiatan_id' => $request->rencana_kegiatan_id,
            'pelaksanaan_kegiatan' => $request->pelaksanaan_kegiatan,
            'hasil_kegiatan' => $request->hasil_kegiatan,
            'kendala' => $request->kendala,
            'evaluasi' => $request->evaluasi,
            'dokumentasi' => !empty($dokumentasiPaths) ? $dokumentasiPaths : null,
        ]);

        toast('Laporan kegiatan berhasil disimpan!', 'success');
        return redirect()->route('laporan_kegiatan.index', $laporan);
    }

    /**
     * Display the specified resource.
     */
    public function show(LaporanKegiatan $laporanKegiatan)
    {
        $laporanKegiatan->load('rencanaKegiatan');
        return view('laporan_kegiatan.show', compact('laporanKegiatan'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(LaporanKegiatan $laporanKegiatan)
    {
        $laporanKegiatan->load('rencanaKegiatan');
        return view('laporan_kegiatan.edit', compact('laporanKegiatan'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, LaporanKegiatan $laporanKegiatan)
    {
        $request->validate([
            'pelaksanaan_kegiatan' => 'required|string|min:10',
            'hasil_kegiatan' => 'required|string|min:10',
            'kendala' => 'nullable|string',
            'evaluasi' => 'nullable|string',
            'dokumentasi' => 'nullable|array|max:5',
            'dokumentasi.*' => 'image|mimes:jpg,jpeg,png|max:2048',
            'remove_dokumentasi' => 'nullable|array',
            'remove_dokumentasi.*' => 'string',
        ], [
            'pelaksanaan_kegiatan.required' => 'Pelaksanaan kegiatan wajib diisi.',
            'pelaksanaan_kegiatan.min' => 'Pelaksanaan kegiatan minimal 10 karakter.',
            'hasil_kegiatan.required' => 'Hasil kegiatan wajib diisi.',
            'hasil_kegiatan.min' => 'Hasil kegiatan minimal 10 karakter.',
            'dokumentasi.max' => 'Maksimal 5 file dokumentasi.',
            'dokumentasi.*.image' => 'Dokumentasi harus berupa gambar.',
            'dokumentasi.*.mimes' => 'Format dokumentasi harus jpg, jpeg, atau png.',
            'dokumentasi.*.max' => 'Ukuran maksimal file dokumentasi 2MB.',
        ]);

        // Handle file removals
        $currentDokumentasi = $laporanKegiatan->dokumentasi ?? [];
        $removeDokumentasi = $request->input('remove_dokumentasi', []);

        if (!empty($removeDokumentasi)) {
            foreach ($removeDokumentasi as $path) {
                if (in_array($path, $currentDokumentasi)) {
                    Storage::disk('public')->delete($path);
                    $currentDokumentasi = array_diff($currentDokumentasi, [$path]);
                }
            }
        }

        // Handle new file uploads
        $newDokumentasiPaths = [];
        if ($request->hasFile('dokumentasi')) {
            foreach ($request->file('dokumentasi') as $file) {
                $newDokumentasiPaths[] = $file->store('laporan_kegiatan/dokumentasi', 'public');
            }
        }

        // Merge existing and new documentation
        $finalDokumentasi = array_merge($currentDokumentasi, $newDokumentasiPaths);

        $laporanKegiatan->update([
            'pelaksanaan_kegiatan' => $request->pelaksanaan_kegiatan,
            'hasil_kegiatan' => $request->hasil_kegiatan,
            'kendala' => $request->kendala,
            'evaluasi' => $request->evaluasi,
            'dokumentasi' => !empty($finalDokumentasi) ? array_values($finalDokumentasi) : null,
        ]);

        toast('Laporan kegiatan berhasil diperbarui!', 'success');
        return redirect()->route('laporan_kegiatan.index', $laporanKegiatan);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(LaporanKegiatan $laporanKegiatan)
    {
        // Delete documentation files
        if (!empty($laporanKegiatan->dokumentasi)) {
            foreach ($laporanKegiatan->dokumentasi as $path) {
                Storage::disk('public')->delete($path);
            }
        }

        $laporanKegiatan->delete();

        toast('Laporan kegiatan berhasil dihapus.', 'success');
        return redirect()->route('laporan_kegiatan.index');
    }

    /**
     * Print the specified laporan.
     */
    public function print(LaporanKegiatan $laporanKegiatan)
    {
        $this->authorize('print', $laporanKegiatan);

        $laporanKegiatan->load('rencanaKegiatan');

        return view('laporan_kegiatan.print', compact('laporanKegiatan'));
    }
}
