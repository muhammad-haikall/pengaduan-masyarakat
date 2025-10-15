<!DOCTYPE html>
<html>

<head>
    <title>Register Petugas</title>
</head>

<body>
    <h2>Register Petugas</h2>

    @if(session('success'))
        <p style="color:green">{{ session('success') }}</p>
    @endif

    <form action="{{ route('admin.register.submit') }}" method="POST">
        @csrf
        <label>Nama Petugas:</label>
        <input type="text" name="nama_petugas" required><br><br>

        <label>Username:</label>
        <input type="text" name="username" required><br><br>

        <label>Password:</label>
        <input type="text" name="password" required><br><br>

        <label>Telepon:</label>
        <input type="text" name="telp"><br><br>

        <label>Level:</label>
        <select name="level" required>
            <option value="petugas">Petugas</option>
            <option value="admin">Admin</option>
        </select><br><br>

        <button type="submit">Register</button>
    </form>
</body>

</html>