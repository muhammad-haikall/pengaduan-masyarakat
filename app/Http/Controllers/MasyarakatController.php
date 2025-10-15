<?php


namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Masyarakat;

class MasyarakatController extends Controller
{
    public function showLogin()
    {
        return view('masyarakat.login');
    }

    public function login(Request $request)
    {
        $user = DB::table('masyarakat')
            ->where('username', $request->username)
            ->first();

        if ($user && $request->password === $user->password) {
            session(['masyarakat' => $user]);
            return redirect('/dashboard/pengaduan');
        }

        return redirect()->back()->with('error', 'Username atau password salah!');
    }
    public function storePengaduan(Request $request)
    {
        $user = session('masyarakat');
        if (!$user) {
            return redirect('/login');
        }

        $request->validate([
            'nama' => 'required|string',
            'isi_laporan' => 'required|min:10',
            'foto' => 'nullable|image|mimes:jpg,jpeg,png|max:2048'
        ]);


        // Jika ingin update nama user di tabel masyarakat:
        \App\Models\Masyarakat::where('nik', $user->nik)->update(['nama' => $request->nama]);

        $fotoName = null;
        if ($request->hasFile('foto')) {
            $fotoName = time() . '_' . $request->file('foto')->getClientOriginalName();
            $request->file('foto')->move(public_path('uploads'), $fotoName);
        }

        \DB::table('pengaduan')->insert([
            'tgl_pengaduan' => $request->tgl_pengaduan ?? date('Y-m-d'),
            'nik' => $user->nik ?? null,
            'isi_laporan' => $request->isi_laporan,
            'foto' => $fotoName,
            'status' => '0'
        ]);

        return redirect('/dashboard/pengaduan')->with('success', 'Pengaduan berhasil dikirim!');
    }
    public function showDashboard()
    {
        if (!session('masyarakat')) {
            return redirect('/login');
        }

        // Ambil semua laporan milik user yang sedang login
        $laporanSaya = DB::table('pengaduan')
            ->where('nik', session('masyarakat')->nik)
            ->orderBy('tgl_pengaduan', 'desc')
            ->get();

        return view('masyarakat.dashboard', compact('laporanSaya'));
    }
    public function showRegister()
    {
        return view('masyarakat.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'nik' => 'required|numeric|unique:masyarakat,nik',
            'nama' => 'required|string',
            'username' => 'required|string|unique:masyarakat,username',
            'password' => 'required|string|min:6',
            'telp' => 'required|string',
        ]);

        Masyarakat::create([
            'nik' => $request->nik,
            'nama' => $request->nama,
            'username' => $request->username,
            'password' => $request->password,
            'telp' => $request->telp,
        ]);

        return redirect('/login')->with('success', 'Registrasi berhasil, silakan login.');
    }


    public function editPengaduan($id)
    {
        if (!session('masyarakat')) {
            return redirect('/login');
        }

        $laporan = DB::table('pengaduan')
            ->where('id_pengaduan', $id)
            ->where('nik', session('masyarakat')->nik)
            ->first();

        if (!$laporan) {
            return redirect('/dashboard/pengaduan')->with('error', 'Laporan tidak ditemukan.');
        }

        return view('masyarakat.edit', compact('laporan'));
    }

    public function updatePengaduan(Request $request, $id)
    {
        if (!session('masyarakat')) {
            return redirect('/login');
        }

        $request->validate([
            'nama' => 'required|string',
            'isi_laporan' => 'required|min:10',
            'foto' => 'nullable|image|mimes:jpg,jpeg,png|max:2048'
        ]);

        $laporan = DB::table('pengaduan')
            ->where('id_pengaduan', $id)
            ->where('nik', session('masyarakat')->nik)
            ->first();

        if (!$laporan) {
            return redirect('/dashboard/pengaduan')->with('error', 'Laporan tidak ditemukan.');
        }

        // Update nama user jika diubah
        \App\Models\Masyarakat::where('nik', session('masyarakat')->nik)->update(['nama' => $request->nama]);

        $fotoName = $laporan->foto;
        if ($request->hasFile('foto')) {
            $fotoName = time() . '_' . $request->file('foto')->getClientOriginalName();
            $request->file('foto')->move(public_path('uploads'), $fotoName);
        }

        DB::table('pengaduan')
            ->where('id_pengaduan', $id)
            ->update([
                'isi_laporan' => $request->isi_laporan,
                'foto' => $fotoName,
                // tgl_pengaduan dan status tidak diubah
            ]);

        return redirect('/dashboard/pengaduan')->with('success', 'Laporan berhasil diperbarui!');
    }

    public function deletePengaduan($id)
    {
        if (!session('masyarakat')) {
            return redirect('/login');
        }

        // Pastikan hanya laporan milik user yang bisa dihapus
        DB::table('pengaduan')
            ->where('id_pengaduan', $id)
            ->where('nik', session('masyarakat')->nik)
            ->delete();

        return redirect('/dashboard/pengaduan')->with('success', 'Laporan berhasil dihapus!');
    }

    public function logout()
    {
        session()->forget('masyarakat');
        return redirect('/login');
    }
}