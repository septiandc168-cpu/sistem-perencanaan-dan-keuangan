@extends('layouts.adminlte')
@section('content_title', 'Home')

@section('content')
    <div class="">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">{{ __('Dashboard') }}</div>

                    <div class="card-body">
                        @if (session('status'))
                            <div class="alert alert-success" role="alert">
                                {{ session('status') }}
                            </div>
                        @endif
                        Welcome to Aplikasi Perencanaan Kegiatan, <strong
                            class="capitalize">{{ auth()->user()->name }}</strong>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
