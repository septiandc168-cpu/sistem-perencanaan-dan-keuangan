@extends('layouts.adminlte')

@section('content_title', 'Daftar Rencana Kegiatan')

@section('content')
    <div class="card">
        <div class="card-header">
            <h4 class="card-title">Daftar Rencana Kegiatan</h4>
        </div>
        <div class="card-body">
            <div class="d-flex justify-content-end mb-2">
                <a href="{{ route('rencana_kegiatan.create') }}" class="btn btn-primary">
                    Buat Rencana Kegiatan
                </a>
            </div>
            <table class="table table-sm table-responsive" id="table2">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Kegiatan</th>
                        <th>Desa</th>
                        <th>Tanggal Rencana</th>
                        <th>Jenis Kegiatan</th>
                        <th>Status</th>
                        <th>Opsi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($rencanaKegiatans as $i => $rencanaKegiatan)
                        <tr>
                            <td>{{ $i + 1 }}</td>
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
                                    class="badge bg-{{ $rencanaKegiatan->status == 'diajukan' ? 'secondary' : ($rencanaKegiatan->status == 'disetujui dan sedang berlangsung' ? 'warning text-dark' : ($rencanaKegiatan->status == 'selesai' ? 'success' : 'danger')) }}">{{ ucfirst($rencanaKegiatan->status) }}</span>
                            </td>
                            <td>
                                <div class="dropdown">
                                    <a class="btn dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown"
                                        aria-expanded="false">Aksi</a>
                                    <ul class="dropdown-menu">
                                        <li><a class="dropdown-item"
                                                href="{{ route('rencana_kegiatan.show', $rencanaKegiatan) }}">Detail</a>
                                        </li>
                                        <li><a class="dropdown-item"
                                                href="{{ route('rencana_kegiatan.edit', $rencanaKegiatan) }}">Edit</a>
                                        </li>
                                        <li>
                                            <a class="dropdown-item text-danger"
                                                href="{{ route('rencana_kegiatan.destroy', $rencanaKegiatan) }}"
                                                data-confirm-delete="true">Hapus Data</a>
                                        </li>
                                    </ul>
                                </div>
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

    {{-- Deletion is handled via SweetAlert using data-confirm-delete attribute. --}}

@endsection
