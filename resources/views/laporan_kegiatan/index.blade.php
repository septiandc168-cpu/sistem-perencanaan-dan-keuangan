@extends('layouts.adminlte')

@section('content_title', 'Daftar Laporan Kegiatan')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3 class="mb-0">Daftar Laporan Kegiatan</h3>
        <div>
            @can('create', \App\Models\LaporanKegiatan::class)
                <a href="{{ route('rencana_kegiatan.index') }}" class="btn btn-primary">
                    <i class="fas fa-plus mx-1"></i>Pilih Rencana Kegiatan
                </a>
            @endcan
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible">
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible">
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            {{ session('error') }}
        </div>
    @endif

    <div class="card">
        <div class="card-header">
            <h3 class="card-title">
                <i class="fas fa-list mr-1"></i>
                Data Laporan Kegiatan
            </h3>
        </div>
        <div class="card-body">
            @if($laporans->count() > 0)
                <div class="table-responsive">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th width="50">No</th>
                                <th>Nama Kegiatan</th>
                                <th>Penanggung Jawab</th>
                                <th>Tanggal Laporan</th>
                                <th>Status Rencana</th>
                                <th width="120">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($laporans as $index => $laporan)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>
                                        <strong>{{ $laporan->rencanaKegiatan->nama_kegiatan }}</strong>
                                        <br>
                                        <small class="text-muted">{{ $laporan->rencanaKegiatan->jenis_kegiatan }}</small>
                                    </td>
                                    <td>{{ $laporan->rencanaKegiatan->penanggung_jawab ?: '-' }}</td>
                                    <td>{{ $laporan->created_at->format('d/m/Y') }}</td>
                                    <td>
                                        <span class="badge bg-{{ $laporan->rencanaKegiatan->status == \App\Models\RencanaKegiatan::STATUS_SELESAI
                                            ? 'success'
                                            : 'secondary' }}">
                                            {{ ucfirst($laporan->rencanaKegiatan->status) }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('laporan_kegiatan.show', $laporan) }}" 
                                               class="btn btn-info btn-sm" 
                                               title="Lihat Detail">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            
                                            @can('print', $laporan)
                                                <a href="{{ route('laporan_kegiatan.print', $laporan) }}" 
                                                   class="btn btn-secondary btn-sm" 
                                                   target="_blank"
                                                   title="Cetak Laporan">
                                                    <i class="fas fa-print"></i>
                                                </a>
                                            @endcan
                                            
                                            @can('update', $laporan)
                                                <a href="{{ route('laporan_kegiatan.edit', $laporan) }}" 
                                                   class="btn btn-warning btn-sm" 
                                                   title="Edit Laporan">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                            @endcan
                                            
                                            @can('delete', $laporan)
                                                <button type="button" 
                                                        class="btn btn-danger btn-sm" 
                                                        title="Hapus Laporan"
                                                        data-bs-toggle="modal" 
                                                        data-bs-target="#deleteModal{{ $laporan->id }}">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            @endcan
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination jika diperlukan -->
                @if(method_exists($laporans, 'links'))
                    <div class="d-flex justify-content-center mt-3">
                        {{ $laporans->links() }}
                    </div>
                @endif
            @else
                <div class="text-center py-5">
                    <i class="fas fa-file-alt fa-3x text-muted mb-3"></i>
                    <h5 class="text-muted">Belum ada laporan kegiatan</h5>
                    <p class="text-muted">
                        @can('create', \App\Models\LaporanKegiatan::class)
                            Buat laporan kegiatan untuk rencana kegiatan yang telah selesai.
                        @else
                            Hubungi admin untuk membuat laporan kegiatan.
                        @endcan
                    </p>
                    @can('create', \App\Models\LaporanKegiatan::class)
                        <a href="{{ route('rencana_kegiatan.index') }}" class="btn btn-primary">
                            <i class="fas fa-plus mr-1"></i>Buat Laporan Baru
                        </a>
                    @endcan
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Modal Konfirmasi Hapus -->
@foreach($laporans as $laporan)
    @can('delete', $laporan)
        <div class="modal fade" id="deleteModal{{ $laporan->id }}" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">
                            <i class="fas fa-exclamation-triangle text-warning mr-1"></i>
                            Konfirmasi Hapus
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <p>Apakah Anda yakin ingin menghapus laporan kegiatan ini?</p>
                        <div class="alert alert-warning">
                            <strong>Data yang akan dihapus:</strong>
                            <br>Nama Kegiatan: <strong>{{ $laporan->rencanaKegiatan->nama_kegiatan }}</strong>
                            <br>Tanggal Laporan: <strong>{{ $laporan->created_at->format('d/m/Y H:i') }}</strong>
                        </div>
                        <p class="text-danger">
                            <small><i class="fas fa-info-circle"></i> Tindakan ini tidak dapat dibatalkan.</small>
                        </p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            <i class="fas fa-times mr-1"></i>Batal
                        </button>
                        <form action="{{ route('laporan_kegiatan.destroy', $laporan) }}" method="POST" style="display: inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger">
                                <i class="fas fa-trash mr-1"></i>Hapus
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endcan
@endforeach

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
@endsection
