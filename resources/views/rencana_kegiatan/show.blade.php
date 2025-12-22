@extends('layouts.mantis')

@section('content')
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h3 class="mb-0">Detail Rencana Kegiatan — {{ $report->nama_kegiatan }}</h3>
            <div>
                <a href="{{ route('rencana_kegiatan.index') }}">Kembali</a>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <table class="table">
                    <tr>
                        <th>Nama Kegiatan</th>
                        <td>{{ $report->nama_kegiatan }}</td>
                    </tr>
                    <tr>
                        <th>Jenis Kegiatan</th>
                        <td>{{ $report->jenis_kegiatan }}</td>
                    </tr>
                    <tr>
                        <th>Desa</th>
                        <td>{{ $report->desa }}</td>
                    </tr>
                    <tr>
                        <th>Tanggal</th>
                        <td>
                            @if ($report->tanggal_mulai)
                                {{ \Carbon\Carbon::parse($report->tanggal_mulai)->format('d/m/Y') }}
                                @if ($report->tanggal_selesai)
                                    - {{ \Carbon\Carbon::parse($report->tanggal_selesai)->format('d/m/Y') }}
                                @endif
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>Penanggung Jawab</th>
                        <td>{{ $report->penanggung_jawab }}</td>
                    </tr>
                    <tr>
                        <th>Kelompok</th>
                        <td>{{ $report->kelompok }}</td>
                    </tr>
                    <tr>
                        <th>Estimasi Peserta</th>
                        <td>{{ $report->estimasi_peserta }}</td>
                    </tr>
                    <tr>
                        <th>Estimasi Anggaran</th>
                        <td>{{ $report->estimasi_anggaran }}</td>
                    </tr>
                    <tr>
                        <th>Status</th>
                        <td>{{ ucfirst($report->status) }}</td>
                    </tr>
                </table>

                @if ($report->foto)
                    <div class="mb-3">
                        <h5>Foto</h5>
                        <img src="{{ asset('storage/' . $report->foto) }}" alt="foto" class="img-fluid">
                    </div>
                @endif

                @if ($report->dokumen)
                    <div class="mb-3">
                        <h5>Dokumen</h5>
                        <a href="{{ asset('storage/' . $report->dokumen) }}" target="_blank">Download dokumen</a>
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
                const lat = parseFloat('{{ $report->lat ?? -6.2 }}');
                const lng = parseFloat('{{ $report->lng ?? 106.816666 }}');
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
