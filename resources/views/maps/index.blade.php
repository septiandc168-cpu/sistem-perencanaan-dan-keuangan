@extends('layouts.mantis')

@section('content')
    <div class="">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-item-center">
                <h4 class="card_title">Daftar Rencana Kegiatan</h4>
                <div>
                    <a href="{{ route('maps.create') }}" class="btn btn-primary">
                        Buat Rencana Kegiatan
                    </a>
                </div>
            </div>
            <div class="card_body">
                <table class="table table-bordered" id="table">
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
                        @forelse ($reports as $i => $report)
                            <tr>
                                <td>{{ $i + 1 }}</td>
                                <td>{{ $report->nama_kegiatan ?? ($report->judul ?? '-') }}</td>
                                <td>{{ $report->desa ?? '-' }}</td>
                                <td>
                                    @if ($report->tanggal_mulai)
                                        {{ \Carbon\Carbon::parse($report->tanggal_mulai)->format('d/m/Y') }}
                                        @if ($report->tanggal_selesai)
                                            - {{ \Carbon\Carbon::parse($report->tanggal_selesai)->format('d/m/Y') }}
                                        @endif
                                    @else
                                        -
                                    @endif
                                </td>
                                <td>{{ $report->jenis_kegiatan ?? ($report->kategori ?? '-') }}</td>
                                <td>
                                    <span
                                        class="badge bg-{{ $report->status == 'direncanakan' ? 'secondary' : ($report->status == 'sedang berlangsung' ? 'warning text-dark' : ($report->status == 'selesai' ? 'success' : 'danger')) }}">{{ ucfirst($report->status) }}</span>
                                </td>
                                <td>
                                    <div class="dropdown">
                                        <a class="btn dropdown-toggle" href="#" role="button"
                                            data-bs-toggle="dropdown" aria-expanded="false">Aksi</a>
                                        <ul class="dropdown-menu">
                                            <li><a class="dropdown-item"
                                                    href="{{ route('maps.show', $report->id) }}">Detail</a></li>
                                            <li><a class="dropdown-item"
                                                    href="{{ route('maps.edit', $report->id) }}">Edit</a>
                                            </li>
                                            <li>
                                                <a class="dropdown-item text-danger"
                                                    href="{{ route('maps.destroy', $report->id) }}"
                                                    data-confirm-delete="true">Hapus Data</a>
                                            </li>
                                            <li><a class="dropdown-item" href="#">Buat Laporan</a></li>
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
    </div>

    {{-- Deletion is handled via SweetAlert using data-confirm-delete attribute. --}}

@endsection
