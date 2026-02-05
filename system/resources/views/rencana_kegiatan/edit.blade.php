@extends('layouts.adminlte')

@section('content_title', 'Edit Rencana Kegiatan')

@section('content')
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h5 class="mb-0">Form Rencana Kegiatan</h5>
        </div>

        <form id="rencana-kegiatan-form" action="{{ route('rencana_kegiatan.update', $rencana_kegiatan) }}" method="POST"
            enctype="multipart/form-data">
            @csrf
            @method('PUT')
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label">Koordinat Lokasi</label>
                        <div class="input-group">
                            <input type="text" id="location_lat" name="lat" class="form-control"
                                placeholder="Latitude" readonly required value="{{ old('lat', $rencana_kegiatan->lat) }}">
                            <input type="text" id="location_lng" name="lng" class="form-control"
                                placeholder="Longitude" readonly required value="{{ old('lng', $rencana_kegiatan->lng) }}">
                            <button type="button" id="use-location" class="btn btn-outline-secondary">Gunakan Lokasi
                                Saya</button>
                        </div>
                        <small class="form-text text-muted">Pilih lokasi dengan mengklik pada peta atau gunakan lokasi Anda
                            saat ini.</small>
                    </div>

                    <div class="mb-3" id="map-create"
                        style="width:100%; height:70vh; border:1px solid #ddd; border-radius:4px;">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Nama Kegiatan</label>
                        <input type="text" name="nama_kegiatan" class="form-control" placeholder="Nama kegiatan"
                            value="{{ old('nama_kegiatan', $rencana_kegiatan->nama_kegiatan) }}" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Jenis Kegiatan</label>
                        <select name="jenis_kegiatan" class="form-select" required>
                            <option value="">Pilih jenis kegiatan</option>
                            @php
                                $currentJenis = old('jenis_kegiatan', $rencana_kegiatan->jenis_kegiatan ?? '');
                                $cj = strtolower(trim($currentJenis));
                                $predefined = ['konservasi', 'usaha masyarakat', 'edukasi', 'lainnya'];
                            @endphp
                            @if ($currentJenis && !in_array($cj, $predefined))
                                <option value="{{ $currentJenis }}" selected>{{ $currentJenis }}</option>
                            @endif
                            <option value="konservasi" {{ $cj == 'konservasi' ? 'selected' : '' }}>Konservasi</option>
                            <option value="usaha masyarakat" {{ $cj == 'usaha masyarakat' ? 'selected' : '' }}>Usaha
                                Masyarakat</option>
                            <option value="edukasi" {{ $cj == 'edukasi' ? 'selected' : '' }}>Edukasi</option>
                            <option value="lainnya" {{ $cj == 'lainnya' ? 'selected' : '' }}>Lainnya</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Deskripsi</label>
                        <textarea name="deskripsi" class="form-control" rows="3">{{ old('deskripsi', $rencana_kegiatan->deskripsi) }}</textarea>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Tujuan</label>
                        <textarea name="tujuan" class="form-control" rows="2">{{ old('tujuan', $rencana_kegiatan->tujuan) }}</textarea>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Desa / Wilayah</label>
                        <input type="text" name="desa" class="form-control" placeholder="Nama desa atau wilayah"
                            value="{{ old('desa', $rencana_kegiatan->desa) }}">
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Tanggal Mulai</label>
                            <input type="date" name="tanggal_mulai" class="form-control"
                                value="{{ old('tanggal_mulai', $rencana_kegiatan->tanggal_mulai ? \Carbon\Carbon::parse($rencana_kegiatan->tanggal_mulai)->format('Y-m-d') : null) }}">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Tanggal Selesai</label>
                            <input type="date" name="tanggal_selesai" class="form-control"
                                value="{{ old('tanggal_selesai', $rencana_kegiatan->tanggal_selesai ? \Carbon\Carbon::parse($rencana_kegiatan->tanggal_selesai)->format('Y-m-d') : null) }}">
                        </div>
                    </div>



                    <div class="mb-3">
                        <label class="form-label">Penanggung Jawab</label>
                        <input type="text" name="penanggung_jawab" class="form-control"
                            placeholder="Nama Penanggung Jawab"
                            value="{{ old('penanggung_jawab', $rencana_kegiatan->penanggung_jawab) }}">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Kelompok / Komunitas Pelaksana</label>
                        <input type="text" name="kelompok" class="form-control" placeholder="Nama kelompok"
                            value="{{ old('kelompok', $rencana_kegiatan->kelompok) }}">
                    </div>

                </div>

                <div class="col-md-6">

                    <div class="mb-3">
                        <label class="form-label">Estimasi Jumlah Peserta</label>
                        <input type="number" name="estimasi_peserta" class="form-control" min="0"
                            value="{{ old('estimasi_peserta', $rencana_kegiatan->estimasi_peserta) }}">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Rincian Kebutuhan</label>
                        <textarea type="text" name="rincian_kebutuhan" class="form-control" id="summernote">{!! old('rincian_kebutuhan', $rencana_kegiatan->rincian_kebutuhan ?? '') !!}</textarea>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Status</label>
                        @if (auth()->user()->role->role_name === 'supervisor')
                            <select name="status" class="form-select" required>
                                <option value="">-- Pilih Status --</option>
                                @php
                                    $currentStatus = old('status', $rencana_kegiatan->status ?? '');
                                @endphp
                                <option value="diajukan" {{ $currentStatus == 'diajukan' ? 'selected' : '' }}>
                                    Diajukan
                                </option>
                                <option value="disetujui" {{ $currentStatus == 'disetujui' ? 'selected' : '' }}>
                                    Disetujui
                                </option>
                                <option value="ditolak" {{ $currentStatus == 'ditolak' ? 'selected' : '' }}>
                                    Ditolak
                                </option>
                                <option value="selesai" {{ $currentStatus == 'selesai' ? 'selected' : '' }}>
                                    Selesai
                                </option>
                            </select>
                        @else
                            <input type="hidden" name="status"
                                value="{{ old('status', $rencana_kegiatan->status ?? '') }}">
                            <div class="form-control bg-light" readonly>
                                {{ \App\Models\RencanaKegiatan::getStatusOptions()[old('status', $rencana_kegiatan->status ?? 'diajukan')] ?? 'Diajukan' }}
                            </div>
                            {{-- <small class="form-text text-muted">Status hanya dapat diubah oleh supervisor</small> --}}
                        @endif
                    </div>

                    @if (auth()->user()->role->role_name === 'supervisor')
                        @php
                            $currentStatus = old('status', $rencana_kegiatan->status ?? '');
                            $showKeterangan = in_array($currentStatus, ['disetujui', 'ditolak']);
                        @endphp
                        <div class="keterangan-status-container"
                            style="{{ $showKeterangan ? 'display: block;' : 'display: none;' }}">
                            <div class="mb-3">
                                <label class="form-label">
                                    {{ $currentStatus == 'disetujui' ? 'Catatan Persetujuan' : 'Alasan Penolakan' }}
                                </label>
                                <textarea name="keterangan_status" class="form-control" rows="3"
                                    placeholder="{{ $currentStatus == 'disetujui' ? 'Tambahkan catatan persetujuan...' : 'Jelaskan alasan penolakan...' }}"
                                    {{ $showKeterangan ? 'required' : '' }}>{{ old('keterangan_status', $rencana_kegiatan->keterangan_status) }}</textarea>
                            </div>
                        </div>
                    @endif

                    <div class="mb-3">
                        <label class="form-label fw-bold">Unggah Foto</label>
                        {{-- INPUT FOTO BARU --}}
                        <input type="file" name="foto[]" class="form-control" accept="image/*" multiple>
                        @error('foto')
                            <div class="text-danger small">{{ $message }}</div>
                        @enderror
                        <small class="form-text text-muted">Pilih satu atau lebih file foto baru</small>
                    </div>

                    {{-- FOTO LAMA --}}
                    @if (!empty($rencana_kegiatan->foto))
                        @php
                            // Decode JSON string to array if it's a string
$fotos = is_string($rencana_kegiatan->foto)
    ? json_decode($rencana_kegiatan->foto, true)
    : $rencana_kegiatan->foto;
// Ensure it's an array
                            if (!is_array($fotos)) {
                                $fotos = [];
                            }
                        @endphp
                        <div class="mb-3">
                            <label class="form-label fw-bold">Foto Saat Ini</label>
                            <div class="row">
                                @foreach ($fotos as $index => $foto)
                                    @if ($foto)
                                        @php
                                            // Handle format baru (array dengan path dan original_name)
                                            if (is_array($foto)) {
                                                $fotoPath = $foto['path'];
                                                $fotoName = $foto['original_name'];
                                            } else {
                                                // Handle format lama (string path)
                                                $fotoPath = $foto;
                                                $fotoName = 'Foto ' . ($index + 1);
                                            }
                                        @endphp
                                        <div class="col-md-3 mb-3">
                                            <div class="card">
                                                <img src="{{ asset('public/storage/app/' . $fotoPath) }}"
                                                    class="card-img-top"
                                                    style="height: 150px; object-fit: cover; width: 100%;"
                                                    alt="{{ $fotoName }}">

                                                <div class="card-body p-2">
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox"
                                                            name="remove_foto[]" value="{{ $fotoPath }}"
                                                            id="remove_foto_{{ $index }}">
                                                        <label class="form-check-label"
                                                            for="remove_foto_{{ $index }}">
                                                            <small>Hapus</small>
                                                        </label>
                                                    </div>
                                                    <small class="text-muted">{{ $fotoName }}</small>
                                                </div>

                                            </div>
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                            <small class="form-text text-muted">Centang gambar yang ingin dihapus</small>
                        </div>
                    @endif

                    <div class="mb-3">
                        <label class="form-label fw-bold">Unggah Dokumen</label>
                        <input type="file" name="dokumen[]" class="form-control" accept=".pdf,.doc,.docx" multiple>
                        @error('dokumen')
                            <div class="text-danger small">{{ $message }}</div>
                        @enderror
                        <small class="form-text text-muted">Pilih satu atau lebih file dokumen baru (PDF/Word)</small>
                    </div>

                    {{-- DOKUMEN LAMA --}}
                    @if (!empty($rencana_kegiatan->dokumen))
                        @php
                            // Decode JSON string to array if it's a string
$dokumens = is_string($rencana_kegiatan->dokumen)
    ? json_decode($rencana_kegiatan->dokumen, true)
    : $rencana_kegiatan->dokumen;
// Ensure it's an array
                            if (!is_array($dokumens)) {
                                $dokumens = [];
                            }
                        @endphp
                        <div class="mb-3">
                            <label class="form-label fw-bold">Dokumen Saat Ini</label>
                            <div class="row">
                                @foreach ($dokumens as $index => $file)
                                    @if ($file)
                                        @php
                                            // Handle format baru (array dengan path dan original_name)
                                            if (is_array($file)) {
                                                $filePath = $file['path'];
                                                $fileName = $file['original_name'];
                                            } else {
                                                // Handle format lama (string path)
                                                $filePath = $file;
                                                $fileName = basename($file);
                                            }
                                        @endphp
                                        <div class="col-md-4 mb-3">
                                            <div class="card">
                                                <div class="card-body p-3">
                                                    <div class="d-flex align-items-center mb-2">
                                                        <i class="fas fa-file-alt text-secondary me-2"></i>

                                                        <small class="text-truncate">{{ $fileName }}</small>
                                                    </div>
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox"
                                                            name="remove_dokumen[]" value="{{ $filePath }}"
                                                            id="remove_dokumen_{{ $index }}">
                                                        <label class="form-check-label"
                                                            for="remove_dokumen_{{ $index }}">
                                                            <small>Hapus</small>
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                            <small class="form-text text-muted">Centang dokumen yang ingin dihapus</small>
                        </div>
                    @endif

                </div>
                <div class="col-md-12">
                    <div class="d-flex justify-content-between mb-3">
                        <a href="{{ route('rencana_kegiatan.index') }}" class="btn btn-secondary btn-sm"
                            style="height: 35px; display: flex; align-items: center; justify-content: center;">
                            <i class="fas fa-arrow-left mr-1"></i>Kembali
                        </a>
                        <button type="submit" class="btn btn-primary btn-sm"
                            style="height: 35px; display: flex; align-items: center; justify-content: center;">
                            <i class="fas fa-save mr-1"></i>Perbarui
                        </button>
                    </div>
                </div>
            </div>
    </div>
    </form>

    @push('styles')
        <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
        <style>
            #map-create {
                background: #f7fafc;
            }
        </style>
    @endpush

    @push('scripts')
        <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Auto-resize textareas
                const textareas = document.querySelectorAll('textarea');
                textareas.forEach(textarea => {
                    textarea.addEventListener('input', function() {
                        this.style.height = 'auto';
                        this.style.height = this.scrollHeight + 'px';
                    });
                });

                // File validation untuk foto
                const fotoInput = document.querySelector('input[name="foto[]"]');
                const existingFotos = document.querySelectorAll('input[name="remove_foto[]"]');

                if (fotoInput) {
                    fotoInput.addEventListener('change', function() {
                        const files = this.files;
                        const existingCount = existingFotos.length;
                        const maxFiles = 10;
                        const maxSize = 4 * 1024 * 1024; // 4MB
                        const allowedTypes = ['image/jpeg', 'image/jpg', 'image/png'];

                        if (existingCount + files.length > maxFiles) {
                            alert(`Maksimal ${maxFiles} file foto. Saat ini ada ${existingCount} file.`);
                            this.value = '';
                            return;
                        }

                        for (let i = 0; i < files.length; i++) {
                            const file = files[i];

                            if (file.size > maxSize) {
                                alert(`File ${file.name} terlalu besar. Maksimal 4MB per file.`);
                                this.value = '';
                                return;
                            }

                            if (!allowedTypes.includes(file.type)) {
                                alert(`File ${file.name} format tidak didukung. Gunakan format JPG atau PNG.`);
                                this.value = '';
                                return;
                            }
                        }
                    });
                }

                // File validation untuk dokumen
                const dokumenInput = document.querySelector('input[name="dokumen[]"]');
                const existingDokumens = document.querySelectorAll('input[name="remove_dokumen[]"]');

                if (dokumenInput) {
                    dokumenInput.addEventListener('change', function() {
                        const files = this.files;
                        const existingCount = existingDokumens.length;
                        const maxFiles = 5;
                        const maxSize = 5 * 1024 * 1024; // 5MB
                        const allowedTypes = ['application/pdf', 'application/msword',
                            'application/vnd.openxmlformats-officedocument.wordprocessingml.document'
                        ];

                        if (existingCount + files.length > maxFiles) {
                            alert(`Maksimal ${maxFiles} file dokumen. Saat ini ada ${existingCount} file.`);
                            this.value = '';
                            return;
                        }

                        for (let i = 0; i < files.length; i++) {
                            const file = files[i];

                            if (file.size > maxSize) {
                                alert(`File ${file.name} terlalu besar. Maksimal 5MB per file.`);
                                this.value = '';
                                return;
                            }

                            if (!allowedTypes.includes(file.type)) {
                                alert(`File ${file.name} format tidak didukung. Gunakan format PDF atau Word.`);
                                this.value = '';
                                return;
                            }
                        }
                    });
                }

                // Confirm delete
                const deleteBtn = document.querySelector('button[type="submit"]');
                if (deleteBtn) {
                    deleteBtn.addEventListener('click', function(e) {
                        const removeFotos = document.querySelectorAll('input[name="remove_foto[]"]:checked');
                        const removeDokumens = document.querySelectorAll(
                            'input[name="remove_dokumen[]"]:checked');

                        let message = '';
                        if (removeFotos.length > 0) {
                            message += `${removeFotos.length} foto`;
                        }
                        if (removeDokumens.length > 0) {
                            if (message) message += ' dan ';
                            message += `${removeDokumens.length} dokumen`;
                        }

                        if (message) {
                            if (!confirm(`Anda akan menghapus ${message}. Lanjutkan?`)) {
                                e.preventDefault();
                            }
                        }
                    });
                }

                // Initialize map
                const initialLat = parseFloat('{{ $rencana_kegiatan->lat ?? -6.2 }}');
                const initialLng = parseFloat('{{ $rencana_kegiatan->lng ?? 106.816666 }}');
                const map = L.map('map-create').setView([initialLat, initialLng], 12);

                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    maxZoom: 19,
                    attribution: '&copy; OpenStreetMap contributors'
                }).addTo(map);

                // marker user pick
                let pickMarker = null;

                if (!isNaN(initialLat) && !isNaN(initialLng)) {
                    pickMarker = L.marker([initialLat, initialLng]).addTo(map);
                }

                map.on('click', function(e) {
                    const lat = e.latlng.lat;
                    const lng = e.latlng.lng;
                    if (pickMarker) pickMarker.setLatLng(e.latlng);
                    else pickMarker = L.marker(e.latlng).addTo(map);
                    document.getElementById('location_lat').value = lat.toFixed(6);
                    document.getElementById('location_lng').value = lng.toFixed(6);
                });

                // geolocation button
                const useLocationBtn = document.getElementById('use-location');
                if (useLocationBtn) {
                    useLocationBtn.addEventListener('click', function() {
                        if (!navigator.geolocation) return alert('Geolocation tidak didukung pada browser ini');
                        useLocationBtn.disabled = true;
                        useLocationBtn.textContent = 'Mencari...';
                        navigator.geolocation.getCurrentPosition(function(pos) {
                            const lat = pos.coords.latitude;
                            const lng = pos.coords.longitude;
                            const ll = L.latLng(lat, lng);
                            map.setView(ll, 15);
                            if (pickMarker) pickMarker.setLatLng(ll);
                            else pickMarker = L.marker(ll).addTo(map);
                            document.getElementById('location_lat').value = lat.toFixed(6);
                            document.getElementById('location_lng').value = lng.toFixed(6);
                            useLocationBtn.disabled = false;
                            useLocationBtn.textContent = 'Gunakan Lokasi Saya';
                        }, function(err) {
                            alert('Gagal: ' + err.message);
                            useLocationBtn.disabled = false;
                            useLocationBtn.textContent = 'Gunakan Lokasi Saya';
                        }, {
                            enableHighAccuracy: true
                        });
                    });
                }

                // ensure proper rendering
                setTimeout(function() {
                    map.invalidateSize();
                }, 250);
                // client-side date check with optional auto-swap
                const form = document.getElementById('rencana-kegiatan-form');
                form.addEventListener('submit', function(e) {
                    const startEl = document.querySelector('input[name="tanggal_mulai"]');
                    const endEl = document.querySelector('input[name="tanggal_selesai"]');
                    const s = startEl ? startEl.value : '';
                    const t = endEl ? endEl.value : '';
                    if (s && t) {
                        const sd = new Date(s);
                        const ed = new Date(t);
                        if (ed < sd) {
                            e.preventDefault();
                            if (confirm('Tanggal selesai lebih awal dari tanggal mulai. Tukar otomatis?')) {
                                // swap values and submit
                                startEl.value = t;
                                endEl.value = s;
                                form.submit();
                            } else {
                                alert('Silakan koreksi tanggal sebelum mengirim.');
                            }
                        }
                    }
                });
            });
        </script>
    @endpush
@endsection
