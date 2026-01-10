@extends('layouts.print')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12 text-center">
            <h2 class="text-primary">LAPORAN KEGIATAN</h2>
            <h4>{{ $laporanKegiatan->rencanaKegiatan->nama_kegiatan }}</h4>
            <p class="text-muted">Tanggal Cetak: {{ now()->format('d F Y H:i') }}</p>
        </div>
    </div>

    <!-- Informasi Rencana Kegiatan -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">
                        <i class="fas fa-info-circle mr-1"></i>
                        Informasi Rencana Kegiatan
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-sm table-borderless">
                                <tr>
                                    <td width="150"><strong>Nama Kegiatan</strong></td>
                                    <td>{{ $laporanKegiatan->rencanaKegiatan->nama_kegiatan }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Jenis Kegiatan</strong></td>
                                    <td>{{ $laporanKegiatan->rencanaKegiatan->jenis_kegiatan }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Tujujuan</strong></td>
                                    <td>{{ $laporanKegiatan->rencanaKegiatan->tujuan ?: '-' }}</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-sm table-borderless">
                                <tr>
                                    <td width="150"><strong>Penanggung Jawab</strong></td>
                                    <td>{{ $laporanKegiatan->rencanaKegiatan->penanggung_jawab ?: '-' }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Kelompok</strong></td>
                                    <td>{{ $laporanKegiatan->rencanaKegiatan->kelompok ?: '-' }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Lokasi</strong></td>
                                    <td>{{ $laporanKegiatan->rencanaKegiatan->desa ?: '-' }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    
                    <div class="row mt-3">
                        <div class="col-md-6">
                            <table class="table table-sm table-borderless">
                                <tr>
                                    <td width="150"><strong>Tanggal Mulai</strong></td>
                                    <td>{{ $laporanKegiatan->rencanaKegiatan->tanggal_mulai ? \Carbon\Carbon::parse($laporanKegiatan->rencanaKegiatan->tanggal_mulai)->format('d/m/Y') : '-' }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Tanggal Selesai</strong></td>
                                    <td>{{ $laporanKegiatan->rencanaKegiatan->tanggal_selesai ? \Carbon\Carbon::parse($laporanKegiatan->rencanaKegiatan->tanggal_selesai)->format('d/m/Y') : '-' }}</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-sm table-borderless">
                                <tr>
                                    <td width="150"><strong>Status Rencana</strong></td>
                                    <td>
                                        <span class="badge bg-{{ $laporanKegiatan->rencanaKegiatan->status == \App\Models\RencanaKegiatan::STATUS_SELESAI
                                            ? 'success'
                                            : 'secondary' }}">
                                            {{ ucfirst($laporanKegiatan->rencanaKegiatan->status) }}
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Tanggal Laporan</strong></td>
                                    <td>{{ $laporanKegiatan->created_at->format('d/m/Y H:i') }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Detail Laporan Kegiatan -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0">
                        <i class="fas fa-file-alt mr-1"></i>
                        Detail Laporan Kegiatan
                    </h5>
                </div>
                <div class="card-body">
                    <div class="mb-4">
                        <h6 class="text-primary"><i class="fas fa-tasks mr-1"></i>Pelaksanaan Kegiatan</h6>
                        <div class="p-3 bg-light rounded">
                            {!! nl2br(e($laporanKegiatan->pelaksanaan_kegiatan)) !!}
                        </div>
                    </div>

                    <div class="mb-4">
                        <h6 class="text-success"><i class="fas fa-check-circle mr-1"></i>Hasil Kegiatan</h6>
                        <div class="p-3 bg-light rounded">
                            {!! nl2br(e($laporanKegiatan->hasil_kegiatan)) !!}
                        </div>
                    </div>

                    @if($laporanKegiatan->kendala)
                        <div class="mb-4">
                            <h6 class="text-warning"><i class="fas fa-exclamation-triangle mr-1"></i>Kendala</h6>
                            <div class="p-3 bg-light rounded">
                                {!! nl2br(e($laporanKegiatan->kendala)) !!}
                            </div>
                        </div>
                    @endif

                    @if($laporanKegiatan->evaluasi)
                        <div class="mb-4">
                            <h6 class="text-info"><i class="fas fa-chart-line mr-1"></i>Evaluasi</h6>
                            <div class="p-3 bg-light rounded">
                                {!! nl2br(e($laporanKegiatan->evaluasi)) !!}
                            </div>
                        </div>
                    @endif

                    @if(!empty($laporanKegiatan->dokumentasi))
                        <div class="mb-4">
                            <h6 class="text-secondary"><i class="fas fa-images mr-1"></i>Dokumentasi</h6>
                            <div class="row">
                                @foreach($laporanKegiatan->dokumentasi as $index => $dokumentasi)
                                    <div class="col-md-4 mb-3">
                                        <div class="text-center">
                                            <img src="{{ asset('storage/' . $dokumentasi) }}" 
                                                 class="img-fluid border rounded" 
                                                 style="max-height: 200px; object-fit: cover;"
                                                 alt="Dokumentasi {{ $index + 1 }}">
                                            <br>
                                            <small class="text-muted">Dokumentasi {{ $index + 1 }}</small>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <div class="row mt-5">
        <div class="col-12 text-center">
            <hr>
            <p class="text-muted">
                <small>
                    Dicetak oleh: {{ auth()->user()->name }} ({{ auth()->user()->role->role_name }})<br>
                    Sistem Informasi Perencanaan dan Keuangan
                </small>
            </p>
        </div>
    </div>
</div>

<style>
@media print {
    body {
        font-size: 12pt;
        line-height: 1.4;
    }
    
    .card {
        border: 1px solid #ddd;
        page-break-inside: avoid;
        margin-bottom: 20px;
    }
    
    .card-header {
        background-color: #007bff !important;
        color: white !important;
        padding: 10px;
    }
    
    .table-borderless td {
        border: none;
        padding: 4px 8px;
    }
    
    .table-borderless td:first-child {
        font-weight: bold;
        width: 150px;
    }
    
    h6 {
        margin-bottom: 10px;
        font-weight: bold;
    }
    
    .bg-light {
        background-color: #f8f9fa !important;
        padding: 15px;
        border-radius: 5px;
        border: 1px solid #e9ecef;
    }
    
    .img-fluid {
        max-width: 100%;
        height: auto;
    }
    
    .no-print {
        display: none !important;
    }
    
    @page {
        margin: 1cm;
        size: A4;
    }
}
</style>

<script>
window.onload = function() {
    window.print();
};
</script>
@endsection
