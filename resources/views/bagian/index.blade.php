@extends('layouts.mantis')
@section('content')
<div class="card">
    <div class="card-header">
        <h4 class="card-title">Data Bagian</h4>
    </div>
    <div class="card-body">
        <table class="table">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Bagian</th>
                    <th>Opsi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($bagians as $index => $bagian)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $bagian->nama_bagian }}</td>
                        <td>
                            <a href="{{ route('bagian.show', $bagian->id) }}">Detail</a>
                            <a href="{{ route('bagian.destroy', $bagian->id) }}" class="text-danger" data-confirm-delete="true">Hapus</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
