<!DOCTYPE html>
<html>

<head>
    <title>Dashboard Admin</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>

<body class="bg-light">
    <div class="container mt-5">
        <h3 class="mb-4 text-primary">Dashboard Admin</h3>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}
            </div>
        @endif

        <h1>Selamat datang, {{ session('admin_nama') }} ({{ session('admin_level') }})</h1>

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
                            <form action="{{ route('admin.pengaduan.tanggapan', $p->id_pengaduan) }}" method="POST">
                                @csrf
                                <input type="text" name="tanggapan" class="form-control mb-2"
                                    placeholder="Tulis tanggapan..." required>
                                <button class="btn btn-sm btn-info">Kirim</button>
                            </form>
                        </td>
                        <td>
                            @if($p->status == '0')
                                <a href="{{ route('admin.pengaduan.verifikasi', $p->id_pengaduan) }}"
                                    class="btn btn-sm btn-warning">Verifikasi</a>
                            @elseif($p->status == 'proses')
                                <form action="{{ route('admin.pengaduan.validasi', $p->id_pengaduan) }}" method="POST"
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

        <a href="{{ route('admin.laporan.pdf') }}" class=" btn btn-secondary mt-3">Generate Laporan</a>

        <form action="{{ route('admin.logout') }}" method="POST" class="mt-2">
            @csrf
            <button class="btn btn-danger">Logout</button>
        </form>
        <h3 class="mt-5 text-primary">Register Petugas Baru</h3>

        <form action="{{ route('admin.register') }}" method="POST" class="card p-4 shadow mt-3">
            @csrf
            <div class="mb-3">
                <label class="form-label">Nama Petugas</label>
                <input type="text" name="nama_petugas" class="form-control" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Username</label>
                <input type="text" name="username" class="form-control" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Password</label>
                <input type="password" name="password" class="form-control" required>
            </div>

            <div class="mb-3">
                <label class="form-label">No. Telp</label>
                <input type="text" name="telp" class="form-control">
            </div>

            <div class="mb-3">
                <label class="form-label">Level</label>
                <select name="level" class="form-control" required>
                    <option value="petugas">Petugas</option>
                    <option value="admin">Admin</option>
                </select>
            </div>


        </form>
        <a href="{{ route('admin.logout') }}" class="btn btn-danger mt-2">Logout</a>

    </div>
</body>

</html>