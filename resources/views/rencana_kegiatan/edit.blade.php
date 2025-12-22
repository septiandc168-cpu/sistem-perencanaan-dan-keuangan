@extends('layouts.mantis')

@section('content')
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h3 class="mb-0">Edit Rencana Kegiatan — {{ $rencana_kegiatan->nama_kegiatan }}</h3>
            <div>
                <a href="{{ route('rencana_kegiatan.index') }}">Kembali</a>
            </div>
        </div>

        <form id="rencana-kegiatan-form" action="{{ route('rencana_kegiatan.update', $rencana_kegiatan->id) }}" method="POST" enctype="multipart/form-data">
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
                <div class="col-md-5">


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
                        <label class="form-label">Koordinat Lokasi</label>
                        <div class="input-group">
                            <input type="text" id="location_lat" name="lat" class="form-control"
                                placeholder="Latitude" readonly required                                 value="{{ old('lat', $rencana_kegiatan->lat) }}">
                            <input type="text" id="location_lng" name="lng" class="form-control"
                                placeholder="Longitude" readonly required value="{{ old('lng', $rencana_kegiatan->lng) }}">
                            <button type="button" id="use-location" class="btn btn-outline-secondary">Gunakan Lokasi
                                Saya</button>
                        </div>
                        <div class="form-text">Klik peta untuk memilih lokasi atau gunakan tombol geolocation.</div>
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

                    <div class="mb-3">
                        <label class="form-label">Estimasi Jumlah Peserta</label>
                        <input type="number" name="estimasi_peserta" class="form-control" min="0"
                            value="{{ old('estimasi_peserta', $rencana_kegiatan->estimasi_peserta) }}">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Estimasi Anggaran (opsional)</label>
                        <input type="number" step="0.01" name="estimasi_anggaran" class="form-control"
                            value="{{ old('estimasi_anggaran', $rencana_kegiatan->estimasi_anggaran) }}">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Status</label>
                        <select name="status" class="form-select" required>
                            <option value="">-- Pilih Status --</option>
                            @php
                                $currentStatus = old('status', $rencana_kegiatan->status ?? '');
                            @endphp

                            <option value="direncanakan" {{ $currentStatus == 'direncanakan' ? 'selected' : '' }}>
                                Direncanakan
                            </option>
                            <option value="sedang berlangsung"
                                {{ $currentStatus == 'sedang berlangsung' ? 'selected' : '' }}>
                                Sedang Berlangsung
                            </option>
                            <option value="selesai" {{ $currentStatus == 'selesai' ? 'selected' : '' }}>
                                Selesai
                            </option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Unggah Foto (opsional)</label>
                        <input type="file" name="foto" class="form-control mb-1">
                        @if ($rencana_kegiatan->foto)
                            <div class="mt-1"><a href="{{ asset('storage/' . $rencana_kegiatan->foto) }}" target="_blank">Lihat
                                    foto saat ini</a></div>
                        @endif
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Unggah Dokumen (opsional)</label>
                        <input type="file" name="dokumen" class="form-control mb-1">
                        @if ($rencana_kegiatan->dokumen)
                            <div class="mt-1"><a href="{{ asset('storage/' . $rencana_kegiatan->dokumen) }}"
                                    target="_blank">Lihat
                                    dokumen saat ini</a></div>
                        @endif
                    </div>

                    <div class="mb-3">
                        <button type="submit" class="btn btn-primary">Perbarui Rencana Kegiatan</button>
                    </div>
                </div>

                <div class="col-md-7">
                    <div id="map-create" style="width:100%; height:70vh; border:1px solid #ddd; border-radius:4px;"></div>
                </div>
            </div>
        </form>
    </div>

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
