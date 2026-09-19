<?php

namespace Tests\Feature;

use App\Models\Antrian;
use App\Models\JenisLayanan;
use App\Models\Pengaturan;
use App\Models\User;
use App\Services\AntrianService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RevisiFiturTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    /** Test Fitur 1: Batas Maksimal Pelanggan Harian */
    public function test_batas_maksimal_pelanggan_harian_tercapai()
    {
        $pengaturan = Pengaturan::getAktif();
        $pengaturan->update(['batas_maksimal_pelanggan_harian' => 2, 'max_booking_per_user' => 10]);

        $layanan = JenisLayanan::first();
        $service = app(AntrianService::class);

        $user1 = User::create(['name' => 'U1', 'username' => 'u1', 'email' => 'u1@test.com', 'password' => bcrypt('password'), 'role' => 'pelanggan']);
        $user2 = User::create(['name' => 'U2', 'username' => 'u2', 'email' => 'u2@test.com', 'password' => bcrypt('password'), 'role' => 'pelanggan']);
        $user3 = User::create(['name' => 'U3', 'username' => 'u3', 'email' => 'u3@test.com', 'password' => bcrypt('password'), 'role' => 'pelanggan']);

        $service->buatAntrian($user1, $layanan, 'B 1111 AAA');
        $service->buatAntrian($user2, $layanan, 'B 2222 BBB');

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Batas kuota pendaftaran antrian');

        $service->buatAntrian($user3, $layanan, 'B 3333 CCC');
    }

    /** Test Fitur 2: Maksimum Booking Per User */
    public function test_maksimum_booking_per_user_tercapai()
    {
        $pengaturan = Pengaturan::getAktif();
        $pengaturan->update(['max_booking_per_user' => 1, 'batas_maksimal_pelanggan_harian' => 50]);

        $layanan = JenisLayanan::first();
        $service = app(AntrianService::class);
        $user = User::create(['name' => 'User Booking', 'username' => 'ubooking', 'email' => 'ubooking@test.com', 'password' => bcrypt('password'), 'role' => 'pelanggan']);

        $service->buatAntrian($user, $layanan, 'B 1000 ABC');

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Anda telah mencapai batas maksimum booking antrian aktif');

        $service->buatAntrian($user, $layanan, 'B 2000 ABC');
    }

    /** Test Fitur 3: Detail Jenis Cuci Mobil pada Harga */
    public function test_detail_jenis_cuci_tersedia()
    {
        $layananMobil = JenisLayanan::where('jenis_kendaraan', 'mobil')->first();

        $this->assertNotNull($layananMobil->jenis_cuci);
        $this->assertNotNull($layananMobil->deskripsi);
        $this->assertGreaterThan(0, $layananMobil->est_durasi_menit);
    }

    /** Test Fitur 4: Laporan Keseluruhan Admin Route */
    public function test_laporan_keseluruhan_admin_akses()
    {
        $admin = User::where('role', 'admin')->first();

        $response = $this->actingAs($admin)->get(route('admin.laporan.index'));

        $response->assertStatus(200);
        $response->assertSee('Laporan Keseluruhan Sistem');
        $response->assertSee('Rekapitulasi Per Jenis Layanan');
    }

    /** Test Fitur: Booking Untuk Besok */
    public function test_booking_untuk_besok_berhasil()
    {
        $layanan = JenisLayanan::first();
        $service = app(AntrianService::class);
        $user = User::create(['name' => 'User Besok', 'username' => 'ubesok', 'email' => 'ubesok@test.com', 'password' => bcrypt('password'), 'role' => 'pelanggan']);

        $besok = today()->addDay()->toDateString();
        $antrian = $service->buatAntrian($user, $layanan, 'B 9999 BSK', $besok);

        $this->assertEquals($besok, $antrian->tanggal_booking->toDateString());
        $this->assertEquals('A001', $antrian->nomor_antrian);
    }
}
