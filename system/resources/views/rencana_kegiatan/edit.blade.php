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
                </div>
                <div class="col-md-6">

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
                        <input type="file" id="fotoInput" name="foto[]" class="form-control" accept="image/*"
                            multiple>

                        {{-- <small class="text-muted">
                            Bisa pilih satu atau beberapa foto sekaligus
                        </small> --}}
                    </div>

                    {{-- HIDDEN FOTO LAMA (YANG DIPERTAHANKAN) --}}
                    <div id="foto-lama-hidden"></div>

                    {{-- PREVIEW FOTO --}}
                    <div id="preview-foto" class="d-flex flex-column"></div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Dokumen Kegiatan</label>

                        <input type="file" id="dokumenInput" name="dokumen[]" class="form-control" multiple
                            accept=".pdf,.doc,.docx">

                        {{-- <small class="text-muted">
                            Bisa menambahkan dokumen baru (PDF / Word). Dokumen lama tetap tersimpan jika tidak dihapus.
                        </small> --}}
                    </div>

                    {{-- DOKUMEN LAMA --}}
                    @if (!empty($rencana_kegiatan->dokumen))
                        @foreach ($rencana_kegiatan->dokumen as $index => $file)
                            <div class="d-flex align-items-center border rounded p-2 mb-2">
                                <i class="fas fa-file-alt text-secondary me-2"></i>

                                <div class="w-100">
                                    <a href="{{ asset('storage/' . $file) }}" target="_blank">
                                        {{ basename($file) }}
                                    </a>
                                </div>

                                {{-- kirim ke controller --}}
                                <input type="hidden" name="dokumen_lama[]" value="{{ $file }}">

                                <button type="button" class="btn btn-sm btn-danger" onclick="hapusDokumenLama(this)">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                        @endforeach
                    @endif

                    {{-- PREVIEW DOKUMEN BARU --}}
                    <div id="preview-dokumen" class="d-flex flex-column gap-2 mt-2"></div>
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
        </form>
    </div>

    <script>
        const fotoInput = document.getElementById('fotoInput');
        const preview = document.getElementById('preview-foto');
        const fotoLamaHidden = document.getElementById('foto-lama-hidden');

        /* =======================
           FOTO LAMA (DATABASE)
        ======================= */
        let oldPhotos = @json($rencana_kegiatan->foto ?? []);
        if (!Array.isArray(oldPhotos)) {
            oldPhotos = oldPhotos ? [oldPhotos] : [];
        }

        /* =======================
           FOTO BARU
        ======================= */
        document.addEventListener('DOMContentLoaded', function() {
                    // Handle status change for supervisor
                    const statusSelect = document.querySelector('select[name="status"]');
                    const keteranganContainer = document.querySelector('.keterangan-status-container');

                    if (statusSelect && keteranganContainer) {
                        statusSelect.addEventListener('change', function() {
                            const status = this.value;
                            const showKeterangan = ['disetujui', 'ditolak'].includes(status);

                            if (showKeterangan) {
                                keteranganContainer.style.display = 'block';
                                const keteranganLabel = keteranganContainer.querySelector('label');
                                const keteranganTextarea = keteranganContainer.querySelector('textarea');

                                if (keteranganLabel && keteranganTextarea) {
                                    keteranganLabel.textContent = status === 'disetujui' ? 'Catatan Persetujuan' :
                                        'Alasan Penolakan';
                                    keteranganTextarea.placeholder = status === 'disetujui' ?
                                        'Tambahkan catatan persetujuan...' : 'Jelaskan alasan penolakan...';
                                    keteranganTextarea.required = true;
                                }
                            } else {
                                keteranganContainer.style.display = 'none';
                            }
                        });

                        // Trigger change event on page load
                        const event = new Event('change');
                        statusSelect.dispatchEvent(event);
                    }

                    // Original JavaScript for photo handling
                    let filesBuffer = [];

                    renderAll();

                    /* =======================
                       INPUT FOTO BARU
                    ======================= */
                    fotoInput.addEventListener('change', function() {
                        const selectedFiles = Array.from(this.files);

                        selectedFiles.forEach(file => {
                            if (!file.type.startsWith('image/')) return;

                            const exists = filesBuffer.some(
                                f => f.name === file.name && f.size === file.size
                            );

                            if (!exists) {
                                filesBuffer.push(file);
                            }
                        });

                        syncInputFiles();
                        renderAll();

                        // reset AFTER sync
                        fotoInput.value = '';
                    });

                    /* =======================
                       RENDER SEMUA FOTO
                    ======================= */
                    function renderAll() {
                        preview.innerHTML = '';

                        // FOTO LAMA
                        oldPhotos.forEach((path, index) => {
                            preview.appendChild(createOldPreview(path, index));
                        });

                        // FOTO BARU
                        filesBuffer.forEach((file, index) => {
                            const reader = new FileReader();
                            reader.onload = e => {
                                preview.appendChild(createNewPreview(e.target.result, index));
                            };
                            reader.readAsDataURL(file);
                        });

                        syncOldHidden();
                    }

                    /* =======================
                       PREVIEW COMPONENT
                    ======================= */
                    function createOldPreview(path, index) {
                        const div = document.createElement('div');
                        div.className = 'position-relative d-flex align-items-center gap-2 mb-2';

                        div.innerHTML = `
            <img src="/storage/${path}"
                 class="rounded border"
                 style="width:100px;height:100px;object-fit:cover">

            <button type="button"
                    class="btn btn-sm btn-danger ms-auto"
                    onclick="removeOld(${index})"><i class="fas fa-times"></i></button>
        `;
                        return div;
                    }

                    function createNewPreview(src, index) {
                        const div = document.createElement('div');
                        div.className = 'position-relative d-flex align-items-center gap-2 mb-2';

                        div.innerHTML = `
            <img src="${src}"
                 class="rounded border"
                 style="width:100px;height:100px;object-fit:cover">

            <button type="button"
                    class="btn btn-sm btn-danger ms-auto"
                    onclick="removeNew(${index})"><i class="fas fa-times"></i></button>
        `;
                        return div;
                    }

                    /* =======================
                       REMOVE FOTO
                    ======================= */
                    function removeOld(index) {
                        oldPhotos.splice(index, 1);
                        renderAll();
                    }

                    function removeNew(index) {
                        filesBuffer.splice(index, 1);
                        syncInputFiles();
                        renderAll();
                    }

                    /* =======================
                       SYNC FILE INPUT
                    ======================= */
                    function syncInputFiles() {
                        const dt = new DataTransfer();
                        filesBuffer.forEach(file => dt.items.add(file));
                        fotoInput.files = dt.files;
                    }

                    /* =======================
                       FOTO LAMA → HIDDEN INPUT
                    ======================= */
                    function syncOldHidden() {
                        fotoLamaHidden.innerHTML = '';
                        oldPhotos.forEach(path => {
                            const input = document.createElement('input');
                            input.type = 'hidden';
                            input.name = 'foto_lama[]';
                            input.value = path;
                            fotoLamaHidden.appendChild(input);
                        });
                    }
    </script>

    <script>
        const form = fotoInput.closest('form');

        form.addEventListener('submit', function() {
            const dt = new DataTransfer();

            filesBuffer.forEach(file => {
                dt.items.add(file);
            });

            fotoInput.files = dt.files;
        });
    </script>

    <script>
        const dokumenInput = document.getElementById('dokumenInput');
        const previewDokumen = document.getElementById('preview-dokumen');

        let dokumenBuffer = [];

        dokumenInput.addEventListener('change', function() {
            Array.from(this.files).forEach(file => {
                if (!file.type.match(/pdf|word|officedocument/)) return;

                const exists = dokumenBuffer.some(
                    f => f.name === file.name && f.size === file.size
                );

                if (!exists) {
                    dokumenBuffer.push(file);
                }
            });

            syncDokumenInput();
            renderDokumenPreview();
        });

        function renderDokumenPreview() {
            previewDokumen.innerHTML = '';

            dokumenBuffer.forEach((file, index) => {
                const div = document.createElement('div');
                div.className = 'd-flex align-items-center border rounded p-2';

                div.innerHTML = `
            <i class="fas fa-file-alt text-primary me-2"></i>
            <div class="flex-grow-1">
                <div class="fw-semibold">${file.name}</div>
                <small class="text-muted">${(file.size / 1024).toFixed(1)} KB</small>
            </div>
            <button type="button"
                    class="btn btn-sm btn-danger"
                    onclick="removeDokumenBaru(${index})"><i class="fas fa-times"></i></button>
        `;

                previewDokumen.appendChild(div);
            });
        }

        function removeDokumenBaru(index) {
            dokumenBuffer.splice(index, 1);
            syncDokumenInput();
            renderDokumenPreview();
        }

        function syncDokumenInput() {
            const dt = new DataTransfer();
            dokumenBuffer.forEach(file => dt.items.add(file));
            dokumenInput.files = dt.files;
        }

        // hapus dokumen lama dari form (bukan langsung dari storage)
        function hapusDokumenLama(button) {
            button.closest('.d-flex').remove();
        }
    </script>

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
