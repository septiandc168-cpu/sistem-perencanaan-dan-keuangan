@extends('layouts.adminlte')

@section('content_title', 'Edit Laporan Kegiatan')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3 class="mb-0">Edit Laporan Kegiatan</h3>
        <div>
            <a href="{{ route('laporan_kegiatan.show', $laporanKegiatan) }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left mx-1"></i>Kembali
            </a>
        </div>
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

    <form id="laporan-kegiatan-form" action="{{ route('laporan_kegiatan.update', $laporanKegiatan) }}" method="POST"
        enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <!-- Informasi Rencana Kegiatan (Readonly) -->
        <div class="card card-primary card-outline mb-4">
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
                            <input type="text" class="form-control" value="{{ $laporanKegiatan->rencanaKegiatan->nama_kegiatan }}" readonly>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Jenis Kegiatan</label>
                            <input type="text" class="form-control" value="{{ $laporanKegiatan->rencanaKegiatan->jenis_kegiatan }}" readonly>
                        </div>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Tujuan</label>
                    <textarea class="form-control" rows="2" readonly>{{ $laporanKegiatan->rencanaKegiatan->tujuan }}</textarea>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Penanggung Jawab</label>
                            <input type="text" class="form-control" value="{{ $laporanKegiatan->rencanaKegiatan->penanggung_jawab }}" readonly>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Kelompok</label>
                            <input type="text" class="form-control" value="{{ $laporanKegiatan->rencanaKegiatan->kelompok }}" readonly>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Tanggal Mulai</label>
                            <input type="date" class="form-control" 
                                value="{{ $laporanKegiatan->rencanaKegiatan->tanggal_mulai ? \Carbon\Carbon::parse($laporanKegiatan->rencanaKegiatan->tanggal_mulai)->format('Y-m-d') : '' }}" 
                                readonly>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Tanggal Selesai</label>
                            <input type="date" class="form-control" 
                                value="{{ $laporanKegiatan->rencanaKegiatan->tanggal_selesai ? \Carbon\Carbon::parse($laporanKegiatan->rencanaKegiatan->tanggal_selesai)->format('Y-m-d') : '' }}" 
                                readonly>
                        </div>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Lokasi (Desa)</label>
                    <input type="text" class="form-control" value="{{ $laporanKegiatan->rencanaKegiatan->desa }}" readonly>
                </div>
            </div>
        </div>

        <!-- Form Edit Laporan Kegiatan -->
        <div class="card card-warning card-outline mb-4">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-edit mr-1"></i>
                    Edit Detail Laporan
                </h3>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <label class="form-label fw-bold">Pelaksanaan Kegiatan <span class="text-danger">*</span></label>
                    <textarea name="pelaksanaan_kegiatan" class="form-control" rows="5" required
                        placeholder="Jelaskan bagaimana kegiatan dilaksanakan...">{{ old('pelaksanaan_kegiatan', $laporanKegiatan->pelaksanaan_kegiatan) }}</textarea>
                    @error('pelaksanaan_kegiatan')
                        <div class="text-danger small">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label fw-bold">Hasil Kegiatan <span class="text-danger">*</span></label>
                    <textarea name="hasil_kegiatan" class="form-control" rows="5" required
                        placeholder="Jelaskan hasil yang dicapai dari kegiatan...">{{ old('hasil_kegiatan', $laporanKegiatan->hasil_kegiatan) }}</textarea>
                    @error('hasil_kegiatan')
                        <div class="text-danger small">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label">Kendala <small class="text-muted">(Opsional)</small></label>
                    <textarea name="kendala" class="form-control" rows="4"
                        placeholder="Jelaskan kendala yang dihadapi selama pelaksanaan...">{{ old('kendala', $laporanKegiatan->kendala) }}</textarea>
                    @error('kendala')
                        <div class="text-danger small">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label">Evaluasi <small class="text-muted">(Opsional)</small></label>
                    <textarea name="evaluasi" class="form-control" rows="4"
                        placeholder="Berikan evaluasi terhadap kegiatan...">{{ old('evaluasi', $laporanKegiatan->evaluasi) }}</textarea>
                    @error('evaluasi')
                        <div class="text-danger small">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Dokumentasi Existing -->
                @if(!empty($laporanKegiatan->dokumentasi))
                    <div class="mb-3">
                        <label class="form-label fw-bold">Dokumentasi Saat Ini</label>
                        <div class="row">
                            @foreach($laporanKegiatan->dokumentasi as $index => $dokumentasi)
                                <div class="col-md-3 mb-3">
                                    <div class="card">
                                        <img src="{{ asset('storage/' . $dokumentasi) }}" 
                                             class="card-img-top" 
                                             style="height: 150px; object-fit: cover; width: 100%;"
                                             alt="Dokumentasi {{ $index + 1 }}">
                                        <div class="card-body p-2">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" 
                                                       name="remove_dokumentasi[]" 
                                                       value="{{ $dokumentasi }}"
                                                       id="remove_{{ $index }}">
                                                <label class="form-check-label" for="remove_{{ $index }}">
                                                    <small>Hapus</small>
                                                </label>
                                            </div>
                                            <small class="text-muted">Dokumentasi {{ $index + 1 }}</small>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <small class="form-text text-muted">Centang gambar yang ingin dihapus, lalu upload gambar baru di bawah</small>
                    </div>
                @endif

                <!-- Upload Dokumentasi Baru -->
                <div class="mb-3">
                    <label class="form-label fw-bold">
                        Upload Dokumentasi Baru 
                        <small class="text-muted">(Maksimal 5 file total, format JPG/PNG, maks 2MB per file)</small>
                    </label>
                    <input type="file" name="dokumentasi[]" class="form-control" accept="image/*" multiple>
                    @error('dokumentasi')
                        <div class="text-danger small">{{ $message }}</div>
                    @enderror
                    <small class="form-text text-muted">Pilih satu atau lebih file foto dokumentasi baru</small>
                </div>
            </div>
        </div>

        <div class="d-flex justify-content-end">
            <a href="{{ route('laporan_kegiatan.show', $laporanKegiatan) }}" class="btn btn-secondary me-2">
                <i class="fas fa-times mr-1"></i>Batal
            </a>
            <button type="submit" class="btn btn-warning">
                <i class="fas fa-save mr-1"></i>Update Laporan
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
    const existingDocs = document.querySelectorAll('input[name="remove_dokumentasi[]"]');
    
    fileInput.addEventListener('change', function() {
        const files = this.files;
        const existingCount = existingDocs.length;
        const maxFiles = 5;
        const maxSize = 2 * 1024 * 1024; // 2MB
        const allowedTypes = ['image/jpeg', 'image/jpg', 'image/png'];
        
        if (existingCount + files.length > maxFiles) {
            alert(`Maksimal ${maxFiles} file dokumentasi. Saat ini ada ${existingCount} file.`);
            this.value = '';
            return;
        }
        
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

    // Confirm delete
    const deleteBtn = document.querySelector('button[type="submit"]');
    deleteBtn.addEventListener('click', function(e) {
        const removeDocs = document.querySelectorAll('input[name="remove_dokumentasi[]"]:checked');
        if (removeDocs.length > 0) {
            const docNames = Array.from(removeDocs).map(cb => cb.nextElementSibling.textContent.trim());
            if (!confirm(`Anda akan menghapus ${removeDocs.length} dokumentasi:\n${docNames.join(', ')}\n\nLanjutkan?`)) {
                e.preventDefault();
            }
        }
    });
});
</script>

<style>
.card-img-top {
    cursor: pointer;
    transition: transform 0.2s;
}
.card-img-top:hover {
    transform: scale(1.05);
}
</style>
@endsection
