@extends('layouts.adminlte')

@section('content_title', 'Detail Rencana Kegiatan')

@section('content')
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-3">

            <h5 class="mb-0">
                Rencana Kegiatan
            </h5>

            <div class="text-end">
                <a href="{{ route('rencana_kegiatan.index') }}" class="btn btn-secondary btn-sm"
                    style="height: 35px; display: flex; align-items: center; justify-content: center;">
                    <i class="fas fa-arrow-left mr-1"></i> Kembali
                </a>
            </div>
            {{-- Tombol Laporan Kegiatan --}}
            {{-- @if ($rencana_kegiatan->status === \App\Models\RencanaKegiatan::STATUS_SELESAI)
                    @if ($rencana_kegiatan->hasLaporan())
                        <a href="{{ route('laporan_kegiatan.show', $rencana_kegiatan->laporanKegiatan) }}" class="btn btn-info">
                            <i class="fas fa-file-alt mx-1"></i>Lihat Laporan
                        </a>
                    @else
                        @can('create', \App\Models\LaporanKegiatan::class)
                            <a href="{{ route('laporan_kegiatan.create', ['rencana_kegiatan_id' => $rencana_kegiatan->uuid]) }}" class="btn btn-success">
                                <i class="fas fa-plus mx-1"></i>Buat Laporan
                            </a>
                        @else
                            <button class="btn btn-success" disabled title="Hubungi admin untuk membuat laporan">
                                <i class="fas fa-plus mx-1"></i>Buat Laporan
                            </button>
                        @endcan
                    @endif
                @else
                    <button class="btn btn-secondary" disabled title="Laporan hanya bisa dibuat untuk rencana kegiatan yang selesai">
                        <i class="fas fa-file-alt mx-1"></i>Laporan Tidak Tersedia
                    </button>
                @endif --}}
        </div>

        <div class="row">
            <div class="col-md-6 mb-3">
                <div id="map-show" style="width:100%; height:70vh; border:1px solid #ddd; border-radius:4px;"></div>
            </div>
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
                        <th>Rincian Kebutuhan</th>
                        <td>{!! $rencana_kegiatan->rincian_kebutuhan !!}</td>
                    </tr>
                    <tr>
                        <th>Status</th>
                        <td>
                            <span
                                class="badge bg-{{ $rencana_kegiatan->status == \App\Models\RencanaKegiatan::STATUS_DIAJUKAN
                                    ? 'secondary'
                                    : ($rencana_kegiatan->status == \App\Models\RencanaKegiatan::STATUS_DISETUJUI
                                        ? 'warning text-dark'
                                        : ($rencana_kegiatan->status == \App\Models\RencanaKegiatan::STATUS_SELESAI
                                            ? 'success'
                                            : ($rencana_kegiatan->status == \App\Models\RencanaKegiatan::STATUS_DITOLAK
                                                ? 'danger'
                                                : 'secondary'))) }}">
                                {{ ucfirst($rencana_kegiatan->status) }}
                            </span>
                        </td>
                    </tr>
                    @if (!empty($rencana_kegiatan->keterangan_status))
                        <tr>
                            <th>Keterangan Status</th>
                            <td>{{ $rencana_kegiatan->keterangan_status }}</td>
                        </tr>
                    @endif
                </table>
            </div>
            <div>
                @if (!empty($rencana_kegiatan->foto) && is_array($rencana_kegiatan->foto))
                    <div class="mb-3">
                        <h5>Foto</h5>

                        <div class="d-flex flex-wrap gap-2">
                            @foreach ($rencana_kegiatan->foto as $foto)
                                @if ($foto)
                                    <img src="{{ asset('storage/' . $foto) }}" alt="foto kegiatan" class="img-thumbnail"
                                        style="width:150px;height:150px;object-fit:cover;">
                                @endif
                            @endforeach
                        </div>
                    </div>
                @endif

                @if (!empty($rencana_kegiatan->dokumen))
                    @php
                        // Pastikan selalu array
                        $dokumens = $rencana_kegiatan->dokumen;

                        if (is_string($dokumens)) {
                            $dokumens = json_decode($dokumens, true);
                        }

                        $dokumens = is_array($dokumens) ? $dokumens : [];
                    @endphp

                    @if (count($dokumens))
                        <div class="mb-3">
                            <h5>Dokumen</h5>

                            <ul class="list-group">
                                @foreach ($dokumens as $file)
                                    @if ($file)
                                        <li class="list-group-item d-flex align-items-center">
                                            <i class="fas fa-file-alt text-primary me-2"></i>

                                            <a href="{{ asset('storage/' . $file) }}" target="_blank"
                                                class="text-decoration-none">
                                                {{ basename($file) }}
                                            </a>
                                        </li>
                                    @endif
                                @endforeach
                            </ul>
                        </div>
                    @endif
                @endif
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
