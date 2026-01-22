@extends('layouts.adminlte')

@section('content_title', 'Detail Laporan Kegiatan')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3 class="mb-0">Detail Laporan Kegiatan</h3>
        <div>
            <a href="{{ route('laporan_kegiatan.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left mx-1"></i>Kembali
            </a>
            @can('print', $laporanKegiatan)
                <a href="{{ route('laporan_kegiatan.print', $laporanKegiatan) }}" class="btn btn-info" target="_blank">
                    <i class="fas fa-print mx-1"></i>Cetak
                </a>
            @endcan
            @can('update', $laporanKegiatan)
                <a href="{{ route('laporan_kegiatan.edit', $laporanKegiatan) }}" class="btn btn-warning">
                    <i class="fas fa-edit mx-1"></i>Edit
                </a>
            @endcan
        </div>
    </div>

    <!-- Informasi Rencana Kegiatan -->
    <div class="card card-primary card-outline mb-4">
        <div class="card-header">
            <h3 class="card-title">
                <i class="fas fa-info-circle mr-1"></i>
                Informasi Rencana Kegiatan
            </h3>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <table class="table table-sm">
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
                    <table class="table table-sm">
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
                    <table class="table table-sm">
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
                    <table class="table table-sm">
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
                            <td><strong>Tanggal Dibuat</strong></td>
                            <td>{{ $laporanKegiatan->created_at->format('d/m/Y H:i') }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Detail Laporan Kegiatan -->
    <div class="card card-success card-outline mb-4">
        <div class="card-header">
            <h3 class="card-title">
                <i class="fas fa-file-alt mr-1"></i>
                Detail Laporan
            </h3>
        </div>
        <div class="card-body">
            <div class="mb-4">
                <h5 class="text-primary"><i class="fas fa-tasks mr-1"></i>Pelaksanaan Kegiatan</h5>
                <div class="p-3 bg-light rounded">
                    {!! nl2br(e($laporanKegiatan->pelaksanaan_kegiatan)) !!}
                </div>
            </div>

            <div class="mb-4">
                <h5 class="text-success"><i class="fas fa-check-circle mr-1"></i>Hasil Kegiatan</h5>
                <div class="p-3 bg-light rounded">
                    {!! nl2br(e($laporanKegiatan->hasil_kegiatan)) !!}
                </div>
            </div>

            @if($laporanKegiatan->kendala)
                <div class="mb-4">
                    <h5 class="text-warning"><i class="fas fa-exclamation-triangle mr-1"></i>Kendala</h5>
                    <div class="p-3 bg-light rounded">
                        {!! nl2br(e($laporanKegiatan->kendala)) !!}
                    </div>
                </div>
            @endif

            @if($laporanKegiatan->evaluasi)
                <div class="mb-4">
                    <h5 class="text-info"><i class="fas fa-chart-line mr-1"></i>Evaluasi</h5>
                    <div class="p-3 bg-light rounded">
                        {!! nl2br(e($laporanKegiatan->evaluasi)) !!}
                    </div>
                </div>
            @endif

            @if(!empty($laporanKegiatan->dokumentasi))
                <div class="mb-4">
                    <h5 class="text-secondary"><i class="fas fa-images mr-1"></i>Dokumentasi</h5>
                    <div class="row">
                        @foreach($laporanKegiatan->dokumentasi as $index => $dokumentasi)
                            <div class="col-md-3 mb-3">
                                <div class="card">
                                    <img src="{{ asset('storage/' . $dokumentasi) }}" 
                                         class="card-img-top" 
                                         style="height: 200px; object-fit: cover; width: 100%;"
                                         alt="Dokumentasi {{ $index + 1 }}"
                                         data-bs-toggle="modal" 
                                         data-bs-target="#imageModal{{ $index }}">
                                    <div class="card-body p-2 text-center">
                                        <small class="text-muted">Dokumentasi {{ $index + 1 }}</small>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </div>

    <!-- Modal untuk preview gambar -->
    @if(!empty($laporanKegiatan->dokumentasi))
        @foreach($laporanKegiatan->dokumentasi as $index => $dokumentasi)
            <div class="modal fade" id="imageModal{{ $index }}" tabindex="-1">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Dokumentasi {{ $index + 1 }}</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body text-center">
                            <img src="{{ asset('storage/' . $dokumentasi) }}" 
                                 class="img-fluid" 
                                 alt="Dokumentasi {{ $index + 1 }}">
                        </div>
                        <div class="modal-footer">
                            <a href="{{ asset('storage/' . $dokumentasi) }}" 
                               download="dokumentasi-{{ $index + 1 }}.jpg" 
                               class="btn btn-primary">
                                <i class="fas fa-download mr-1"></i>Download
                            </a>
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    @endif
</div>

<style>
.card-img-top {
    cursor: pointer;
    transition: transform 0.2s;
}
.card-img-top:hover {
    transform: scale(1.05);
}
.modal-body img {
    max-height: 70vh;
    object-fit: contain;
}
</style>
@endsection
