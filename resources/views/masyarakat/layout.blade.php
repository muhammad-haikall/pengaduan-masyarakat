<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Dashboard Masyarakat</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
        }

        header {
            background-color: #3490dc;
            color: white;
            padding: 15px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .logout-btn {
            background-color: #e3342f;
            color: white;
            padding: 6px 12px;
            border: none;
            cursor: pointer;
            border-radius: 5px;
        }
    </style>
</head>

<body>

    <header>
        <div>Selamat datang, {{ session('masyarakat')->username ?? 'Tamu' }}</div>
        <form method="POST" action="/logout">
            @csrf
            <button class="logout-btn">Logout</button>
        </form>
    </header>

    <main style="padding: 20px;">
        @yield('content')
    </main>

</body>

</html>