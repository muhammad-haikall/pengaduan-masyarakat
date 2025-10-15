<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Barryvdh\DomPDF\Facade\Pdf;

class AdminController extends Controller
{
    // =========================
    // LOGIN & LOGOUT
    // =========================
    public function showLogin()
    {
        return view('admin.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        $user = DB::table('petugas')->where('username', $request->username)->first();

        if (!$user || $user->password !== $request->password) {
            return back()->withInput()->with('error', 'Username atau password salah.');
        }

        Session::put('user_logged_in', true);
        Session::put('user_id', $user->id_petugas);
        Session::put('user_nama', $user->nama_petugas);
        Session::put('user_level', strtolower($user->level));

        return redirect()->route('admin.dashboard');
    }

    public function logout()
    {
        Session::flush();
        return redirect()->route('admin.login')->with('success', 'Logout berhasil.');
    }

    // =========================
    // DASHBOARD
    // =========================
    public function dashboard()
    {
        if (!Session::get('user_logged_in')) {
            return redirect()->route('admin.login')->with('error', 'Silakan login terlebih dahulu.');
        }

        $pengaduan = DB::table('pengaduan')
            ->join('masyarakat', 'pengaduan.nik', '=', 'masyarakat.nik')
            ->select('pengaduan.*', 'masyarakat.nama', 'masyarakat.username')
            ->orderBy('pengaduan.tgl_pengaduan', 'desc')
            ->get();

        return view('admin.dashboard', [
            'pengaduan' => $pengaduan,
            'user_nama' => Session::get('user_nama'),
            'user_level' => Session::get('user_level'),
        ]);
    }

    // =========================
    // REGISTER PETUGAS
    // =========================
    public function showRegisterPetugas()
    {
        if (Session::get('user_level') !== 'admin') {
            return redirect()->route('admin.dashboard')->with('error', 'Hanya admin yang bisa mengakses.');
        }
        return view('admin.register_petugas');
    }

    public function registerPetugas(Request $request)
    {
        if (Session::get('user_level') !== 'admin') {
            return redirect()->route('admin.dashboard')->with('error', 'Hanya admin yang bisa mengakses.');
        }

        $request->validate([
            'nama_petugas' => 'required|string|max:100',
            'username' => 'required|string|unique:petugas,username',
            'password' => 'required|string|min:4',
            'telp' => 'nullable|string|max:15',
            'level' => 'required|in:petugas,admin',
        ]);

        DB::table('petugas')->insert([
            'nama_petugas' => $request->nama_petugas,
            'username' => $request->username,
            'password' => $request->password,
            'telp' => $request->telp,
            'level' => strtolower($request->level),
        ]);

        return redirect()->route('admin.dashboard')->with('success', 'Petugas baru berhasil ditambahkan.');
    }

    // =========================
    // VERIFIKASI, VALIDASI, TANGGAPAN
    // =========================
    public function verifikasi($id)
    {
        if (!Session::get('user_logged_in')) {
            return redirect()->route('admin.login')->with('error', 'Silakan login terlebih dahulu.');
        }

        DB::table('pengaduan')->where('id_pengaduan', $id)->update([
            'status' => 'proses'
        ]);

        return redirect()->route('admin.dashboard')->with('success', 'Pengaduan berhasil diverifikasi!');
    }

    public function validasi(Request $request, $id)
    {
        if (!Session::get('user_logged_in')) {
            return redirect()->route('admin.login')->with('error', 'Silakan login terlebih dahulu.');
        }

        DB::table('pengaduan')->where('id_pengaduan', $id)->update([
            'status' => 'selesai'
        ]);

        return redirect()->route('admin.dashboard')->with('success', 'Pengaduan berhasil divalidasi!');
    }

    public function tanggapan(Request $request, $id)
    {
        if (!Session::get('user_logged_in')) {
            return redirect()->route('admin.login')->with('error', 'Silakan login terlebih dahulu.');
        }

        $request->validate([
            'tanggapan' => 'required|string'
        ]);

        DB::table('tanggapan')->insert([
            'id_pengaduan' => $id,
            'tgl_tanggapan' => now(),
            'tanggapan' => $request->tanggapan,
            'id_petugas' => Session::get('user_id'),
        ]);

        DB::table('pengaduan')->where('id_pengaduan', $id)->update([
            'status' => 'proses'
        ]);

        return redirect()->route('admin.dashboard')->with('success', 'Tanggapan berhasil dikirim!');
    }


    // =========================
    // EXPORT PDF LANGSUNG
    // =========================
    public function exportLaporan()
    {
        if (!Session::get('user_logged_in')) {
            return redirect()->route('admin.login')->with('error', 'Silakan login terlebih dahulu.');
        }

        $laporan = DB::table('pengaduan')
            ->join('masyarakat', 'pengaduan.nik', '=', 'masyarakat.nik')
            ->select('pengaduan.*', 'masyarakat.nama', 'masyarakat.username')
            ->orderBy('pengaduan.tgl_pengaduan', 'desc')
            ->get();

        $pdf = Pdf::loadView('admin.laporan_pdf', [
            'laporan' => $laporan,
            'user_nama' => Session::get('user_nama'),
            'user_level' => Session::get('user_level'),
        ])->setPaper('a4', 'portrait');

        return $pdf->download('laporan_pengaduan.pdf');
    }
}
