<?php

namespace App\Http\Controllers;

use App\Models\TbPenarikan; 
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TbPenarikanController extends Controller
{
    // =========================================================================
    // ANGGOTA
    // =========================================================================

    /**
     * Riwayat penarikan milik anggota yang sedang login.
     */
    public function index()
    {
        // Menggunakan TbPenarikan
        $penarikan = TbPenarikan::where('user_id', Auth::id())
            ->latest()
            ->paginate(10);
        
        // Mengarahkan ke file view penarikan.blade.php di dalam folder dashboard
        return view('dashboard.penarikan', compact('penarikan'));
    }

    /**
     * Form pengajuan penarikan baru.
     */
    public function create()
    {
        // Mengarahkan ke file pengajuanp.blade.php di dalam folder penarikan
        return view('penarikan.pengajuanp'); 
    }

    /**
     * Simpan pengajuan penarikan dari anggota.
     */
    public function store(Request $request)
    {
        $request->validate([
            'jumlah'         => 'required|numeric|min:10000',
            'nama_bank'      => 'required|string|max:100',
            'rekening_tujuan'=> 'required|string|max:50',
            'nama_rekening'  => 'required|string|max:100',
            'keterangan'     => 'nullable|string|max:500',
        ], [
            'jumlah.required'          => 'Jumlah penarikan wajib diisi.',
            'jumlah.min'               => 'Jumlah minimal penarikan adalah Rp 10.000.',
            'nama_bank.required'       => 'Nama bank wajib diisi.',
            'rekening_tujuan.required' => 'Nomor rekening wajib diisi.',
            'nama_rekening.required'   => 'Nama pemilik rekening wajib diisi.',
        ]);
        // Menggunakan TbPenarikan
        TbPenarikan::create([
            'user_id'          => Auth::id(),
            'kode_penarikan'   => TbPenarikan::generateKode(),
            'jumlah'           => $request->jumlah,
            'nama_bank'        => $request->nama_bank,
            'rekening_tujuan'  => $request->rekening_tujuan,
            'nama_rekening'    => $request->nama_rekening,
            'keterangan'       => $request->keterangan,
            'status'           => 'pending',
        ]);

        return redirect()->route('penarikan.index')
            ->with('success', 'Pengajuan penarikan berhasil dikirim. Silakan tunggu konfirmasi admin.');
    }

    // =========================================================================
    // ADMIN
    // =========================================================================

    /**
     * Daftar semua penarikan (admin).
     */
    public function adminIndex(Request $request)
    {
        // Menggunakan TbPenarikan
        $query = TbPenarikan::with('user')->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $query->whereHas('user', function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%');
            })->orWhere('kode_penarikan', 'like', '%' . $request->search . '%');
        }

        $penarikan     = $query->paginate(15)->withQueryString();
        $totalPending  = TbPenarikan::where('status', 'pending')->count();
        $totalDisetujui = TbPenarikan::where('status', 'disetujui')->count();
        $totalDitolak  = TbPenarikan::where('status', 'ditolak')->count();

        // Jika folder admin ditaruh di dalam folder views/admin/penarikan/index.blade.php
        // Silakan sesuaikan return view-nya jika lokasinya berbeda
        return view('admin.penarikan.index', compact(
    'penarikan', 'totalPending', 'totalDisetujui', 'totalDitolak'
));
    }

    /**
     * Detail penarikan (admin).
     */
    public function adminShow(TbPenarikan $penarikan)
    {
        $penarikan->load('user', 'diprosesoleh');
        return view('admin.penarikan_show', compact('penarikan'));
    }

    /**
     * Setujui pengajuan penarikan.
     */
    public function approve(Request $request, TbPenarikan $penarikan)
    {
        if ($penarikan->status !== 'pending') {
            return back()->with('error', 'Pengajuan ini sudah diproses sebelumnya.');
        }

        $request->validate([
            'catatan_admin' => 'nullable|string|max:500',
        ]);

        $penarikan->update([
            'status'        => 'disetujui',
            'catatan_admin' => $request->catatan_admin,
            'diproses_oleh' => Auth::id(),
            'diproses_at'   => now(),
        ]);

        return redirect()->route('admin.penarikan.index')
            ->with('success', "Penarikan {$penarikan->kode_penarikan} berhasil disetujui.");
    }

    /**
     * Tolak pengajuan penarikan.
     */
    public function reject(Request $request, TbPenarikan $penarikan)
    {
        if ($penarikan->status !== 'pending') {
            return back()->with('error', 'Pengajuan ini sudah diproses sebelumnya.');
        }

        $request->validate([
            'catatan_admin' => 'required|string|max:500',
        ], [
            'catatan_admin.required' => 'Alasan penolakan wajib diisi.',
        ]);

        $penarikan->update([
            'status'        => 'ditolak',
            'catatan_admin' => $request->catatan_admin,
            'diproses_oleh' => Auth::id(),
            'diproses_at'   => now(),
        ]);

        return redirect()->route('admin.penarikan.index')
            ->with('success', "Penarikan {$penarikan->kode_penarikan} telah ditolak.");
    }
}