<?php

namespace Database\Seeders;

use App\Models\Advokat;
use App\Models\Analisis;
use App\Models\Evaluasi;
use App\Models\Pengajuan;
use App\Models\Penugasan;
use App\Models\StatusLog;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $password = Hash::make('password');

        // ---------- Akun ----------
        User::create([
            'nama' => 'Super Admin', 'email' => 'superadmin@sigaphukum.test',
            'password' => $password, 'role' => 'super_admin',
        ]);
        $pengurus = User::create([
            'nama' => 'Admin Pengurus', 'email' => 'pengurus@sigaphukum.test',
            'password' => $password, 'role' => 'admin_pengurus',
        ]);
        $relawan = User::create([
            'nama' => 'Admin Relawan', 'email' => 'relawan@sigaphukum.test',
            'password' => $password, 'role' => 'admin_relawan',
        ]);
        $civitas = User::create([
            'nama' => 'Ahmad Rizki', 'email' => 'ahmad@mhs.test',
            'password' => $password, 'role' => 'pelapor',
            'tipe_pelapor' => 'civitas', 'nim_nip' => '2388010001',
        ]);
        $warga = User::create([
            'nama' => 'Siti Aminah', 'email' => 'siti@warga.test',
            'password' => $password, 'role' => 'pelapor',
            'tipe_pelapor' => 'masyarakat',
        ]);

        // ---------- Advokat (data contoh) ----------
        $advokat1 = Advokat::create([
            'nama' => 'Advokat Contoh Satu, S.H.',
            'spesialisasi' => 'Hukum Perdata & Ketenagakerjaan',
            'bio' => 'Data contoh, nanti diganti data asli PKBH.',
            'kontak' => 'advokat1@contoh.test',
        ]);
        Advokat::create([
            'nama' => 'Advokat Contoh Dua, S.H.',
            'spesialisasi' => 'Hukum Pidana',
            'bio' => 'Data contoh, nanti diganti data asli PKBH.',
            'kontak' => 'advokat2@contoh.test',
        ]);

        // ---------- Pengajuan 1: baru masuk ----------
        Pengajuan::create([
            'user_id' => $civitas->id,
            'no_tiket' => 'SH-2026-0001',
            'jenis_layanan' => 'konsultasi',
            'kategori' => 'Perdata',
            'kronologi' => 'Ingin berkonsultasi soal perjanjian sewa kos.',
            'status' => 'diajukan',
        ]);

        // ---------- Pengajuan 2: sedang ditangani (formulir offline) ----------
        $p2 = Pengajuan::create([
            'user_id' => $warga->id,
            'no_tiket' => 'SH-2026-0002',
            'jenis_layanan' => 'bantuan_hukum',
            'jenis_bantuan' => 'litigasi',
            'kategori' => 'Ketenagakerjaan',
            'kronologi' => 'Upah belum dibayar oleh pemberi kerja selama 3 bulan.',
            'sumber' => 'offline',
            'diinput_oleh' => $relawan->id,
            'status' => 'ditangani',
        ]);
        Analisis::create([
            'pengajuan_id' => $p2->id,
            'ringkasan_kasus' => 'Sengketa upah, bukti kontrak tersedia.',
            'rekomendasi' => 'Ditempuh jalur litigasi karena mediasi sudah gagal.',
            'bentuk_bantuan' => 'litigasi',
            'dibuat_oleh' => $pengurus->id,
        ]);
        Penugasan::create([
            'pengajuan_id' => $p2->id,
            'advokat_id' => $advokat1->id,
            'tanggal_mulai' => now()->toDateString(),
            'catatan' => 'Advokat mulai menyusun gugatan.',
        ]);
        foreach (['diajukan', 'diverifikasi', 'dianalisis', 'ditangani'] as $status) {
            StatusLog::create([
                'pengajuan_id' => $p2->id,
                'status' => $status,
                'keterangan' => 'Status berubah menjadi ' . $status,
                'diupdate_oleh' => $pengurus->id,
            ]);
        }

        // ---------- Pengajuan 3: selesai ----------
        $p3 = Pengajuan::create([
            'user_id' => $civitas->id,
            'no_tiket' => 'SH-2026-0003',
            'jenis_layanan' => 'pengaduan',
            'kategori' => 'Hak Cipta',
            'kronologi' => 'Karya tulis dipublikasikan tanpa izin.',
            'status' => 'selesai',
        ]);
        Evaluasi::create([
            'pengajuan_id' => $p3->id,
            'hasil_evaluasi' => 'Masalah selesai lewat mediasi.',
            'tindak_lanjut' => 'Tidak ada tindak lanjut.',
            'dibuat_oleh' => $pengurus->id,
        ]);
    }
}