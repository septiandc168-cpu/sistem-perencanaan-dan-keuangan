<div>
    @extends('layouts.mantis')
    @section('content')
        <div class="">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-item-center">
                    <h4 class="card_title">Data Pegawai</h4>
                    <div>
                        <a href="{{ route('pegawai.create') }}" class="btn btn-primary">
                            Tambah Data
                        </a>
                    </div>
                </div>
                <div class="card_body">
                    <table class="table table-bordered" id="table">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama Pegawai</th>
                                <th>Bagian</th>
                                <th>Email</th>
                                <th>NIK</th>
                                <th>Jenis Kelamin</th>
                                <th>Umur</th>
                                <th>Tempat, Tanggal Lahir</th>
                                <th>Alamat</th>
                                <th>Foto Pegawai</th>
                                <th>Opsi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($pegawai as $index => $item)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $item->nama_pegawai }}</td>
                                    <td>{{ $item->bagian?->nama_bagian }}</td>
                                    <td>{{ $item->user->email }}</td>
                                    <td>{{ $item->nik }}</td>
                                    <td>{{ $item->jenis_kelamin }}</td>
                                    <td>{{ $item->umur }}</td>
                                    <td>{{ $item->tempat_lahir }},
                                        {{ \Carbon\Carbon::parse($item->tanggal_lahir)->locale('id')->translatedFormat('d F Y') }}
                                    </td>
                                    <td>{{ $item->alamat }}</td>
                                    <td>
                                        <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                            data-bs-target="#modalFoto{{ $item->id }}">Lihat Foto</button>
                                    </td>
                                    <td>
                                        <div class="dropdown">
                                            <a class="btn dropdown-toggle" href="#" role="button"
                                                data-bs-toggle="dropdown" aria-expanded="false">
                                                Aksi
                                            </a>

                                            <ul class="dropdown-menu">
                                                <li><a class="dropdown-item"
                                                        href="{{ route('pegawai.edit', $item->id) }}">Edit</a></li>
                                                <li>
                                                    <button type="button" class="btn text-danger" data-bs-toggle="modal"
                                                        data-bs-target="#confirmDeleteModal{{ $item->id }}">Hapus
                                                        Data</button>
                                                </li>
                                            </ul>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        @foreach ($pegawai as $item)
            <!-- Modal -->
            <div class="modal fade" id="confirmDeleteModal{{ $item->id }}" tabindex="-1"
                aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h1 class="modal-title fs-5" id="exampleModalLabel">Lanjutkan Penghapusan Data ?</h1>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <p>Data akan terhapus secara permanen, Klik <b>Lanjutkan</b> untuk menghapus data</p>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <form action="{{ route('pegawai.destroy', $item->id) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger">Lanjutkan</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
        @foreach ($pegawai as $item)
            <!-- Modal -->
            <div class="modal fade" id="modalFoto{{ $item->id }}" tabindex="-1" aria-labelledby="exampleModalLabel"
                aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h1 class="modal-title fs-5" id="exampleModalLabel">Foto Pegawai</h1>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <img src="{{ asset('storage/foto_pegawai/' . $item->foto) }}" alt="{{ $item->foto }}"
                                class="img-fluid">
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    @endsection
</div>
