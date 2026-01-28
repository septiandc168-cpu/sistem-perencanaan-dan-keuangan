@extends('layouts.adminlte')

@section('content_title', 'Buat Rencana Kegiatan')

@section('content')
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h5 class="mb-0">Form Rencana Kegiatan</h5>
        </div>

        <form id="rencana-kegiatan-form" action="{{ route('rencana_kegiatan.store') }}" method="POST"
            enctype="multipart/form-data">
            @csrf
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label">Koordinat Lokasi</label>
                        <div class="input-group">
                            <input type="text" id="location_lat" name="lat" class="form-control"
                                placeholder="Latitude" readonly required>
                            <input type="text" id="location_lng" name="lng" class="form-control"
                                placeholder="Longitude" readonly required>
                            <button type="button" id="use-location" class="btn btn-outline-secondary">Gunakan Lokasi
                                Saya</button>
                        </div>
                        <small class="form-text text-muted">Pilih lokasi dengan mengklik pada peta atau gunakan lokasi
                            Anda
                            saat ini.</small>
                    </div>

                    <div class="mb-3" id="map-create"
                        style="width:100%; height:70vh; border:1px solid #ddd; border-radius:4px;">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Nama Kegiatan</label>
                        <input type="text" name="nama_kegiatan" class="form-control" placeholder="Nama kegiatan"
                            required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Jenis Kegiatan</label>
                        <select name="jenis_kegiatan" class="form-select" required>
                            <option value="">Pilih jenis kegiatan</option>
                            <option value="konservasi">Konservasi</option>
                            <option value="usaha masyarakat">Usaha Masyarakat</option>
                            <option value="edukasi">Edukasi</option>
                            <option value="lainnya">Lainnya</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Deskripsi</label>
                        <textarea name="deskripsi" class="form-control" rows="3"></textarea>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Tujuan</label>
                        <textarea name="tujuan" class="form-control" rows="2"></textarea>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Desa / Wilayah</label>
                        <input type="text" name="desa" class="form-control" placeholder="Nama desa atau wilayah">
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Tanggal Mulai</label>
                            <input type="date" name="tanggal_mulai" class="form-control">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Tanggal Selesai</label>
                            <input type="date" name="tanggal_selesai" class="form-control">
                        </div>
                    </div>
                </div>
                <div class="col-md-6">

                    <div class="mb-3">
                        <label class="form-label">Penanggung Jawab</label>
                        <input type="text" name="penanggung_jawab" class="form-control"
                            placeholder="Nama Penanggung Jawab">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Kelompok / Komunitas Pelaksana</label>
                        <input type="text" name="kelompok" class="form-control" placeholder="Nama kelompok">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Estimasi Jumlah Peserta</label>
                        <input type="number" name="estimasi_peserta" class="form-control" min="0">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Rincian Kebutuhan</label>
                        <textarea type="text" name="rincian_kebutuhan" class="form-control" id="summernote"></textarea>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Unggah Foto</label>

                        <input type="file" id="fotoInput" name="foto[]" class="form-control" accept="image/*"
                            multiple>

                        {{-- <small class="text-muted">
                            Bisa pilih satu foto atau beberapa sekaligus
                        </small> --}}
                    </div>

                    {{-- PREVIEW --}}
                    <div id="preview-foto" class="d-flex flex-column"></div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Dokumen Kegiatan</label>

                        <input type="file" id="dokumenInput" name="dokumen[]" class="form-control" multiple
                            accept=".pdf,.doc,.docx">

                        {{-- <small class="text-muted">
                            Bisa pilih satu atau beberapa dokumen (PDF / Word)
                        </small> --}}
                    </div>

                    {{-- PREVIEW DOKUMEN --}}
                    <div id="preview-dokumen" class="d-flex flex-column gap-2 mb-3"></div>
                </div>
                <div class="col-md-12">
                    <div class="d-flex justify-content-between mb-3">
                        <a href="{{ route('rencana_kegiatan.index') }}" class="btn btn-secondary btn-sm"
                            style="height: 35px; display: flex; align-items: center; justify-content: center;">
                            <i class="fas fa-arrow-left mr-1"></i> Kembali
                        </a>
                        <button type="submit" class="btn btn-primary btn-sm"
                            style="height: 35px; display: flex; align-items: center; justify-content: center;">
                            <i class="fas fa-save mr-1"></i> Simpan</button>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <script>
        const fotoInput = document.getElementById('fotoInput');
        const preview = document.getElementById('preview-foto');

        let filesBuffer = [];

        fotoInput.addEventListener('change', function() {
            for (let file of this.files) {
                if (!file.type.startsWith('image/')) continue;

                // hindari duplikasi
                if (!filesBuffer.some(f => f.name === file.name && f.size === file.size)) {
                    filesBuffer.push(file);
                }
            }

            renderPreview();
            syncInputFiles();
        });

        function renderPreview() {
            preview.innerHTML = '';

            filesBuffer.forEach((file, index) => {
                const reader = new FileReader();

                reader.onload = e => {
                    const div = document.createElement('div');
                    div.className = 'position-relative d-flex align-items-center gap-2 mb-2';

                    div.innerHTML = `
                    <img src="${e.target.result}"
                         style="width:100px;height:100px;object-fit:cover"
                         class="rounded border">

                    <button type="button"
                            class="btn btn-sm btn-danger ms-auto"
                            onclick="removeFoto(${index})">
                        <i class="fas fa-times"></i>
                    </button>
                `;

                    preview.appendChild(div);
                };

                reader.readAsDataURL(file);
            });
        }

        function removeFoto(index) {
            filesBuffer.splice(index, 1);
            renderPreview();
            syncInputFiles();
        }

        function syncInputFiles() {
            const dataTransfer = new DataTransfer();
            filesBuffer.forEach(file => dataTransfer.items.add(file));
            fotoInput.files = dataTransfer.files;
        }
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

            // ❌ JANGAN reset input
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
                <small class="text-muted">${(file.size/1024).toFixed(1)} KB</small>
            </div>
            <button type="button"
                    class="btn btn-sm btn-danger"
                    onclick="removeDokumen(${index})"><i class="fas fa-times"></i></button>
        `;

                previewDokumen.appendChild(div);
            });
        }

        function removeDokumen(index) {
            dokumenBuffer.splice(index, 1);
            syncDokumenInput();
            renderDokumenPreview();
        }

        function syncDokumenInput() {
            const dt = new DataTransfer();
            dokumenBuffer.forEach(file => dt.items.add(file));
            dokumenInput.files = dt.files;
        }
    </script>

    @push('js')
        <script src="{{ asset('adminlte') }}/plugins/summernote/summernote-bs4.min.js"></script>

        <script>
            $(function() {
                // Summernote
                $('#summernote').summernote()

                // CodeMirror
                CodeMirror.fromTextArea(document.getElementById("codeMirrorDemo"), {
                    mode: "htmlmixed",
                    theme: "monokai"
                });
            })
        </script>
    @endpush

    @push('styles')
        <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
        <style>
            #map-create {
                background: #f7fafc;
            }
        </style>
    @endpush

    @push('css')
        <link rel="stylesheet" href="{{ asset('adminlte') }}/plugins/summernote/summernote-bs4.min.css">
    @endpush

    @push('scripts')
        <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const map = L.map('map-create').setView([-6.200000, 106.816666], 5);

                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    maxZoom: 19,
                    attribution: '&copy; OpenStreetMap contributors'
                }).addTo(map);

                // marker user pick
                let pickMarker = null;

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
            });
            // client-side date check with optional auto-swap for create form
            document.addEventListener('DOMContentLoaded', function() {
                const form = document.getElementById('rencana-kegiatan-form');
                if (!form) return;

                form.addEventListener('submit', function(e) {
                    // Check if coordinates are filled
                    const latEl = document.querySelector('input[name="lat"]');
                    const lngEl = document.querySelector('input[name="lng"]');
                    const lat = latEl ? latEl.value : '';
                    const lng = lngEl ? lngEl.value : '';

                    if (!lat || !lng) {
                        e.preventDefault();
                        alert(
                            'Silakan pilih lokasi pada peta terlebih dahulu dengan mengklik pada peta atau menggunakan tombol "Gunakan Lokasi Saya".'
                        );
                        return false;
                    }

                    // Date validation
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
