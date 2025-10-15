<!DOCTYPE html>
<html>

<head>
    <title>Form Layanan Pengaduan</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
</head>

<body class="bg-light">

    <div class="container mt-5">
        <h3 class="text-center text-danger mb-4">Form Layanan Pengaduan Masyarakat</h3>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <div class="card shadow-sm p-4 mb-4">
            <form action="/dashboard/pengaduan" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="mb-3">
                    <label class="form-label">Tanggal Pengaduan</label>
                    <input type="date" name="tgl_pengaduan" class="form-control" value="{{ date('Y-m-d') }}" required>
                </div>

                  <div class="mb-3">
                     <label class="form-label">Nama</label>
                     <input type="text" name="nama" class="form-control" value="{{ session('masyarakat')->nama }}" required>
                   
                    </div>


                <div class="mb-3">
                    <label class="form-laabel">Isi Laporan *</label>
                    <textarea name="isi_laporan" class="form-control" rows="4" placeholder="Tulis laporan Anda..."
                        required></textarea>
                </div>

                <div class="mb-3">
                    <label class="form-label">Bukti/Gambar (Opsional)</label>
                    <input type="file" name="foto" class="form-control" accept="image/*">
                </div>

                <button type="submit" class="btn btn-danger w-100">
                    <i class="bi bi-check-circle"></i> Konfirmasi & Kirim Laporan
                </button>
            </form>
        </div>

        <div class="card shadow-sm p-4">
            <h5 class="mb-3">Laporan Saya</h5>
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>Tanggal</th>
                    
                        <th>Isi Laporan</th>
                        <th>Bukti</th>
                        <th>Status</th>
                            <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($laporanSaya ?? [] as $laporan)
                        <tr>
                            <td>{{ $laporan->tgl_pengaduan }}</td>
                            <td>{{ $laporan->isi_laporan }}</td>
                            <td>
                                @if($laporan->foto)
                                    <img src="{{ asset('uploads/' . $laporan->foto) }}" width="60">
                                @else
                                    -
                                @endif
                                </td> <td>
                                @if($laporan->status == '0')
                                    <span class="badge bg-warning text-dark">Belum Dikonfirmasi</span>
                                @else
                                    <span class="badge bg-success">Terkirim</span>
                                @endif
                                </td>
                                <td>
                                    <a href=" /dashboard/pengaduan/{{ $laporan->id_pengaduan }}/edit"
                                    class="btn btn-sm btn-primary" title="Edit">
                                    <i class="bi bi-pencil-square"></i>
                                    </a>
                                  <form action="/dashboard/pengaduan/{{ $laporan->id_pengaduan }}/delete" method="POST" style="display:inline;" onsubmit="return confirm('Yakin hapus laporan ini?')">
                                      @csrf
                                        @method('DELETE')
                                        <button class="btn btn-sm btn-danger" title="Hapus">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center">Belum ada laporan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
              <div class="d-flex justify-content-end mb-3">
                    <form action="{{ url('/logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-outline-danger">
                            <i class="bi bi-box-arrow-right"></i> Logout
                        </button>
                    </form>
             </div>

</body>

</html>