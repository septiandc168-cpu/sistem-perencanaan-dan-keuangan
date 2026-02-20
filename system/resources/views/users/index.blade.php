@extends('layouts.adminlte')

@section('content_title', 'Daftar User')

@section('content')
    <div class="card">
        <div class="p-2 d-flex align-items-center justify-content-between border">
            <h4 class="h5 mb-0 d-flex align-items-center">
                Daftar User
            </h4>
            <div>
                <x-user.form-user />
            </div>
        </div>
        <div class="card-body">
            <x-alert :errors="$errors" />
            <table class="table table-bordered table-sm" id="table2">
                <thead class="bg-navy">
                    <tr>
                        <th class="align-middle" style=" padding-left: 18px; height: 35px; width: 35px">No</th>
                        <th class="align-middle" style=" padding-left: 18px; height: 35px; width: 85px">Aksi</th>
                        <th class="align-middle" style=" padding-left: 18px; height: 35px;">Nama</th>
                        <th class="align-middle" style=" padding-left: 18px; height: 35px;">Email</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($users as $index => $user)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <x-user.form-user :id="$user->id" />
                                    <a href="{{ route('users.destroy', $user->id) }}" class="btn btn-danger mx-1"
                                        style="width: 35px; height: 35px; display: flex; align-items: center; justify-content: center;"
                                        data-confirm-delete="true">
                                        <i class="fas fa-trash"></i>
                                    </a>
                                    <x-user.reset-password :id="$user->id" />
                                </div>
                            </td>
                            <td>{{ $user->name }}</td>
                            <td>{{ $user->email }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    {{-- @foreach ($users as $user)
        <!-- Modal -->
        <div class="modal fade" id="roleModal{{ $user->id }}" tabindex="-1" aria-labelledby="exampleModalLabel"
            aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="exampleModalLabel">Ganti Role</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <p class="my-2 text-center text-secondary">Mengganti Role dapat merubah hak akses dari user, klik
                            Ganti Role untuk melanjutkan perintah ini</p>
                        <form action="{{ route('users.update-role') }}" method="POST">
                            @csrf
                            <input type="hidden" name="user_id" value="{{ $user->id }}">
                            <div>
                                <label for="role_id">Tentukan Role Akses</label>
                                <select name="role_id" id="role_id" class="form-control">
                                    <option value="">Pilih Role</option>
                                    @foreach ($roles as $role)
                                        <option value="{{ $role->id }}">{{ $role->role_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <button type="submit" class="btn btn-primary mt-2 w-100">
                                    Ganti Role
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endforeach --}}
@endsection
