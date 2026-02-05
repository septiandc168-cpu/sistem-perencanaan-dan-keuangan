@extends('layouts.adminlte')

@section('content_title', 'Detail Laporan Kegiatan')

@section('content')
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h5 class="mb-0">Laporan Kegiatan</h5>
            <div>
                <a href="{{ route('laporan_kegiatan.index') }}" class="btn btn-secondary btn-sm"
                    style="height: 35px; display: flex; align-items: center; justify-content: center;">
                    <i class="fas fa-arrow-left mr-1"></i>Kembali
                </a>
                {{-- @can('print', $laporanKegiatan)
                    <a href="{{ route('laporan_kegiatan.print', $laporanKegiatan) }}" class="btn btn-info btn-sm"
                        style="height: 35px; display: flex; align-items: center; justify-content: center;" target="_blank">
                        <i class="fas fa-print mr-1"></i>Cetak
                    </a>
                @endcan
                @can('update', $laporanKegiatan)
                    <a href="{{ route('laporan_kegiatan.edit', $laporanKegiatan) }}" class="btn btn-warning btn-sm"
                        style="height: 35px; display: flex; align-items: center; justify-content: center;">
                        <i class="fas fa-edit mr-1"></i>Edit
                    </a>
                @endcan --}}
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
                                <td>{{ $laporanKegiatan->rencanaKegiatan->tanggal_mulai ? \Carbon\Carbon::parse($laporanKegiatan->rencanaKegiatan->tanggal_mulai)->format('d/m/Y') : '-' }}
                                </td>
                            </tr>
                            <tr>
                                <td><strong>Tanggal Selesai</strong></td>
                                <td>{{ $laporanKegiatan->rencanaKegiatan->tanggal_selesai ? \Carbon\Carbon::parse($laporanKegiatan->rencanaKegiatan->tanggal_selesai)->format('d/m/Y') : '-' }}
                                </td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <table class="table table-sm">
                            <tr>
                                <td width="150"><strong>Status Rencana</strong></td>
                                <td>
                                    <span
                                        class="badge bg-{{ $laporanKegiatan->rencanaKegiatan->status == \App\Models\RencanaKegiatan::STATUS_SELESAI
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

                @if ($laporanKegiatan->kendala)
                    <div class="mb-4">
                        <h5 class="text-warning"><i class="fas fa-exclamation-triangle mr-1"></i>Kendala</h5>
                        <div class="p-3 bg-light rounded">
                            {!! nl2br(e($laporanKegiatan->kendala)) !!}
                        </div>
                    </div>
                @endif

                @if ($laporanKegiatan->evaluasi)
                    <div class="mb-4">
                        <h5 class="text-info"><i class="fas fa-chart-line mr-1"></i>Evaluasi</h5>
                        <div class="p-3 bg-light rounded">
                            {!! nl2br(e($laporanKegiatan->evaluasi)) !!}
                        </div>
                    </div>
                @endif

                @if (!empty($laporanKegiatan->dokumentasi))
                    <div class="mb-4">
                        <h5 class="text-secondary"><i class="fas fa-images mr-1"></i>Dokumentasi</h5>
                        <div class="row">
                            @foreach ($laporanKegiatan->dokumentasi as $index => $dokumentasi)
                                <div class="col-md-3 mb-3">
                                    <div class="card">
                                        <img src="{{ asset('public/storage/app/' . $dokumentasi) }}" class="card-img-top"
                                            style="height: 200px; object-fit: cover; width: 100%;"
                                            alt="Dokumentasi {{ $index + 1 }}" data-toggle="modal"
                                            data-target="#imageModal{{ $index }}">
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
        @if (!empty($laporanKegiatan->dokumentasi))
            @foreach ($laporanKegiatan->dokumentasi as $index => $dokumentasi)
                <div class="modal fade" id="imageModal{{ $index }}">
                    <div class="modal-dialog modal-lg modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h4 class="modal-title">Dokumentasi {{ $index + 1 }}</h4>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body text-center p-2">
                                <img src="{{ asset('public/storage/app/' . $dokumentasi) }}" class="img-fluid"
                                    alt="{{ basename($dokumentasi) }}" style="max-height: 70vh; object-fit: contain;">
                            </div>
                            <div class="modal-footer justify-content-between">
                                <a href="{{ asset('public/storage/app/' . $dokumentasi) }}"
                                    download="{{ basename($dokumentasi) }}" class="btn btn-primary btn-sm">
                                    <i class="fas fa-download mr-1"></i>Download
                                </a>
                                <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Tutup</button>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        @endif
    </div>

    {{-- SCRIPT MODAL --}}
    @push('scripts')
        <script>
            // Bootstrap 4 - modal akan otomatis berfungsi dengan data-toggle dan data-target
            console.log('Modal Bootstrap 4 siap');
        </script>
    @endpush

    {{-- RESPONSIVE MODAL CSS --}}
    @push('styles')
        <style>
            /* Responsive modal untuk mobile */
            @media (max-width: 576px) {
                .modal-dialog {
                    margin: 10px;
                    max-width: calc(100vw - 20px);
                }

                .modal-body {
                    padding: 10px !important;
                }

                .modal-body img {
                    max-height: 60vh !important;
                }

                .modal-footer {
                    padding: 10px !important;
                    flex-direction: column;
                    gap: 5px;
                }

                .modal-footer .btn {
                    width: 100%;
                    margin: 0;
                }
            }

            @media (max-width: 768px) {
                .modal-dialog.modal-lg {
                    max-width: 95%;
                }
            }
        </style>
    @endpush

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
