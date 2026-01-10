<?php

namespace App\Http\Controllers;

use App\Models\RencanaKegiatan;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Auth\Access\AuthorizationException;
use Carbon\Carbon;
use RealRashid\SweetAlert\Facades\Alert;
use RealRashid\SweetAlert\Toaster;

class RencanaKegiatanController extends Controller
{
    public function index(Request $request)
    {
        $rencanaKegiatans = RencanaKegiatan::with('laporanKegiatan')->get();
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

        return view('rencana_kegiatan.index', compact('rencanaKegiatans'));
    }

    public function create()
    {
        return view('rencana_kegiatan.create');
    }

    public function store(Request $request)
    {
        $user = auth()->user();
        $isSupervisor = $user->role->role_name === 'supervisor';
        $isAdmin = $user->role->role_name === 'admin';

        // Different validation rules based on role
        if ($isSupervisor) {
            // Supervisor can change status and must provide keterangan for approve/reject
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
                'rincian_kebutuhan' => 'nullable|string',
                'status' => 'required|in:diajukan,disetujui,ditolak,selesai',
                'keterangan_status' => 'required_if:status,disetujui,ditolak|string',
                'foto' => 'nullable|array',
                'foto.*' => 'image|mimes:jpg,jpeg,png|max:4096',
                'dokumen' => 'nullable|array',
                'dokumen.*' => 'file|mimes:pdf,doc,docx|max:5120',
            ];
            
            $messages = [
                'nama_kegiatan.required' => 'Nama kegiatan wajib diisi.',
                'jenis_kegiatan.required' => 'Jenis kegiatan wajib dipilih.',
                'lat.required' => 'Latitude lokasi wajib diisi.',
                'lng.required' => 'Longitude lokasi wajib diisi.',
                'status.required' => 'Status wajib dipilih.',
                'status.in' => 'Status tidak valid.',
                'keterangan_status.required_if' => 'Keterangan status wajib diisi saat menyetujui atau menolak.',
                'tanggal_mulai.date' => 'Format tanggal mulai tidak valid.',
                'tanggal_selesai.date' => 'Format tanggal selesai tidak valid.',
            ];
        } else {
            // Admin cannot change status and no keterangan field
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
                'rincian_kebutuhan' => 'nullable|string',
                'foto' => 'nullable|array',
                'foto.*' => 'image|mimes:jpg,jpeg,png|max:4096',
                'dokumen' => 'nullable|array',
                'dokumen.*' => 'file|mimes:pdf,doc,docx|max:5120',
            ];
            
            $messages = [
                'nama_kegiatan.required' => 'Nama kegiatan wajib diisi.',
                'jenis_kegiatan.required' => 'Jenis kegiatan wajib dipilih.',
                'lat.required' => 'Latitude lokasi wajib diisi.',
                'lng.required' => 'Longitude lokasi wajib diisi.',
                'tanggal_mulai.date' => 'Format tanggal mulai tidak valid.',
                'tanggal_selesai.date' => 'Format tanggal selesai tidak valid.',
            ];
        }

        $validated = $request->validate($rules, $messages);

        $fotoPaths = [];

        if ($request->hasFile('foto')) {
            foreach ($request->file('foto') as $file) {
                $fotoPaths[] = $file->store('rencana_kegiatans', 'public');
            }
        }

        $dokumenPaths = [];
        if ($request->hasFile('dokumen')) {
            foreach ($request->file('dokumen') as $file) {
                $dokumenPaths[] = $file->store('rencana_kegiatans/dokumen', 'public');
            }
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

        // map incoming fields to RencanaKegiatan structure
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
            'rincian_kebutuhan' => $validated['rincian_kebutuhan'] ?? null,
            'foto' => !empty($fotoPaths) ? $fotoPaths : null,
            'dokumen' => !empty($dokumenPaths) ? $dokumenPaths : null,
            'status' => 'diajukan',
        ];

        RencanaKegiatan::create($data);

        // Alert::success('Berhasil', 'Rencana kegiatan berhasil disimpan!');
        toast('Rencana kegiatan berhasil disimpan!', 'success');
        return redirect()->route('rencana_kegiatan.index');
    }

    /**
     * Public front map view showing markers.
     */
    public function frontIndex()
    {
        $rencanaKegiatans = RencanaKegiatan::whereNotNull('lat')->whereNotNull('lng')->get();
        return view('rencana_kegiatan.front_index', compact('rencanaKegiatans'));
    }

    public function show(RencanaKegiatan $rencana_kegiatan)
    {
        $rencana_kegiatan->load('laporanKegiatan');
        return view('rencana_kegiatan.show', compact('rencana_kegiatan'));
    }

    public function edit(RencanaKegiatan $rencana_kegiatan)
    {
        // Check authorization
        $this->authorize('update', $rencana_kegiatan);
        
        return view('rencana_kegiatan.edit', compact('rencana_kegiatan'));
    }

    public function update(Request $request, RencanaKegiatan $rencana_kegiatan)
    {
        // Check authorization
        $this->authorize('update', $rencana_kegiatan);
        
        $user = auth()->user();
        $isSupervisor = $user->role->role_name === 'supervisor';
        $isAdmin = $user->role->role_name === 'admin';
        Log::info('RencanaKegiatanController@update called', ['id' => $rencana_kegiatan->id, 'input' => $request->all()]);

        // Different validation rules based on role
        if ($isSupervisor) {
            // Supervisor can change status and must provide keterangan for approve/reject
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
                'rincian_kebutuhan' => 'nullable|string',
                'status' => 'required|in:diajukan,disetujui,ditolak,selesai',
                'keterangan_status' => 'required_if:status,disetujui,ditolak|string',
                'foto' => 'nullable|image|max:4096',
                'dokumen' => 'nullable|file|mimes:pdf,doc,docx|max:5120',
            ];
            
            $messages = [
                'nama_kegiatan.required' => 'Nama kegiatan wajib diisi.',
                'jenis_kegiatan.required' => 'Jenis kegiatan wajib dipilih.',
                'lat.required' => 'Latitude lokasi wajib diisi.',
                'lng.required' => 'Longitude lokasi wajib diisi.',
                'status.required' => 'Status wajib dipilih.',
                'status.in' => 'Status tidak valid.',
                'keterangan_status.required_if' => 'Keterangan status wajib diisi saat menyetujui atau menolak.',
                'tanggal_mulai.date' => 'Format tanggal mulai tidak valid.',
                'tanggal_selesai.date' => 'Format tanggal selesai tidak valid.',
            ];
        } else {
            // Admin cannot change status and no keterangan field
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
                'rincian_kebutuhan' => 'nullable|string',
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
        }

        $validated = $request->validate($rules, $messages);

        // Ambil foto lama (karena casts array)
        $fotoLama = $request->input('foto_lama', []);

        $fotoLama = is_array($fotoLama) ? $fotoLama : [];

        // Upload foto baru
        $fotoBaru = [];
        if ($request->hasFile('foto')) {
            foreach ($request->file('foto') as $file) {
                $fotoBaru[] = $file->store('rencana_kegiatans', 'public');
            }
        }

        $fotoSebelumnya = $rencana_kegiatan->foto ?? [];
        $fotoDihapus = array_diff($fotoSebelumnya, $fotoLama);

        foreach ($fotoDihapus as $path) {
            Storage::disk('public')->delete($path);
        }

        // Gabungkan foto lama + baru
        $semuaFoto = array_merge($fotoLama, $fotoBaru);

        // Simpan ke validated
        if (!empty($semuaFoto)) {
            $validated['foto'] = json_encode($semuaFoto);
        }

        $dokumenLama = $request->input('dokumen_lama', []);
        $dokumenLama = is_array($dokumenLama) ? $dokumenLama : [];

        $dokumenBaru = [];
        if ($request->hasFile('dokumen')) {
            foreach ($request->file('dokumen') as $file) {
                $dokumenBaru[] = $file->store('rencana_kegiatans/docs', 'public');
            }
        }

        $dokumenDihapus = array_diff($rencana_kegiatan->dokumen ?? [], $dokumenLama);
        foreach ($dokumenDihapus as $path) {
            Storage::disk('public')->delete($path);
        }

        $semuaDokumen = array_merge($dokumenLama, $dokumenBaru);

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

        // Handle file uploads
        if ($request->hasFile('foto')) {
            $validated['foto'] = $request->file('foto')->store('rencana_kegiatans', 'public');
        }
        if ($request->hasFile('dokumen')) {
            $validated['dokumen'] = $request->file('dokumen')->store('rencana_kegiatans/docs', 'public');
        }

        // Handle date swapping
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

        // Prepare update data based on role
        if ($isSupervisor) {
            // Supervisor can update all fields including status and keterangan
            $data = [
                'judul' => $validated['nama_kegiatan'] ?? $rencana_kegiatan->judul,
                'nama_kegiatan' => $validated['nama_kegiatan'],
                'jenis_kegiatan' => $validated['jenis_kegiatan'],
                'kategori' => $validated['jenis_kegiatan'],
                'deskripsi' => $validated['deskripsi'] ?? null,
                'tujuan' => $validated['tujuan'] ?? null,
                'lat' => $validated['lat'],
                'lng' => $validated['lng'],
                'desa' => $validated['desa'] ?? null,
                'tanggal_mulai' => $validated['tanggal_mulai'] ?? null,
                'tanggal_selesai' => $validated['tanggal_selesai'] ?? null,
                'penanggung_jawab' => $validated['penanggung_jawab'] ?? null,
                'kelompok' => $validated['kelompok'] ?? null,
                'estimasi_peserta' => $validated['estimasi_peserta'] ?? null,
                'rincian_kebutuhan' => $validated['rincian_kebutuhan'] ?? null,
                'status' => $validated['status'],
                'keterangan_status' => $validated['keterangan_status'] ?? null,
                'foto' => $validated['foto'] ?? $rencana_kegiatan->foto,
                'dokumen' => $validated['dokumen'] ?? $rencana_kegiatan->dokumen,
            ];
        } else {
            // Admin revisi: reset status to 'diajukan' and clear keterangan
            $data = [
                'judul' => $validated['nama_kegiatan'] ?? $rencana_kegiatan->judul,
                'nama_kegiatan' => $validated['nama_kegiatan'],
                'jenis_kegiatan' => $validated['jenis_kegiatan'],
                'kategori' => $validated['jenis_kegiatan'],
                'deskripsi' => $validated['deskripsi'] ?? null,
                'tujuan' => $validated['tujuan'] ?? null,
                'lat' => $validated['lat'],
                'lng' => $validated['lng'],
                'desa' => $validated['desa'] ?? null,
                'tanggal_mulai' => $validated['tanggal_mulai'] ?? null,
                'tanggal_selesai' => $validated['tanggal_selesai'] ?? null,
                'penanggung_jawab' => $validated['penanggung_jawab'] ?? null,
                'kelompok' => $validated['kelompok'] ?? null,
                'estimasi_peserta' => $validated['estimasi_peserta'] ?? null,
                'rincian_kebutuhan' => $validated['rincian_kebutuhan'] ?? null,
                'status' => RencanaKegiatan::STATUS_DIAJUKAN, // Reset to diajukan for admin revisi
                'keterangan_status' => null, // Clear keterangan
                'foto' => $validated['foto'] ?? $rencana_kegiatan->foto,
                'dokumen' => $validated['dokumen'] ?? $rencana_kegiatan->dokumen,
            ];
        }

        $rencana_kegiatan->update($data);

        $message = $isSupervisor 
            ? 'Rencana kegiatan berhasil diperbarui!' 
            : 'Rencana kegiatan berhasil direvisi dan diajukan ulang!';
        
        toast($message, 'success');
        return redirect()->route('rencana_kegiatan.index');
    }

    public function destroy(RencanaKegiatan $rencana_kegiatan)
    {
        // remove files
        if (!empty($rencana_kegiatan->foto) && is_array($rencana_kegiatan->foto)) {
            foreach ($rencana_kegiatan->foto as $path) {
                if ($path && Storage::disk('public')->exists($path)) {
                    Storage::disk('public')->delete($path);
                }
            }
        }
        if (!empty($rencana_kegiatan->dokumen) && is_array($rencana_kegiatan->dokumen)) {
            foreach ($rencana_kegiatan->dokumen as $path) {
                if ($path && Storage::disk('public')->exists($path)) {
                    Storage::disk('public')->delete($path);
                }
            }
        }

        $rencana_kegiatan->delete();
        // Alert::success('Berhasil', 'Rencana kegiatan berhasil dihapus.');
        toast('Rencana kegiatan berhasil dihapus.', 'success');
        return redirect()->route('rencana_kegiatan.index');
    }
}
