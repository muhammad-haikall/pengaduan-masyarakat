<!DOCTYPE html>
<html>

<head>
    <title>Laporan Pengaduan</title>
    <style>
        body {
            font-family: sans-serif;
            font-size: 12px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        table,
        th,
        td {
            border: 1px solid black;
        }

        th,
        td {
            padding: 5px;
            text-align: left;
        }

        th {
            background-color: #f0f0f0;
        }
    </style>
</head>

<body>
    <h3>Laporan Pengaduan</h3>
    <p>Admin: {{ $user_nama }} ({{ $user_level }})</p>

    <table>
        <thead>
            <tr>
                <th>Tanggal</th>
                <th>Nama</th>
                <th>Username</th>
                <th>Isi Laporan</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($laporan as $l)
                <tr>
                    <td>{{ $l->tgl_pengaduan }}</td>
                    <td>{{ $l->nama }}</td>
                    <td>{{ $l->username }}</td>
                    <td>{{ $l->isi_laporan }}</td>
                    <td>
                        @if($l->status == '0') Belum direspon
                        @elseif($l->status == 'proses') Proses
                        @elseif($l->status == 'selesai') Selesai
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>

</html>