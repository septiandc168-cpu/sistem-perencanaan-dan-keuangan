<?php

namespace App\Http\Controllers;

use App\Models\Report;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use RealRashid\SweetAlert\Facades\Alert;

class MapController extends Controller
{
    public function index(Request $request)
    {
        $reports = Report::all();
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

        return view('maps.index', compact('reports'));
    }

    public function create()
    {
        return view('maps.create');
    }

    public function store(Request $request)
    {
        $rules = [
            'nama_kegiatan' => 'required|string',
            'jenis_kegiatan' => 'required|string',
            'deskripsi' => 'nullable|string',
            'tujuan' => 'nullable|string',
            'lat' => 'required|numeric',
            'lng' => 'required|numeric',
            'desa' => 'nullable|string',
            'tanggal_mulai' => 'nullable|date',
            'tanggal_selesai' => 'nullable|date',
            'penanggung_jawab' => 'nullable|string',
            'kelompok' => 'nullable|string',
            'estimasi_peserta' => 'nullable|integer',
            'estimasi_anggaran' => 'nullable|numeric',
            'foto' => 'nullable|image|max:4096',
            'dokumen' => 'nullable|file|mimes:pdf,doc,docx|max:5120',
        ];

        $messages = [
            'nama_kegiatan.required' => 'Nama kegiatan wajib diisi.',
            'jenis_kegiatan.required' => 'Jenis kegiatan wajib dipilih.',
            'lat.required' => 'Latitude lokasi wajib diisi.',
            'lng.required' => 'Longitude lokasi wajib diisi.',
            'tanggal_mulai.date' => 'Format tanggal mulai tidak valid.',
            'tanggal_selesai.date' => 'Format tanggal selesai tidak valid.',
        ];

        $validated = $request->validate($rules, $messages);

        if ($request->hasFile('foto')) {
            $validated['foto'] = $request->file('foto')->store('reports', 'public');
        }
        if ($request->hasFile('dokumen')) {
            $validated['dokumen'] = $request->file('dokumen')->store('reports/docs', 'public');
        }

        // if both dates present and end before start, swap them automatically
        if (!empty($validated['tanggal_mulai']) && !empty($validated['tanggal_selesai'])) {
            try {
                $d1 = Carbon::parse($validated['tanggal_mulai']);
                $d2 = Carbon::parse($validated['tanggal_selesai']);
                if ($d2->lt($d1)) {
                    // swap
                    $tmp = $validated['tanggal_mulai'];
                    $validated['tanggal_mulai'] = $validated['tanggal_selesai'];
                    $validated['tanggal_selesai'] = $tmp;
                }
            } catch (\Exception $e) {
                // ignore parse errors here; validation already ensured date format
            }
        }

        // map incoming fields to Report structure
        $data = [
            'judul' => $validated['nama_kegiatan'] ?? ($validated['judul'] ?? null),
            'nama_kegiatan' => $validated['nama_kegiatan'] ?? null,
            'jenis_kegiatan' => $validated['jenis_kegiatan'] ?? null,
            'kategori' => $validated['jenis_kegiatan'] ?? $validated['kategori'] ?? null,
            'deskripsi' => $validated['deskripsi'] ?? null,
            'tujuan' => $validated['tujuan'] ?? null,
            'lat' => $validated['lat'] ?? null,
            'lng' => $validated['lng'] ?? null,
            'desa' => $validated['desa'] ?? null,
            'tanggal_mulai' => $validated['tanggal_mulai'] ?? null,
            'tanggal_selesai' => $validated['tanggal_selesai'] ?? null,
            'penanggung_jawab' => $validated['penanggung_jawab'] ?? null,
            'kelompok' => $validated['kelompok'] ?? null,
            'estimasi_peserta' => $validated['estimasi_peserta'] ?? null,
            'estimasi_anggaran' => $validated['estimasi_anggaran'] ?? null,
            'foto' => $validated['foto'] ?? null,
            'dokumen' => $validated['dokumen'] ?? null,
            'status' => 'direncanakan',
        ];

        Report::create($data);

        Alert::success('Berhasil', 'Rencana kegiatan berhasil disimpan!');
        return redirect()->route('maps.index');
    }

    /**
     * Public front map view showing markers.
     */
    public function frontIndex()
    {
        $reports = Report::whereNotNull('lat')->whereNotNull('lng')->get();
        return view('maps.front_index', compact('reports'));
    }

    public function show(Report $map)
    {
        return view('maps.show', ['report' => $map]);
    }

    public function edit(Report $map)
    {
        return view('maps.edit', ['report' => $map]);
    }

    public function update(Request $request, Report $map)
    {
        Log::info('MapController@update called', ['id' => $map->id, 'input' => $request->all()]);

        $rules = [
            'nama_kegiatan' => 'required|string',
            'jenis_kegiatan' => 'required|string',
            'deskripsi' => 'nullable|string',
            'tujuan' => 'nullable|string',
            'lat' => 'required|numeric',
            'lng' => 'required|numeric',
            'desa' => 'nullable|string',
            'tanggal_mulai' => 'nullable|date',
            'tanggal_selesai' => 'nullable|date',
            'penanggung_jawab' => 'nullable|string',
            'kelompok' => 'nullable|string',
            'estimasi_peserta' => 'nullable|integer',
            'estimasi_anggaran' => 'nullable|numeric',
            'status' => 'required|string',
            'foto' => 'nullable|image|max:4096',
            'dokumen' => 'nullable|file|mimes:pdf,doc,docx|max:5120',
        ];

        $messages = [
            'nama_kegiatan.required' => 'Nama kegiatan wajib diisi.',
            'jenis_kegiatan.required' => 'Jenis kegiatan wajib dipilih.',
            'lat.required' => 'Latitude lokasi wajib diisi.',
            'lng.required' => 'Longitude lokasi wajib diisi.',
            'tanggal_mulai.date' => 'Format tanggal mulai tidak valid.',
            'tanggal_selesai.date' => 'Format tanggal selesai tidak valid.',
        ];

        $validated = $request->validate($rules, $messages);

        if ($request->hasFile('foto')) {
            // remove old photo
            if ($map->foto) {
                Storage::disk('public')->delete($map->foto);
            }
            $validated['foto'] = $request->file('foto')->store('reports', 'public');
        }
        if ($request->hasFile('dokumen')) {
            if ($map->dokumen) {
                Storage::disk('public')->delete($map->dokumen);
            }
            $validated['dokumen'] = $request->file('dokumen')->store('reports/docs', 'public');
        }

        // if both dates present and end before start, swap them automatically
        if (!empty($validated['tanggal_mulai']) && !empty($validated['tanggal_selesai'])) {
            try {
                $d1 = Carbon::parse($validated['tanggal_mulai']);
                $d2 = Carbon::parse($validated['tanggal_selesai']);
                if ($d2->lt($d1)) {
                    $tmp = $validated['tanggal_mulai'];
                    $validated['tanggal_mulai'] = $validated['tanggal_selesai'];
                    $validated['tanggal_selesai'] = $tmp;
                }
            } catch (\Exception $e) {
                // ignore parse errors
            }
        }

        $data = [
            'judul' => $validated['nama_kegiatan'] ?? ($validated['judul'] ?? $map->judul),
            'nama_kegiatan' => $validated['nama_kegiatan'] ?? $map->nama_kegiatan,
            'jenis_kegiatan' => $validated['jenis_kegiatan'] ?? $map->jenis_kegiatan,
            'kategori' => $validated['jenis_kegiatan'] ?? $map->kategori,
            'deskripsi' => $validated['deskripsi'] ?? $map->deskripsi,
            'tujuan' => $validated['tujuan'] ?? $map->tujuan,
            'lat' => $validated['lat'] ?? $map->lat,
            'lng' => $validated['lng'] ?? $map->lng,
            'desa' => $validated['desa'] ?? $map->desa,
            'tanggal_mulai' => $validated['tanggal_mulai'] ?? $map->tanggal_mulai,
            'tanggal_selesai' => $validated['tanggal_selesai'] ?? $map->tanggal_selesai,
            'penanggung_jawab' => $validated['penanggung_jawab'] ?? $map->penanggung_jawab,
            'kelompok' => $validated['kelompok'] ?? $map->kelompok,
            'estimasi_peserta' => $validated['estimasi_peserta'] ?? $map->estimasi_peserta,
            'estimasi_anggaran' => $validated['estimasi_anggaran'] ?? $map->estimasi_anggaran,
            'status' => $validated['status'] ?? $map->status,
        ];

        if (isset($validated['foto'])) $data['foto'] = $validated['foto'];
        if (isset($validated['dokumen'])) $data['dokumen'] = $validated['dokumen'];

        $map->update($data);

        Alert::success('Berhasil', 'Rencana kegiatan berhasil diperbarui!');
        return redirect()->route('maps.index');
    }

    public function destroy(Report $map)
    {
        // remove files
        if ($map->foto) {
            Storage::disk('public')->delete($map->foto);
        }
        if ($map->dokumen) {
            Storage::disk('public')->delete($map->dokumen);
        }

        $map->delete();
        Alert::success('Berhasil', 'Rencana kegiatan berhasil dihapus.');
        return redirect()->route('maps.index');
    }
}
