@extends('layouts.adminlte')
@section('content_title', 'Dashboard')

@section('content')
    <div class="container-fluid">
        <!-- Alert Status -->
        @if (session('status'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle mr-2"></i>
                {{ session('status') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif

        <!-- Welcome Card -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card bg-gradient-primary">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-8">
                                <h2 class="text-white mb-2">Selamat Datang di Rekam WeBe</h2>
                                <p class="text-white">Halo, <strong
                                        class="text-white">{{ ucwords(auth()->user()->name) }}</strong>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="row mb-4">
            <!-- Total User -->
            <div class="col-lg-4 col-6">
                <div class="small-box bg-info">
                    <div class="inner">
                        <h3>{{ \App\Models\User::count() }}</h3>
                        <p>Total User</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-users"></i>
                    </div>
                    @if (Auth::user()->role_id == 1)
                        <a href="{{ route('users.index') }}" class="small-box-footer">
                            Lihat Detail <i class="fas fa-arrow-circle-right"></i>
                        </a>
                    @else
                        <a href="#" class="small-box-footer">
                            Info <i class="fas fa-arrow-circle-right"></i>
                        </a>
                    @endif
                </div>
            </div>

            <!-- Total Rencana Kegiatan -->
            <div class="col-lg-4 col-6">
                <div class="small-box bg-success">
                    <div class="inner">
                        <h3>{{ \App\Models\RencanaKegiatan::count() }}</h3>
                        <p>Total Rencana Kegiatan</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-calendar"></i>
                    </div>
                    <a href="{{ route('rencana_kegiatan.index') }}" class="small-box-footer">
                        Lihat Detail <i class="fas fa-arrow-circle-right"></i>
                    </a>
                </div>
            </div>

            <!-- Total Laporan Kegiatan -->
            @if (isset(auth()->user()->role->role_name) && in_array(auth()->user()->role->role_name, ['admin', 'supervisor']))
                <div class="col-lg-4 col-6">
                    <div class="small-box bg-warning">
                        <div class="inner">
                            <h3>{{ \App\Models\LaporanKegiatan::count() }}</h3>
                            <p>Total Laporan Kegiatan</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-file-alt"></i>
                        </div>
                        <a href="{{ route('laporan_kegiatan.index') }}" class="small-box-footer">
                            Lihat Detail <i class="fas fa-arrow-circle-right"></i>
                        </a>
                    </div>
                </div>
            @else
                <!-- Placeholder untuk non-admin/supervisor -->
                <div class="col-lg-4 col-6">
                    <div class="small-box bg-secondary">
                        <div class="inner">
                            <h3>-</h3>
                            <p>Laporan Kegiatan</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-lock"></i>
                        </div>
                        <a href="#" class="small-box-footer">
                            Terbatas <i class="fas fa-arrow-circle-right"></i>
                        </a>
                    </div>
                </div>
            @endif
        </div>

        <!-- Tables Row -->
        <div class="row">
            <!-- Rencana Kegiatan Terbaru -->
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-calendar-plus mr-2"></i>
                            Rencana Kegiatan Terbaru
                        </h3>
                        <div class="card-tools">
                            <a href="{{ route('rencana_kegiatan.index') }}" class="btn btn-tool btn-sm">
                                <i class="fas fa-list"></i> Lihat Semua
                            </a>
                        </div>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>Tanggal</th>
                                        <th>Kegiatan</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $rencanaTerbaru = \App\Models\RencanaKegiatan::latest()->take(5)->get();
                                    @endphp
                                    @forelse($rencanaTerbaru as $rencana)
                                        <tr>
                                            <td>{{ \Carbon\Carbon::parse($rencana->tanggal_kegiatan)->format('d/m/Y') }}
                                            </td>
                                            <td>
                                                <a href="{{ route('rencana_kegiatan.show', $rencana->id) }}"
                                                    class="text-decoration-none">
                                                    {{ Str::limit($rencana->nama_kegiatan, 30) }}
                                                </a>
                                            </td>
                                            <td>
                                                @switch($rencana->status)
                                                    @case('pending')
                                                        <span class="badge badge-warning">Pending</span>
                                                    @break

                                                    @case('disetujui')
                                                        <span class="badge badge-success">Disetujui</span>
                                                    @break

                                                    @case('rejected')
                                                        <span class="badge badge-danger">Ditolak</span>
                                                    @break

                                                    @case('selesai')
                                                        <span class="badge badge-info">Selesai</span>
                                                    @break

                                                    @default
                                                        <span class="badge badge-secondary">{{ $rencana->status }}</span>
                                                @endswitch
                                            </td>
                                        </tr>
                                        @empty
                                            <tr>
                                                <td colspan="3" class="text-center text-muted">Belum ada data rencana
                                                    kegiatan</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Rencana Disetujui Table -->
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-check-circle mr-2"></i>
                                Rencana Kegiatan Disetujui
                            </h3>
                            <div class="card-tools">
                                <a href="{{ route('rencana_kegiatan.index') }}?status=disetujui" class="btn btn-tool btn-sm">
                                    <i class="fas fa-list"></i> Lihat Semua
                                </a>
                            </div>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-striped table-hover">
                                    <thead>
                                        <tr>
                                            <th width="5%">No</th>
                                            <th width="15%">Tanggal</th>
                                            <th>Nama Kegiatan</th>
                                            <th width="15%">Pelaksana</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                            $rencanaDisetujui = \App\Models\RencanaKegiatan::where(
                                                'status',
                                                'disetujui',
                                            )
                                                ->latest()
                                                ->take(10)
                                                ->get();
                                        @endphp
                                        @forelse($rencanaDisetujui as $index => $rencana)
                                            <tr>
                                                <td>{{ $index + 1 }}</td>
                                                <td>{{ \Carbon\Carbon::parse($rencana->tanggal_kegiatan)->format('d/m/Y') }}
                                                </td>
                                                <td>
                                                    <a href="{{ route('rencana_kegiatan.show', $rencana->id) }}"
                                                        class="text-decoration-none">
                                                        {{ $rencana->nama_kegiatan }}
                                                    </a>
                                                </td>
                                                <td>
                                                    @if ($rencana->user)
                                                        {{ ucwords($rencana->user->name) }}
                                                    @else
                                                        <span class="text-muted">-</span>
                                                    @endif
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="7" class="text-center text-muted py-4">
                                                    <i class="fas fa-inbox fa-2x mb-2"></i><br>
                                                    Belum ada rencana kegiatan yang disetujui
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Kegiatan Selesai Terbaru -->
            <div class="row mt-4">
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-check-double mr-2"></i>
                                Kegiatan Selesai Terbaru
                            </h3>
                            <div class="card-tools">
                                <a href="{{ route('rencana_kegiatan.index') }}?status=selesai" class="btn btn-tool btn-sm">
                                    <i class="fas fa-list"></i> Lihat Semua
                                </a>
                            </div>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-striped table-hover">
                                    <thead>
                                        <tr>
                                            <th>Tanggal</th>
                                            <th>Kegiatan</th>
                                            <th>Pelaksana</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                            $kegiatanSelesai = \App\Models\RencanaKegiatan::where('status', 'selesai')
                                                ->latest()
                                                ->take(5)
                                                ->get();
                                        @endphp
                                        @forelse($kegiatanSelesai as $kegiatan)
                                            <tr>
                                                <td>{{ \Carbon\Carbon::parse($kegiatan->tanggal_kegiatan)->format('d/m/Y') }}
                                                </td>
                                                <td>
                                                    <a href="{{ route('rencana_kegiatan.show', $kegiatan->id) }}"
                                                        class="text-decoration-none">
                                                        {{ Str::limit($kegiatan->nama_kegiatan, 30) }}
                                                    </a>
                                                </td>
                                                <td>
                                                    @if ($kegiatan->user)
                                                        {{ ucwords($kegiatan->user->name) }}
                                                    @else
                                                        <span class="text-muted">-</span>
                                                    @endif
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="3" class="text-center text-muted">Belum ada kegiatan yang
                                                    selesai</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endsection
