@extends('layouts.mantis')

@section('content')
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h3 class="mb-0">Detail Rencana Kegiatan — {{ $rencana_kegiatan->nama_kegiatan }}</h3>
            <div>
                <a href="{{ route('rencana_kegiatan.index') }}">Kembali</a>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <table class="table">
                    <tr>
                        <th>Nama Kegiatan</th>
                        <td>{{ $rencana_kegiatan->nama_kegiatan }}</td>
                    </tr>
                    <tr>
                        <th>Jenis Kegiatan</th>
                        <td>{{ $rencana_kegiatan->jenis_kegiatan }}</td>
                    </tr>
                    <tr>
                        <th>Desa</th>
                        <td>{{ $rencana_kegiatan->desa }}</td>
                    </tr>
                    <tr>
                        <th>Tanggal</th>
                        <td>
                            @if ($rencana_kegiatan->tanggal_mulai)
                                {{ \Carbon\Carbon::parse($rencana_kegiatan->tanggal_mulai)->format('d/m/Y') }}
                                @if ($rencana_kegiatan->tanggal_selesai)
                                    - {{ \Carbon\Carbon::parse($rencana_kegiatan->tanggal_selesai)->format('d/m/Y') }}
                                @endif
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>Penanggung Jawab</th>
                        <td>{{ $rencana_kegiatan->penanggung_jawab }}</td>
                    </tr>
                    <tr>
                        <th>Kelompok</th>
                        <td>{{ $rencana_kegiatan->kelompok }}</td>
                    </tr>
                    <tr>
                        <th>Estimasi Peserta</th>
                        <td>{{ $rencana_kegiatan->estimasi_peserta }}</td>
                    </tr>
                    <tr>
                        <th>Estimasi Anggaran</th>
                        <td>{{ $rencana_kegiatan->estimasi_anggaran }}</td>
                    </tr>
                    <tr>
                        <th>Status</th>
                        <td>{{ ucfirst($rencana_kegiatan->status) }}</td>
                    </tr>
                </table>

                @if ($rencana_kegiatan->foto)
                    <div class="mb-3">
                        <h5>Foto</h5>
                        <img src="{{ asset('storage/' . $rencana_kegiatan->foto) }}" alt="foto" class="img-fluid">
                    </div>
                @endif

                @if ($rencana_kegiatan->dokumen)
                    <div class="mb-3">
                        <h5>Dokumen</h5>
                        <a href="{{ asset('storage/' . $rencana_kegiatan->dokumen) }}" target="_blank">Download dokumen</a>
                    </div>
                @endif
            </div>
            <div class="col-md-6">
                <div id="map-show" style="width:100%; height:70vh; border:1px solid #ddd; border-radius:4px;"></div>
            </div>
        </div>
    </div>

    @push('styles')
        <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    @endpush

    @push('scripts')
        <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const lat = parseFloat('{{ $rencana_kegiatan->lat ?? -6.2 }}');
                const lng = parseFloat('{{ $rencana_kegiatan->lng ?? 106.816666 }}');
                const map = L.map('map-show').setView([lat, lng], 13);
                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    maxZoom: 19,
                    attribution: '&copy; OpenStreetMap contributors'
                }).addTo(map);

                if (!isNaN(lat) && !isNaN(lng)) {
                    L.marker([lat, lng]).addTo(map);
                }

                setTimeout(function() {
                    map.invalidateSize();
                }, 200);
            });
        </script>
    @endpush

@endsection
