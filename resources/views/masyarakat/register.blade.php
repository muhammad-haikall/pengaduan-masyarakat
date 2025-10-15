<!DOCTYPE html>
<html>

<head>
    <title>Register Masyarakat</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>

<body class="bg-light">

    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-5">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h3 class="mb-4 text-center text-primary">Registrasi Masyarakat</h3>

                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form method="POST" action="/register">
                            @csrf
                            <div class="mb-3">
                                <label class="form-label">NIK</label>
                                <input type="text" name="nik" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Nama</label>
                                <input type="text" name="nama" class="form-control" required>
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
                                <label class="form-label">No. Telepon</label>
                                <input type="text" name="telp" class="form-control" required>
                            </div>
                            <button type="submit" class="btn btn-primary w-100">Registrasi</button>
                        </form>

                        <div class="mt-3 text-center">
                            Sudah punya akun? <a href="/login">Login disini</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</body>

</html>