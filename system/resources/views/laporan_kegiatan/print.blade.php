@extends('layouts.print')

@section('content')
    <div class="container-fluid">
        <!-- Header Print (menggunakan CSS @page) -->
        <!-- Header Web (tidak muncul saat print) -->
        <div class="web-header no-print mb-4">
            <div class="col-12 text-center">
                <h2 class="text-dark">LAPORAN KEGIATAN</h2>
                <h3>{{ $laporanKegiatan->rencanaKegiatan->nama_kegiatan }}</h3>
                <p class="text-muted">Tanggal Cetak: {{ now()->format('d F Y') }}</p>
            </div>
        </div>

        <!-- Judul untuk Print (muncul di halaman pertama) -->
        <div class="print-title-section mb-4">
            <div class="col-12 text-center">
                <h2 class="text-dark">LAPORAN KEGIATAN</h2>
                <h3>{{ $laporanKegiatan->rencanaKegiatan->nama_kegiatan }}</h3>
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
                                        <td><strong>Tujuan</strong></td>
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
                                <table class="table table-sm table-borderless">
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
                                        <td><strong>Tanggal Laporan</strong></td>
                                        <td>{{ $laporanKegiatan->created_at->format('d/m/Y') }}</td>
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
                    <div class="card-header bg-success text-white mt-3">
                        <h5 class="mb-0">
                            <i class="fas fa-file-alt mr-1"></i>
                            Detail Laporan Kegiatan
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-4">
                            <h6 class="text-dark"><i class="fas fa-tasks mr-1"></i>Pelaksanaan Kegiatan</h6>
                            <div class="p-3 bg-light rounded">
                                {!! nl2br(e($laporanKegiatan->pelaksanaan_kegiatan)) !!}
                            </div>
                        </div>

                        <div class="mb-4">
                            <h6 class="text-dark"><i class="fas fa-check-circle mr-1"></i>Hasil Kegiatan</h6>
                            <div class="p-3 bg-light rounded">
                                {!! nl2br(e($laporanKegiatan->hasil_kegiatan)) !!}
                            </div>
                        </div>

                        @if ($laporanKegiatan->kendala)
                            <div class="mb-4">
                                <h6 class="text-dark"><i class="fas fa-exclamation-triangle mr-1"></i>Kendala</h6>
                                <div class="p-3 bg-light rounded">
                                    {!! nl2br(e($laporanKegiatan->kendala)) !!}
                                </div>
                            </div>
                        @endif

                        @if ($laporanKegiatan->evaluasi)
                            <div class="mb-4">
                                <h6 class="text-dark"><i class="fas fa-chart-line mr-1"></i>Evaluasi</h6>
                                <div class="p-3 bg-light rounded">
                                    {!! nl2br(e($laporanKegiatan->evaluasi)) !!}
                                </div>
                            </div>
                        @endif

                        @if (!empty($laporanKegiatan->dokumentasi))
                            <div class="mb-4">
                                <h6 class="text-dark"><i class="fas fa-images mr-1"></i>Dokumentasi</h6>
                                <div class="row">
                                    @foreach ($laporanKegiatan->dokumentasi as $index => $dokumentasi)
                                        <div class="col-md-4 mb-3">
                                            <div class="text-center">
                                                <img src="{{ asset('public/storage/app/' . $dokumentasi) }}"
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

        <!-- Footer Web (tidak muncul saat print) -->
        <div class="web-footer no-print mt-5">
            <div class="col-12 text-center">
                <hr>
                <p class="text-muted">
                    <small>
                        Dicetak oleh: {{ auth()->user()->name }}<br>
                        Rekam WeBe
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
                font-family: 'Times New Roman', Times, serif;
            }

            .card {
                border: none;
                page-break-inside: avoid;
                margin-bottom: 20px;
            }

            .card-header {
                background-color: #007bff !important;
                color: white !important;
                padding: 10px;
            }

            .card-header.bg-success {
                background-color: #28a745 !important;
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

            /* Badge colors */
            .badge.bg-success {
                background-color: #28a745 !important;
                color: white !important;
            }

            .badge.bg-secondary {
                background-color: #6c757d !important;
                color: white !important;
            }

            .text-muted {
                color: #6c757d !important;
                /* Abu-abu */
            }

            .text-secondary {
                color: #6c757d !important;
                /* Abu-abu */
            }

            .text-primary {
                color: #007bff !important;
                /* Biru */
            }

            .text-success {
                color: #28a745 !important;
                /* Hijau */
            }

            /* Override untuk mode tanpa background graphics - ubah semua teks berwarna menjadi hitam */
            @media print and (monochrome) {

                .text-muted,
                .text-secondary,
                .text-primary,
                .text-success,
                *[class*="text-"] {
                    color: black !important;
                }

                /* Override semua elemen dengan warna abu-abu */
                small.text-muted,
                .text-muted *,
                .text-secondary *,
                .text-primary *,
                .text-success * {
                    color: black !important;
                }

                /* Override badge colors - PRIORITAS TINGGI */
                .badge,
                .badge.bg-success,
                .badge.bg-primary,
                .badge.bg-secondary {
                    background-color: white !important;
                    color: black !important;
                    border: none !important;
                }

                /* Override card header colors */
                .card-header,
                .card-header.bg-primary,
                .card-header.bg-success {
                    background-color: white !important;
                    color: black !important;
                    border: none !important;
                }
            }

            /* Alternative approach - force black text when background graphics disabled */
            @media print {

                body:not(.color-enabled) .text-muted,
                body:not(.color-enabled) .text-secondary,
                body:not(.color-enabled) .text-primary,
                body:not(.color-enabled) .text-success,
                body:not(.color-enabled) *[class*="text-"] {
                    color: black !important;
                }

                /* Alternative approach for badges */
                body:not(.color-enabled) .badge,
                body:not(.color-enabled) .badge.bg-success,
                body:not(.color-enabled) .badge.bg-primary,
                body:not(.color-enabled) .badge.bg-secondary {
                    background-color: white !important;
                    color: black !important;
                    border: none !important;
                }
            }

            /* Tambahkan jarak antara header ke isi laporan */
            .print-title-section {
                margin-bottom: 50px !important;
                margin-top: 30px !important;
            }

            .print-title-section+.row {
                margin-top: 20px !important;
            }

            @page {
                margin: 2cm 1.5cm 2cm 1.5cm;
                size: A4;

                @top-left {
                    content: "{{ now()->format('d F Y') }}";
                    font-size: 11pt;
                    font-family: 'Times New Roman', Times, serif;
                    color: black;
                    margin: 0;
                    padding-bottom: 15px;
                }

                @top-right {
                    content: "Yayasan WeBe Konservasi Ketapang";
                    font-size: 11pt;
                    font-weight: bold;
                    font-family: 'Times New Roman', Times, serif;
                    color: black;
                    margin: 0;
                    padding-bottom: 15px;
                }

                @bottom-left {
                    content: "Rekam WeBe";
                    font-size: 10pt;
                    font-family: 'Times New Roman', Times, serif;
                    color: black;
                    margin: 0;
                }

                @bottom-right {
                    content: counter(page);
                    font-size: 10pt;
                    font-family: 'Times New Roman', Times, serif;
                    color: black;
                    margin: 0;
                }
            }
        }

        /* Web styles (untuk tampilan browser) */
        @media screen {
            .print-title-section {
                display: none !important;
            }
        }
    </style>

    <script>
        window.onload = function() {
            // Auto print
            setTimeout(function() {
                window.print();
            }, 500);
        };
    </script>
@endsection
