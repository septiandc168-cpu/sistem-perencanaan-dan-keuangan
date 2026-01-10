@extends('layouts.adminlte')

@section('content_title', 'Daftar Rencana Kegiatan')

@section('content')
    <div class="card">
        <div class="p-2 d-flex justify-content-between border">
            <h4 class="h5">Daftar Rencana Kegiatan</h4>
            <div>
                <a href="{{ route('rencana_kegiatan.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus mx-1"></i>
                    Buat Rencana Kegiatan
                </a>
            </div>
        </div>
        <div class="card-body">
            <table class="table table-sm" id="table2">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Opsi</th>
                        <th>Nama Kegiatan</th>
                        <th>Desa</th>
                        <th>Tanggal Rencana</th>
                        <th>Jenis Kegiatan</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($rencanaKegiatans as $i => $rencanaKegiatan)
                        <tr>
                            <td>{{ $i + 1 }}</td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <a class="btn btn-primary mx-1"
                                        style="width: 35px; height: 35px; display: flex; align-items: center; justify-content: center;"
                                        href="{{ route('rencana_kegiatan.show', $rencanaKegiatan) }}"
                                        title="Detail Rencana Kegiatan">
                                        <i class="fas fa-info"></i>
                                    </a>
                                    
                                    {{-- Tombol Laporan Kegiatan --}}
                                    @if($rencanaKegiatan->status === \App\Models\RencanaKegiatan::STATUS_SELESAI)
                                        @if($rencanaKegiatan->hasLaporan())
                                            <a class="btn btn-info mx-1"
                                                style="width: 35px; height: 35px; display: flex; align-items: center; justify-content: center;"
                                                href="{{ route('laporan_kegiatan.show', $rencanaKegiatan->laporanKegiatan) }}"
                                                title="Lihat Laporan">
                                                <i class="fas fa-file-alt"></i>
                                            </a>
                                        @else
                                            @can('create', \App\Models\LaporanKegiatan::class)
                                                <a class="btn btn-success mx-1"
                                                    style="width: 35px; height: 35px; display: flex; align-items: center; justify-content: center;"
                                                    href="{{ route('laporan_kegiatan.create', ['rencana_kegiatan_id' => $rencanaKegiatan->uuid]) }}"
                                                    title="Buat Laporan">
                                                    <i class="fas fa-plus"></i>
                                                </a>
                                            @else
                                                <button class="btn btn-success mx-1"
                                                        style="width: 35px; height: 35px; display: flex; align-items: center; justify-content: center;"
                                                        disabled
                                                        title="Hubungi admin untuk membuat laporan">
                                                    <i class="fas fa-plus"></i>
                                                </button>
                                            @endcan
                                        @endif
                                    @endif
                                    
                                    @if(auth()->user()->role->role_name === 'admin' && $rencanaKegiatan->status !== \App\Models\RencanaKegiatan::STATUS_DITOLAK)
                                    <a class="btn btn-secondary mx-1 disabled"
                                        style="width: 35px; height: 35px; display: flex; align-items: center; justify-content: center; cursor: not-allowed;"
                                        title="Admin hanya dapat mengedit rencana kegiatan dengan status 'Ditolak'">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                @else
                                    <a class="btn btn-warning mx-1"
                                        style="width: 35px; height: 35px; display: flex; align-items: center; justify-content: center;"
                                        href="{{ route('rencana_kegiatan.edit', $rencanaKegiatan) }}">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                @endif
                                    <a class="btn btn-danger mx-1"
                                        style="width: 35px; height: 35px; display: flex; align-items: center; justify-content: center;"
                                        href="{{ route('rencana_kegiatan.destroy', $rencanaKegiatan) }}"
                                        data-confirm-delete="true">
                                        <i class="fas fa-trash"></i>
                                    </a>
                                </div>
                            </td>
                            <td>{{ $rencanaKegiatan->nama_kegiatan ?? ($rencanaKegiatan->judul ?? '-') }}</td>
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
                            <td>{{ $rencanaKegiatan->jenis_kegiatan ?? ($rencanaKegiatan->kategori ?? '-') }}</td>
                            <td>
                                <span
                                    class="badge bg-{{ $rencanaKegiatan->status == \App\Models\RencanaKegiatan::STATUS_DIAJUKAN
                                        ? 'secondary'
                                        : ($rencanaKegiatan->status == \App\Models\RencanaKegiatan::STATUS_DISETUJUI
                                            ? 'warning text-dark'
                                            : ($rencanaKegiatan->status == \App\Models\RencanaKegiatan::STATUS_SELESAI
                                                ? 'success'
                                                : ($rencanaKegiatan->status == \App\Models\RencanaKegiatan::STATUS_DITOLAK
                                                    ? 'danger'
                                                    : 'secondary'))) }}">
                                    {{ ucfirst($rencanaKegiatan->status) }}
                                </span>
                            </td>
                            <!-- <td>
                                    <div class="dropdown">
                                        <a class="btn dropdown-toggle" href="#" role="button" data-toggle="dropdown"
                                            aria-expanded="false">
                                            Aksi
                                        </a>

                                        <div class="dropdown-menu">
                                            <a class="dropdown-item"
                                                href="{{ route('rencana_kegiatan.show', $rencanaKegiatan) }}">Detail</a>
                                            <a class="dropdown-item"
                                                href="{{ route('rencana_kegiatan.edit', $rencanaKegiatan) }}">Edit</a>
                                            <a class="dropdown-item text-danger"
                                                href="{{ route('rencana_kegiatan.destroy', $rencanaKegiatan) }}"
                                                data-confirm-delete="true">Hapus Data</a>
                                        </div>
                                    </div>
                                </td> -->
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

    {{-- Deletion is handled via SweetAlert using data-confirm-delete attribute. --}}

@endsection
