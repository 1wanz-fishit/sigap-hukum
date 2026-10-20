<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Analisis;
use App\Models\Pengajuan;
use App\Models\StatusLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PengajuanController extends Controller
{
    // Daftar semua pengajuan. Bisa disaring: ?status=diajukan&jenis_layanan=konsultasi
    public function index(Request $request)
    {
        $query = Pengajuan::with('user:id,nama,tipe_pelapor')->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('jenis_layanan')) {
            $query->where('jenis_layanan', $request->jenis_layanan);
        }

        return $query->paginate(20);
    }

    // Detail lengkap satu pengajuan
    public function show($id)
    {
        return Pengajuan::with([
            'user:id,nama,email,tipe_pelapor,nim_nip',
            'statusLogs' => fn ($q) => $q->latest(),
            'statusLogs.pengupdate:id,nama,role',
            'analisis',
            'penugasan.advokat',
            'evaluasi',
            'dokumentasis',
        ])->findOrFail($id);
    }

    // Tahap 2 PKBH: verifikasi
    public function verifikasi(Request $request, $id)
    {
        $data = $request->validate([
            'keterangan' => ['nullable', 'string', 'max:500'],
        ]);

        $pengajuan = Pengajuan::findOrFail($id);

        if ($pengajuan->status !== 'diajukan') {
            return response()->json([
                'message' => 'Verifikasi hanya bisa dilakukan pada pengajuan berstatus diajukan.',
            ], 422);
        }

        DB::transaction(function () use ($pengajuan, $data, $request) {
            $this->ubahStatus(
                $pengajuan, 'diverifikasi',
                $data['keterangan'] ?? 'Data lengkap dan layak, pengajuan diverifikasi.',
                $request->user()->id
            );
        });

        return $pengajuan->fresh();
    }

    // Tahap 3 PKBH: analisis kasus & rekomendasi penanganan
    public function analisis(Request $request, $id)
    {
        $data = $request->validate([
            'ringkasan_kasus' => ['required', 'string', 'min:10'],
            'rekomendasi' => ['required', 'string', 'min:10'],
            'bentuk_bantuan' => ['required', 'in:litigasi,non_litigasi'],
        ]);

        $pengajuan = Pengajuan::findOrFail($id);

        if ($pengajuan->status !== 'diverifikasi') {
            return response()->json([
                'message' => 'Analisis hanya bisa dibuat pada pengajuan berstatus diverifikasi.',
            ], 422);
        }

        DB::transaction(function () use ($pengajuan, $data, $request) {
            Analisis::create($data + [
                'pengajuan_id' => $pengajuan->id,
                'dibuat_oleh' => $request->user()->id,
            ]);

            $this->ubahStatus(
                $pengajuan, 'dianalisis',
                'Analisis kasus dan rekomendasi penanganan selesai disusun.',
                $request->user()->id
            );
        });

        return $pengajuan->fresh()->load('analisis');
    }

    // Ubah status sekaligus catat ke log riwayat
    private function ubahStatus(Pengajuan $pengajuan, string $status, string $keterangan, int $adminId): void
    {
        $pengajuan->update(['status' => $status]);

        StatusLog::create([
            'pengajuan_id' => $pengajuan->id,
            'status' => $status,
            'keterangan' => $keterangan,
            'diupdate_oleh' => $adminId,
        ]);
    }
}