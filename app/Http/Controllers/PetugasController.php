<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PetugasController extends Controller
{
    public function showLogin()
    {
        return view('petugas.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required',
            'password' => 'required',
        ]);

        $petugas = DB::table('petugas')
            ->where('username', $request->username)
            ->where('password', $request->password) // kalau belum pakai hash
            ->first();

        if ($petugas) {
            $request->session()->put('petugas', [
                'id' => $petugas->id_petugas,
                'nama' => $petugas->nama_petugas,
                'username' => $petugas->username,
            ]);

            return redirect()->route('petugas.dashboard')->with('success', 'Login berhasil!');
        } else {
            return back()->with('error', 'Username atau password salah.');
        }
    }

    public function dashboard(Request $request)
    {
        // cek apakah session petugas ada
        if (!$request->session()->has('petugas')) {
            return redirect()->route('petugas.login')->with('error', 'Silakan login dulu');
        }

        // Ambil semua pengaduan untuk ditampilkan
        $pengaduan = DB::table('pengaduan')
            ->join('masyarakat', 'pengaduan.nik', '=', 'masyarakat.nik')
            ->select('pengaduan.*', 'masyarakat.nama', 'masyarakat.username')
            ->get();

        return view('petugas.dashboard', [
            'petugas' => $request->session()->get('petugas'),
            'pengaduan' => $pengaduan,
        ]);
    }

    public function logout(Request $request)
    {
        $request->session()->forget('petugas');
        return redirect()->route('petugas.login')->with('success', 'Berhasil logout');
    }

    // =========================
    // VERIFIKASI, VALIDASI, TANGGAPAN
    // =========================
    public function verifikasi(Request $request, $id)
    {
        if (!$request->session()->has('petugas')) {
            return redirect()->route('petugas.login')->with('error', 'Silakan login terlebih dahulu.');
        }

        DB::table('pengaduan')->where('id_pengaduan', $id)->update([
            'status' => 'proses'
        ]);

        return redirect()->route('petugas.dashboard')->with('success', 'Pengaduan berhasil diverifikasi!');
    }

    public function validasi(Request $request, $id)
    {
        if (!$request->session()->has('petugas')) {
            return redirect()->route('petugas.login')->with('error', 'Silakan login terlebih dahulu.');
        }

        DB::table('pengaduan')->where('id_pengaduan', $id)->update([
            'status' => 'selesai'
        ]);

        return redirect()->route('petugas.dashboard')->with('success', 'Pengaduan berhasil divalidasi!');
    }

    public function tanggapan(Request $request, $id)
    {
        if (!$request->session()->has('petugas')) {
            return redirect()->route('petugas.login')->with('error', 'Silakan login terlebih dahulu.');
        }

        $request->validate([
            'tanggapan' => 'required|string'
        ]);

        $petugas = $request->session()->get('petugas');

        DB::table('tanggapan')->insert([
            'id_pengaduan' => $id,
            'tgl_tanggapan' => now(),
            'tanggapan' => $request->tanggapan,
            'id_petugas' => $petugas['id'], // ambil dari session
        ]);

        DB::table('pengaduan')->where('id_pengaduan', $id)->update([
            'status' => 'proses'
        ]);

        return redirect()->route('petugas.dashboard')->with('success', 'Tanggapan berhasil dikirim!');
    }
}
