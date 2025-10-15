<!DOCTYPE html>
<html>

<head>
    <title>Edit Laporan Pengaduan</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>

<body class="bg-light">

    <div class="container mt-5">
        <h3 class="text-center text-primary mb-4">Edit Laporan Pengaduan</h3>

        @if(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        <div class="card shadow-sm p-4">
            <form action="{{ route('pengaduan.update', $laporan->id_pengaduan) }}" method="POST"
                enctype="multipart/form-data">
                @csrf

                <div class="mb-3">
                    <label class="form-label">Tanggal Pengaduan</label>
                    <input type="date" class="form-control" value="{{ $laporan->tgl_pengaduan }}" disabled>
                </div>

                <div class="mb-3">
                    <label class="form-label">Nama</label>
                    <input type="text" name="nama" class="form-control" value="{{ session('masyarakat')->nama }}"
                        required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Isi Laporan *</label>
                    <textarea name="isi_laporan" class="form-control" rows="4"
                        required>{{ $laporan->isi_laporan }}</textarea>
                </div>

                <div class="mb-3">
                    <label class="form-label">Bukti/Gambar (Opsional)</label>
                    @if($laporan->foto)
                        <div class="mb-2">
                            <img src="{{ asset('uploads/' . $laporan->foto) }}" width="100">
                        </div>
                    @endif
                    <input type="file" name="foto" class="form-control" accept="image/*">
                    <small class="text-muted">Kosongkan jika tidak ingin mengubah gambar.</small>
                </div>

                <button type="submit" class="btn btn-primary w-100">Simpan Perubahan</button>
                <a href="/dashboard/pengaduan" class="btn btn-secondary w-100 mt-2">Kembali</a>
            </form>
        </div>
    </div>

</body>

</html>