@extends('layouts.adminlte')

@section('content_title', 'Daftar Rencana Kegiatan')

@section('content')
    <div class="card">
        <div class="p-2 d-flex align-items-center justify-content-between border">
            <h4 class="h5 mb-0 d-flex align-items-center">
                Data Rencana Kegiatan
            </h4>

            <div>
                <a href="{{ route('rencana_kegiatan.create') }}" class="btn btn-primary btn-sm"
                    style="height: 35px; display: flex; align-items: center; justify-content: center;">
                    <i class="fas fa-plus mr-1"></i>
                    Tambah
                </a>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-striped table-sm" id="table2">
                    <thead>
                        <tr>
                            <th width="50">No</th>
                            <th width="120">Opsi</th>
                            <th>Nama Kegiatan</th>
                            <th>Penanggung Jawab</th>
                            <th>Desa</th>
                            <th>Tanggal Rencana</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($rencanaKegiatans as $i => $rencanaKegiatan)
                            <tr>
                                <td>{{ $i + 1 }}</td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a class="btn btn-primary btn-sm"
                                            style="width: 35px; height: 35px; display: flex; align-items: center; justify-content: center;"
                                            href="{{ route('rencana_kegiatan.show', $rencanaKegiatan) }}"
                                            title="Detail Rencana Kegiatan">
                                            <i class="fas fa-info"></i>
                                        </a>

                                        {{-- Tombol Laporan Kegiatan --}}
                                        @if ($rencanaKegiatan->status === \App\Models\RencanaKegiatan::STATUS_SELESAI)
                                            @if ($rencanaKegiatan->hasLaporan())
                                                <a class="btn btn-info btn-sm"
                                                    style="width: 35px; height: 35px; display: flex; align-items: center; justify-content: center;"
                                                    href="{{ route('laporan_kegiatan.show', $rencanaKegiatan->laporanKegiatan) }}"
                                                    title="Lihat Laporan">
                                                    <i class="fas fa-file-alt"></i>
                                                </a>
                                            @else
                                                @can('create', \App\Models\LaporanKegiatan::class)
                                                    <a class="btn btn-success btn-sm"
                                                        style="width: 35px; height: 35px; display: flex; align-items: center; justify-content: center;"
                                                        href="{{ route('laporan_kegiatan.create', ['rencana_kegiatan_id' => $rencanaKegiatan->uuid]) }}"
                                                        title="Buat Laporan">
                                                        <i class="fas fa-plus"></i>
                                                    </a>
                                                @else
                                                    <button class="btn btn-success btn-sm"
                                                        style="width: 35px; height: 35px; display: flex; align-items: center; justify-content: center;"
                                                        disabled title="Hubungi admin untuk membuat laporan">
                                                        <i class="fas fa-plus"></i>
                                                    </button>
                                                @endcan
                                            @endif
                                        @endif

                                        @if (auth()->user()->role->role_name === 'admin' &&
                                                $rencanaKegiatan->status !== \App\Models\RencanaKegiatan::STATUS_DITOLAK)
                                            <a class="btn btn-secondary btn-sm disabled"
                                                style="width: 35px; height: 35px; display: flex; align-items: center; justify-content: center; cursor: not-allowed;"
                                                title="Admin hanya dapat mengedit rencana kegiatan dengan status 'Ditolak'">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                        @else
                                            <a class="btn btn-warning btn-sm"
                                                style="width: 35px; height: 35px; display: flex; align-items: center; justify-content: center;"
                                                href="{{ route('rencana_kegiatan.edit', $rencanaKegiatan) }}"
                                                title="Edit Rencana Kegiatan">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                        @endif
                                        <a class="btn btn-danger btn-sm"
                                            style="width: 35px; height: 35px; display: flex; align-items: center; justify-content: center;"
                                            href="{{ route('rencana_kegiatan.destroy', $rencanaKegiatan) }}"
                                            title="Hapus Rencana Kegiatan" data-confirm-delete="true">
                                            <i class="fas fa-trash"></i>
                                        </a>
                                    </div>
                                </td>
                                <td>{{ $rencanaKegiatan->nama_kegiatan ?? ($rencanaKegiatan->judul ?? '-') }}
                                    <br>
                                    <small
                                        class="text-muted">{{ $rencanaKegiatan->jenis_kegiatan ?? ($rencanaKegiatan->kategori ?? '-') }}</small>
                                </td>
                                <td>{{ $rencanaKegiatan->penanggung_jawab ?? '-' }}</td>
                                <td>{{ $rencanaKegiatan->desa ?? '-' }}</td>
                                <td>
                                    @if ($rencanaKegiatan->tanggal_mulai)
                                        {{ \Carbon\Carbon::parse($rencanaKegiatan->tanggal_mulai)->format('d/m/Y') }}
                                        @if ($rencanaKegiatan->tanggal_selesai)
                                            -
                                            {{ \Carbon\Carbon::parse($rencanaKegiatan->tanggal_selesai)->format('d/m/Y') }}
                                        @endif
                                    @else
                                        -
                                    @endif
                                </td>
                                <td>
                                    <span
                                        class="badge bg-{{ $rencanaKegiatan->status == \App\Models\RencanaKegiatan::STATUS_DIAJUKAN
                                            ? 'warning text-dark'
                                            : ($rencanaKegiatan->status == \App\Models\RencanaKegiatan::STATUS_DISETUJUI
                                                ? 'primary'
                                                : ($rencanaKegiatan->status == \App\Models\RencanaKegiatan::STATUS_SELESAI
                                                    ? 'success'
                                                    : ($rencanaKegiatan->status == \App\Models\RencanaKegiatan::STATUS_DITOLAK
                                                        ? 'danger'
                                                        : 'secondary'))) }}">
                                        {{ ucfirst($rencanaKegiatan->status) }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center">Tidak ada data rencana kegiatan.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <style>
        .table th {
            background-color: #f8f9fa;
            font-weight: 600;
        }

        .btn-group .btn {
            margin-right: 2px;
        }

        .btn-group .btn:last-child {
            margin-right: 0;
        }

        .modal-body {
            max-height: 60vh;
            overflow-y: auto;
        }
    </style>


    {{-- Deletion is handled via SweetAlert using data-confirm-delete attribute. --}}

@endsection
