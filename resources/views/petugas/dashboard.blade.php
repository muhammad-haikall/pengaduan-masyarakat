<!DOCTYPE html>
<html>

<head>
    <title>Dashboard Petugas</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>

<body class="bg-light">
    <div class="container mt-5">
        <h3 class="mb-4 text-primary">Dashboard Petugas</h3>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}
            </div>
        @endif

        <h1>Selamat datang, {{ session('petugas_nama') }} ({{ session('petugas_level') }})</h1>

        <table class="table table-bordered table-striped mt-4">
            <thead>
                <tr>
                    <th>Tanggal</th>
                    <th>Nama</th>
                    <th>Username</th>
                    <th>Isi Laporan</th>
                    <th>Status</th>
                    <th>Tanggapan</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($pengaduan as $p)
                    <tr>
                        <td>{{ $p->tgl_pengaduan }}</td>
                        <td>{{ $p->nama }}</td>
                        <td>{{ $p->username }}</td>
                        <td>{{ $p->isi_laporan }}</td>
                        <td>
                            @if($p->status == '0')
                                <span class="badge bg-warning text-dark">Belum direspon</span>
                            @elseif($p->status == 'proses')
                                <span class="badge bg-primary">Proses</span>
                            @elseif($p->status == 'selesai')
                                <span class="badge bg-success">Selesai</span>
                            @endif
                        </td>
                        <td>
                            <form action="{{ route('petugas.pengaduan.tanggapan', $p->id_pengaduan) }}" method="POST">
                                @csrf
                                <input type="text" name="tanggapan" class="form-control mb-2"
                                    placeholder="Tulis tanggapan..." required>
                                <button class="btn btn-sm btn-info">Kirim</button>
                            </form>
                        </td>
                        <td>
                            @if($p->status == '0')
                                <a href="{{ route('petugas.pengaduan.verifikasi', $p->id_pengaduan) }}"
                                    class="btn btn-sm btn-warning">Verifikasi</a>
                            @elseif($p->status == 'proses')
                                <form action="{{ route('petugas.pengaduan.validasi', $p->id_pengaduan) }}" method="POST"
                                    style="display:inline;">
                                    @csrf
                                    <button class="btn btn-sm btn-success">Validasi</button>
                                </form>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center text-muted">Belum ada data pengaduan</td>
                    </tr>
                @endforelse
            </tbody>
        </table>


        <a href="{{ route('petugas.logout') }}" class="btn btn-danger mt-2">Logout</a>


    </div>
</body></html>