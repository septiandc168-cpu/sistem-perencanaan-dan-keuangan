@extends('layouts.adminlte')

@section('content_title', 'Buat Laporan Kegiatan')

@section('content')
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h5 class="mb-0">Form Laporan Kegiatan</h5>
        </div>

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form id="laporan-kegiatan-form" action="{{ route('laporan_kegiatan.store') }}" method="POST"
            enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="rencana_kegiatan_id" value="{{ $rencanaKegiatan->uuid }}">

            <!-- Informasi Rencana Kegiatan (Readonly) -->
            <div class="card mb-4">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-info-circle mr-1"></i>
                        Informasi Rencana Kegiatan
                    </h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Nama Kegiatan</label>
                                <input type="text" class="form-control" value="{{ $rencanaKegiatan->nama_kegiatan }}"
                                    readonly>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Jenis Kegiatan</label>
                                <input type="text" class="form-control" value="{{ $rencanaKegiatan->jenis_kegiatan }}"
                                    readonly>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Tujuan</label>
                        <textarea class="form-control" rows="2" readonly>{{ $rencanaKegiatan->tujuan }}</textarea>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Penanggung Jawab</label>
                                <input type="text" class="form-control" value="{{ $rencanaKegiatan->penanggung_jawab }}"
                                    readonly>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Kelompok</label>
                                <input type="text" class="form-control" value="{{ $rencanaKegiatan->kelompok }}"
                                    readonly>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Tanggal Mulai</label>
                                <input type="date" class="form-control"
                                    value="{{ $rencanaKegiatan->tanggal_mulai ? \Carbon\Carbon::parse($rencanaKegiatan->tanggal_mulai)->format('Y-m-d') : '' }}"
                                    readonly>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Tanggal Selesai</label>
                                <input type="date" class="form-control"
                                    value="{{ $rencanaKegiatan->tanggal_selesai ? \Carbon\Carbon::parse($rencanaKegiatan->tanggal_selesai)->format('Y-m-d') : '' }}"
                                    readonly>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Lokasi (Desa)</label>
                        <input type="text" class="form-control" value="{{ $rencanaKegiatan->desa }}" readonly>
                    </div>
                </div>
            </div>

            <!-- Form Laporan Kegiatan -->
            <div class="card mb-4">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-file-alt mr-1"></i>
                        Detail Laporan Kegiatan
                    </h3>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Pelaksanaan Kegiatan <span class="text-danger">*</span></label>
                        <textarea name="pelaksanaan_kegiatan" class="form-control" rows="5" required
                            placeholder="Deskripsikan proses pelaksanaan kegiatan dalam kalimat yang singkat dan jelas.&#10;Deskripsi dapat dibuat dalam bentuk satu paragraf, dua paragraf atau maksimal tiga paragraf.">{{ old('pelaksanaan_kegiatan') }}</textarea>
                        @error('pelaksanaan_kegiatan')
                            <div class="text-danger small">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Hasil Kegiatan <span class="text-danger">*</span></label>
                        <textarea name="hasil_kegiatan" class="form-control" rows="5" required
                            placeholder="Deskripsikan hasil yang dicapai dari kegiatan dalam kalimat yang singkat dan jelas.&#10;Deskripsi dapat dibuat dalam bentuk satu paragraf, dua paragraf atau maksimal tiga paragraf.">{{ old('hasil_kegiatan') }}</textarea>
                        @error('hasil_kegiatan')
                            <div class="text-danger small">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Kendala</label>
                        <textarea name="kendala" class="form-control" rows="4"
                            placeholder="Deskripsikan kendala yang dihadapi selama pelaksanaan kegiatan dalam kalimat yang singkat dan jelas.&#10;Deskripsi dapat dibuat dalam bentuk satu paragraf, dua paragraf atau maksimal tiga paragraf.&#10;Sertakan juga solusi yang telah dilakukan untuk mengatasi kendala tersebut">{{ old('kendala') }}</textarea>
                        @error('kendala')
                            <div class="text-danger small">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Evaluasi</label>
                        <textarea name="evaluasi" class="form-control" rows="4" placeholder="Deskripsikan evaluasi terhadap kegiatan dalam kalimat yang singkat dan jelas.&#10;Deskripsi dapat dibuat dalam bentuk satu paragraf, dua paragraf atau maksimal tiga paragraf.&#10;Sertakan juga pembelajaran dan rekomendasi untuk kegiatan selanjutnya">{{ old('evaluasi') }}</textarea>
                        @error('evaluasi')
                            <div class="text-danger small">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- <small class="text-muted">(Maksimal 5 file, format
                                JPG/PNG, maks 2MB per file)</small> --}}
                    <div class="mb-3">
                        <label class="form-label fw-bold">Dokumentasi</label>
                        <input type="file" name="dokumentasi[]" class="form-control" accept="image/*" multiple>
                        @error('dokumentasi')
                            <div class="text-danger small">{{ $message }}</div>
                        @enderror
                        <small class="form-text text-muted">Unggah file foto dokumentasi kegiatan<br>
                        Maksimal 5 foto dengan ukuran maksimal 2MB per foto<br>
                            Format: JPG, JPEG, PNG</small>
                    </div>
                </div>
            </div>

            <div class="d-flex justify-content-between mb-3">
                <a href="{{ route('rencana_kegiatan.index') }}" class="btn btn-secondary btn-sm"
                    style="height: 35px; display: flex; align-items: center; justify-content: center;">
                    <i class="fas fa-arrow-left mr-1"></i>Kembali
                </a>
                <button type="submit" class="btn btn-primary btn-sm"
                    style="height: 35px; display: flex; align-items: center; justify-content: center;">
                    <i class="fas fa-save mr-1"></i>Simpan
                </button>
            </div>
        </form>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Auto-resize textareas
            const textareas = document.querySelectorAll('textarea');
            textareas.forEach(textarea => {
                textarea.addEventListener('input', function() {
                    this.style.height = 'auto';
                    this.style.height = this.scrollHeight + 'px';
                });
            });

            // File validation
            const fileInput = document.querySelector('input[name="dokumentasi[]"]');
            fileInput.addEventListener('change', function() {
                const files = this.files;
                const maxSize = 2 * 1024 * 1024; // 2MB
                const allowedTypes = ['image/jpeg', 'image/jpg', 'image/png'];

                for (let i = 0; i < files.length; i++) {
                    const file = files[i];

                    if (file.size > maxSize) {
                        alert(`File ${file.name} terlalu besar. Maksimal 2MB per file.`);
                        this.value = '';
                        return;
                    }

                    if (!allowedTypes.includes(file.type)) {
                        alert(`File ${file.name} format tidak didukung. Gunakan format JPG atau PNG.`);
                        this.value = '';
                        return;
                    }
                }
            });
        });
    </script>
@endsection
