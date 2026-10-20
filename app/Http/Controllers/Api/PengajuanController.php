<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Pengajuan;
use App\Models\StatusLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PengajuanController extends Controller
{
    // Riwayat: semua pengajuan milik pelapor yang sedang login
    public function index(Request $request)
    {
        return $request->user()->pengajuans()->latest()->get();
    }

    // Ajukan layanan baru
    public function store(Request $request)
    {
        $data = $request->validate([
            'jenis_layanan' => ['required', 'in:konsultasi,pengaduan,bantuan_hukum'],
            'jenis_bantuan' => ['required_if:jenis_layanan,bantuan_hukum', 'nullable', 'in:litigasi,non_litigasi'],
            'kategori' => ['required', 'string', 'max:100'],
            'kronologi' => ['required', 'string', 'min:10'],
            'dokumen_bukti' => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:5120'],
        ]);

        $user = $request->user();

        $pengajuan = DB::transaction(function () use ($request, $data, $user) {
            $path = $request->hasFile('dokumen_bukti')
                ? $request->file('dokumen_bukti')->store('bukti', 'public')
                : null;

            // Nomor tiket: SH-TAHUN-URUTAN, contoh SH-2026-0004
            $tahun = now()->year;
            $urut = Pengajuan::whereYear('created_at', $tahun)->lockForUpdate()->count() + 1;

            $pengajuan = Pengajuan::create([
                'user_id' => $user->id,
                'no_tiket' => sprintf('SH-%d-%04d', $tahun, $urut),
                'jenis_layanan' => $data['jenis_layanan'],
                'jenis_bantuan' => $data['jenis_layanan'] === 'bantuan_hukum' ? ($data['jenis_bantuan'] ?? null) : null,
                'kategori' => $data['kategori'],
                'kronologi' => $data['kronologi'],
                'dokumen_bukti' => $path,
                'sumber' => 'online',
                'status' => 'diajukan',
            ]);

            StatusLog::create([
                'pengajuan_id' => $pengajuan->id,
                'status' => 'diajukan',
                'keterangan' => 'Pengajuan dikirim oleh pelapor.',
                'diupdate_oleh' => $user->id,
            ]);

            return $pengajuan;
        });

        return response()->json($pengajuan, 201);
    }

    // Detail + log perkembangan. Hanya milik sendiri, kalau milik orang lain hasilnya 404
    public function show(Request $request, $id)
    {
        return $request->user()->pengajuans()
            ->with([
                'statusLogs' => fn ($q) => $q->latest(),
                'penugasan.advokat:id,nama,foto,spesialisasi',
            ])
            ->findOrFail($id);
    }

    // Batalkan: hanya sebelum tahap penanganan
    public function batal(Request $request, $id)
    {
        $data = $request->validate([
            'alasan_batal' => ['required', 'string', 'min:5'],
        ]);

        $pengajuan = $request->user()->pengajuans()->findOrFail($id);

        if (! in_array($pengajuan->status, ['diajukan', 'diverifikasi', 'dianalisis'])) {
            return response()->json([
                'message' => 'Pengajuan yang sudah ditangani, selesai, atau dibatalkan tidak dapat dibatalkan.',
            ], 422);
        }

        DB::transaction(function () use ($pengajuan, $data, $request) {
            $pengajuan->update([
                'status' => 'dibatalkan',
                'alasan_batal' => $data['alasan_batal'],
            ]);

            StatusLog::create([
                'pengajuan_id' => $pengajuan->id,
                'status' => 'dibatalkan',
                'keterangan' => $data['alasan_batal'],
                'diupdate_oleh' => $request->user()->id,
            ]);
        });

        return $pengajuan->fresh();
    }
}