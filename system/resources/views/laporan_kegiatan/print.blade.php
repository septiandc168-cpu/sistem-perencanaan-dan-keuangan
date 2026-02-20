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
                            <div class="col-12">
                                <table class="table table-sm table-borderless">
                                    <tr>
                                        <td width="180" class="label-field"><strong>Nama Kegiatan</strong></td>
                                        <td class="value-field">{{ $laporanKegiatan->rencanaKegiatan->nama_kegiatan }}</td>
                                    </tr>
                                    <tr>
                                        <td class="label-field"><strong>Jenis Kegiatan</strong></td>
                                        <td class="value-field">{{ $laporanKegiatan->rencanaKegiatan->jenis_kegiatan }}</td>
                                    </tr>
                                    <tr>
                                        <td class="label-field"><strong>Tujuan</strong></td>
                                        <td class="value-field">{{ $laporanKegiatan->rencanaKegiatan->tujuan ?: '-' }}</td>
                                    </tr>
                                    <tr>
                                        <td class="label-field"><strong>Penanggung Jawab</strong></td>
                                        <td class="value-field">{{ $laporanKegiatan->rencanaKegiatan->penanggung_jawab ?: '-' }}</td>
                                    </tr>
                                    <tr>
                                        <td class="label-field"><strong>Kelompok</strong></td>
                                        <td class="value-field">{{ $laporanKegiatan->rencanaKegiatan->kelompok ?: '-' }}</td>
                                    </tr>
                                    <tr>
                                        <td class="label-field"><strong>Lokasi</strong></td>
                                        <td class="value-field">{{ $laporanKegiatan->rencanaKegiatan->desa ?: '-' }}</td>
                                    </tr>
                                    <tr>
                                        <td class="label-field"><strong>Tanggal Mulai</strong></td>
                                        <td class="value-field">{{ $laporanKegiatan->rencanaKegiatan->tanggal_mulai ? \Carbon\Carbon::parse($laporanKegiatan->rencanaKegiatan->tanggal_mulai)->format('d/m/Y') : '-' }}</td>
                                    </tr>
                                    <tr>
                                        <td class="label-field"><strong>Tanggal Selesai</strong></td>
                                        <td class="value-field">{{ $laporanKegiatan->rencanaKegiatan->tanggal_selesai ? \Carbon\Carbon::parse($laporanKegiatan->rencanaKegiatan->tanggal_selesai)->format('d/m/Y') : '-' }}</td>
                                    </tr>
                                    <tr>
                                        <td class="label-field"><strong>Status Rencana</strong></td>
                                        <td class="value-field">
                                            <span class="badge-status text-white">
                                                {{ ucfirst($laporanKegiatan->rencanaKegiatan->status) }}
                                            </span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="label-field"><strong>Tanggal Laporan</strong></td>
                                        <td class="value-field">{{ $laporanKegiatan->created_at->format('d/m/Y') }}</td>
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
                            <h6 class="section-title"><i class="fas fa-tasks mr-2"></i>Pelaksanaan Kegiatan</h6>
                            <div class="content-box">
                                {!! nl2br(e($laporanKegiatan->pelaksanaan_kegiatan)) !!}
                            </div>
                        </div>

                        <div class="mb-4">
                            <h6 class="section-title"><i class="fas fa-check-circle mr-2"></i>Hasil Kegiatan</h6>
                            <div class="content-box">
                                {!! nl2br(e($laporanKegiatan->hasil_kegiatan)) !!}
                            </div>
                        </div>

                        @if ($laporanKegiatan->kendala)
                            <div class="mb-4">
                                <h6 class="section-title"><i class="fas fa-exclamation-triangle mr-2"></i>Kendala</h6>
                                <div class="content-box">
                                    {!! nl2br(e($laporanKegiatan->kendala)) !!}
                                </div>
                            </div>
                        @endif

                        @if ($laporanKegiatan->evaluasi)
                            <div class="mb-4">
                                <h6 class="section-title"><i class="fas fa-chart-line mr-2"></i>Evaluasi</h6>
                                <div class="content-box">
                                    {!! nl2br(e($laporanKegiatan->evaluasi)) !!}
                                </div>
                            </div>
                        @endif

                        @if (!empty($laporanKegiatan->dokumentasi))
                            <div class="mb-4">
                                <h6 class="section-title"><i class="fas fa-images mr-2"></i>Dokumentasi</h6>
                                <div class="documentation-grid">
                                    @foreach ($laporanKegiatan->dokumentasi as $index => $dokumentasi)
                                        <div class="doc-item">
                                            <div class="doc-image-container">
                                                <img src="{{ asset('public/storage/app/' . $dokumentasi) }}"
                                                    class="doc-image"
                                                    alt="Dokumentasi {{ $index + 1 }}">
                                            </div>
                                            <div class="doc-caption">
                                                Dokumentasi {{ $index + 1 }}
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
        /* Base styles for both print and screen */
        .label-field {
            font-weight: bold !important;
            vertical-align: top !important;
            padding: 8px 12px !important;
            background-color: #f8f9fa !important;
            border: 1px solid #dee2e6 !important;
            width: 180px !important;
        }
        
        .value-field {
            padding: 8px 12px !important;
            vertical-align: top !important;
            border: 1px solid #dee2e6 !important;
            word-wrap: break-word !important;
            overflow-wrap: break-word !important;
            hyphens: auto !important;
            line-height: 1.5 !important;
        }
        
        .section-title {
            font-weight: bold !important;
            font-size: 14pt !important;
            color: #2c3e50 !important;
            margin-bottom: 12px !important;
            border-bottom: 2px !important;
            padding-bottom: 6px !important;
        }
        
        .content-box {
            padding: 15px !important;
            background-color: #f8f9fa !important;
            border: 1px solid #dee2e6 !important;
            border-radius: 6px !important;
            margin-bottom: 15px !important;
            text-align: justify !important;
            line-height: 1.6 !important;
            word-wrap: break-word !important;
            overflow-wrap: break-word !important;
            hyphens: auto !important;
        }
        
        .badge-status {
            padding: 4px 8px !important;
            background-color: #28a745 !important;
            color: white !important;
            border-radius: 4px !important;
            font-size: 11pt !important;
            font-weight: normal !important;
        }
        
        .documentation-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-top: 15px;
        }
        
        .doc-item {
            text-align: center;
            page-break-inside: avoid;
        }
        
        .doc-image-container {
            margin-bottom: 8px;
        }
        
        .doc-image {
            max-width: 100%;
            height: 180px;
            object-fit: cover;
            border: 1px solid #dee2e6;
            border-radius: 4px;
        }
        
        .doc-caption {
            font-size: 11pt;
            color: black;
            font-style: italic;
        }

        @media print {
            body {
                font-size: 12pt;
                line-height: 1.4;
                font-family: 'Times New Roman', Times, serif;
                color: black;
            }

            .card {
                border: none;
                page-break-inside: avoid;
                margin-bottom: 25px;
                box-shadow: none;
            }

            .card-header {
                background-color: #007bff !important;
                color: white !important;
                padding: 12px 15px;
                font-weight: bold;
                font-size: 14pt;
            }

            .card-header.bg-success {
                background-color: #28a745 !important;
                color: white !important;
                font-weight: bold;
            }

            .table-borderless {
                width: 100%;
                border-collapse: collapse;
            }

            .table-borderless td {
                border: 1px solid #dee2e6 !important;
                padding: 8px 12px !important;
            }

            .table-borderless tr {
                page-break-inside: avoid;
            }

            .label-field {
                font-weight: bold !important;
                vertical-align: top !important;
                padding: 8px 12px !important;
                background-color: #f8f9fa !important;
                border: 1px solid #dee2e6 !important;
                width: 180px !important;
            }
            
            .value-field {
                padding: 8px 12px !important;
                vertical-align: top !important;
                border: 1px solid #dee2e6 !important;
                word-wrap: break-word !important;
                overflow-wrap: break-word !important;
                hyphens: auto !important;
                line-height: 1.5 !important;
            }
            
            .section-title {
                font-weight: bold !important;
                font-size: 14pt !important;
                color: black !important;
                margin-bottom: 12px !important;
                border-bottom: 2px !important;
                padding-bottom: 6px !important;
            }
            
            .content-box {
                padding: 15px !important;
                background-color: white !important;
                border: 1px solid #dee2e6 !important;
                border-radius: 0 !important;
                margin-bottom: 15px !important;
                text-align: justify !important;
                line-height: 1.6 !important;
                word-wrap: break-word !important;
                overflow-wrap: break-word !important;
                hyphens: auto !important;
                page-break-inside: avoid;
            }
            
            .badge-status {
                padding: 4px 8px !important;
                background-color: #28a745 !important;
                color: white !important;
                border-radius: 4px !important;
                font-size: 11pt !important;
                font-weight: normal !important;
            }
            
            .documentation-grid {
                display: grid;
                grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
                gap: 15px;
                margin-top: 15px;
            }
            
            .doc-item {
                text-align: center;
                page-break-inside: avoid;
            }
            
            .doc-image-container {
                margin-bottom: 8px;
            }
            
            .doc-image {
                max-width: 100%;
                height: 150px;
                object-fit: cover;
                border: 1px solid #dee2e6;
            }
            
            .doc-caption {
                font-size: 10pt;
                color: black;
                font-style: italic;
            }

            .img-fluid {
                max-width: 100%;
                height: auto;
                border: 1px solid #dee2e6;
            }

            .no-print {
                display: none !important;
            }

            /* Print-specific overrides */
            .text-muted,
            .text-secondary,
            .text-primary,
            .text-success,
            .text-white,
            *[class*="text-"] {
                color: black !important;
            }
            
            /* Force all card header text to be black */
            .card-header *,
            .card-header h5,
            .card-header .mb-0,
            .card-header i {
                color: black !important;
            }

            /* Badge colors for print */
            .badge,
            .badge.bg-success,
            .badge.bg-primary,
            .badge.bg-secondary {
                background-color: #6c757d !important;
                color: white !important;
                border: none !important;
            }

            /* Override card header colors for monochrome printing */
            @media print and (monochrome) {
                .card-header,
                .card-header.bg-primary,
                .card-header.bg-success {
                    background-color: white !important;
                    color: black !important;
                    border: 2px solid black !important;
                }
                
                /* Force all text in card headers to be black */
                .card-header *,
                .card-header h5,
                .card-header .mb-0,
                .card-header i,
                .card-header.text-white *,
                .text-white {
                    color: black !important;
                }
                
                .section-title {
                    border-bottom: 2px !important;
                }
                
                .badge-status {
                    background-color: white !important;
                    color: black !important;
                    border: 1px solid black !important;
                }
            }

            /* Page layout */
            .print-title-section {
                margin-bottom: 50px !important;
                margin-top: 30px !important;
                text-align: center;
            }

            .print-title-section h2 {
                font-size: 18pt;
                font-weight: bold;
                margin-bottom: 10px;
            }

            .print-title-section h3 {
                font-size: 14pt;
                font-weight: bold;
                margin-bottom: 5px;
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
            
            .card {
                box-shadow: 0 2px 4px rgba(0,0,0,0.1);
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
