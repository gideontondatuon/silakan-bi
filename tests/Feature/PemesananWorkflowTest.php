<?php

namespace Tests\Feature;

use App\Enums\PemesananStatus;
use App\Enums\Role;
use App\Models\LayoutRuangan;
use App\Models\Pemesanan;
use App\Models\Ruangan;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class PemesananWorkflowTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;
    protected User $user;
    protected Ruangan $ruangan;
    protected LayoutRuangan $layout;

    protected function setUp(): void
    {
        parent::setUp();
        Storage::fake('public');

        $this->admin = User::factory()->create([
            'username' => 'admin_test',
            'role' => Role::ADMIN,
            'nama_unit' => 'Administrator',
            'password' => Hash::make('password'),
        ]);

        $this->user = User::factory()->create([
            'username' => 'user_test',
            'role' => Role::USER,
            'nama_unit' => 'Sistem Pembayaran',
            'password' => Hash::make('password'),
        ]);

        $this->ruangan = Ruangan::create([
            'nama_ruangan' => 'Ruang Tondano',
            'lokasi' => 'Lantai 3',
            'kapasitas' => 50,
            'status' => 'aktif',
        ]);

        $this->layout = LayoutRuangan::create([
            'nama_layout' => 'U-Shape',
            'ruangan_id' => $this->ruangan->id,
            'kapasitas_layout' => 50,
        ]);

        $this->ruangan->layouts()->attach($this->layout->id);
    }

    public function test_user_can_submit_pemesanan(): void
    {
        $tomorrow = Carbon::tomorrow()->format('Y-m-d');
        $file = UploadedFile::fake()->create('disposisi.pdf', 100, 'application/pdf');

        $response = $this->actingAs($this->user)->post(route('pemesanan.store'), [
            'ruangan_id' => $this->ruangan->id,
            'layout_ruangan_id' => $this->layout->id,
            'tanggal_kegiatan' => $tomorrow,
            'waktu_mulai' => '09:00',
            'waktu_selesai' => '11:00',
            'judul_kegiatan' => 'Rapat Koordinasi Tim SP',
            'pic_kegiatan' => 'Ahmad PIC',
            'jenis_pic' => 'Organik',
            'no_wa_pic' => '081234567890',
            'jumlah_tamu' => 25,
            'catatan_user' => 'Mohon proyektor disiapkan',
            'file_disposisi' => $file,
        ]);

        $response->assertRedirect(route('pemesanan.index'));
        $this->assertDatabaseHas('pemesanan', [
            'user_id' => $this->user->id,
            'ruangan_id' => $this->ruangan->id,
            'judul_kegiatan' => 'Rapat Koordinasi Tim SP',
            'status' => PemesananStatus::PENDING->value,
        ]);
    }

    public function test_conflicting_schedule_is_rejected_on_submission(): void
    {
        $targetDate = Carbon::tomorrow()->format('Y-m-d');

        // Existing approved booking
        Pemesanan::create([
            'kode_pemesanan' => 'PMS-TEST-001',
            'user_id' => $this->user->id,
            'ruangan_id' => $this->ruangan->id,
            'layout_ruangan_id' => $this->layout->id,
            'tanggal_kegiatan' => $targetDate,
            'waktu_mulai' => '09:00',
            'waktu_selesai' => '11:00',
            'judul_kegiatan' => 'Rapat Pagi',
            'pic_kegiatan' => 'PIC 1',
            'jenis_pic' => 'Organik',
            'no_wa_pic' => '081234567890',
            'jumlah_tamu' => 15,
            'status' => PemesananStatus::DISETUJUI->value,
            'approved_by' => $this->admin->id,
            'approved_at' => now(),
        ]);

        $file = UploadedFile::fake()->create('disposisi_bentrok.pdf', 100, 'application/pdf');

        // Attempt overlapping booking (10:00 - 12:00)
        $response = $this->actingAs($this->user)->from(route('pemesanan.create'))->post(route('pemesanan.store'), [
            'ruangan_id' => $this->ruangan->id,
            'layout_ruangan_id' => $this->layout->id,
            'tanggal_kegiatan' => $targetDate,
            'waktu_mulai' => '10:00',
            'waktu_selesai' => '12:00',
            'judul_kegiatan' => 'Rapat Bentrok',
            'pic_kegiatan' => 'PIC 2',
            'jenis_pic' => 'Organik',
            'no_wa_pic' => '081234567899',
            'jumlah_tamu' => 20,
            'file_disposisi' => $file,
        ]);

        $response->assertSessionHas('error');
        $this->assertDatabaseMissing('pemesanan', [
            'judul_kegiatan' => 'Rapat Bentrok',
        ]);
    }

    public function test_admin_can_approve_pemesanan(): void
    {
        $targetDate = Carbon::tomorrow()->format('Y-m-d');

        $pemesanan = Pemesanan::create([
            'kode_pemesanan' => 'PMS-TEST-002',
            'user_id' => $this->user->id,
            'ruangan_id' => $this->ruangan->id,
            'layout_ruangan_id' => $this->layout->id,
            'tanggal_kegiatan' => $targetDate,
            'waktu_mulai' => '13:00',
            'waktu_selesai' => '15:00',
            'judul_kegiatan' => 'Rapat Siang Menunggu Persetujuan',
            'pic_kegiatan' => 'PIC Siang',
            'jenis_pic' => 'Organik',
            'no_wa_pic' => '081234567890',
            'jumlah_tamu' => 10,
            'status' => PemesananStatus::PENDING->value,
        ]);

        $response = $this->actingAs($this->admin)->post(route('admin.approval.approve', $pemesanan), [
            'catatan_admin' => 'Disetujui, harap jaga kebersihan ruangan.',
        ]);

        $response->assertRedirect(route('admin.approval.index'));

        $pemesanan->refresh();
        $this->assertEquals(PemesananStatus::DISETUJUI, $pemesanan->status);
        $this->assertEquals($this->admin->id, $pemesanan->approved_by);
        $this->assertNotNull($pemesanan->approved_at);
        $this->assertEquals('Disetujui, harap jaga kebersihan ruangan.', $pemesanan->catatan_admin);
    }

    public function test_admin_can_reject_pemesanan_and_audit_fields_are_saved(): void
    {
        $targetDate = Carbon::tomorrow()->format('Y-m-d');

        $pemesanan = Pemesanan::create([
            'kode_pemesanan' => 'PMS-TEST-003',
            'user_id' => $this->user->id,
            'ruangan_id' => $this->ruangan->id,
            'layout_ruangan_id' => $this->layout->id,
            'tanggal_kegiatan' => $targetDate,
            'waktu_mulai' => '15:00',
            'waktu_selesai' => '17:00',
            'judul_kegiatan' => 'Rapat Sore Akan Ditolak',
            'pic_kegiatan' => 'PIC Sore',
            'jenis_pic' => 'Organik',
            'no_wa_pic' => '081234567890',
            'jumlah_tamu' => 10,
            'status' => PemesananStatus::PENDING->value,
        ]);

        $response = $this->actingAs($this->admin)->post(route('admin.approval.reject', $pemesanan), [
            'alasan_penolakan' => 'Ruangan akan digunakan untuk agenda mendadak Pimpinan.',
        ]);

        $response->assertRedirect(route('admin.approval.index'));

        $pemesanan->refresh();
        $this->assertEquals(PemesananStatus::DITOLAK, $pemesanan->status);
        $this->assertEquals($this->admin->id, $pemesanan->rejected_by);
        $this->assertNotNull($pemesanan->rejected_at);
        $this->assertEquals('Ruangan akan digunakan untuk agenda mendadak Pimpinan.', $pemesanan->alasan_penolakan);
        $this->assertEquals($this->admin->id, $pemesanan->rejecter->id);
    }

    public function test_user_can_cancel_pending_pemesanan_and_audit_fields_are_saved(): void
    {
        $targetDate = Carbon::tomorrow()->format('Y-m-d');

        $pemesanan = Pemesanan::create([
            'kode_pemesanan' => 'PMS-TEST-004',
            'user_id' => $this->user->id,
            'ruangan_id' => $this->ruangan->id,
            'layout_ruangan_id' => $this->layout->id,
            'tanggal_kegiatan' => $targetDate,
            'waktu_mulai' => '08:00',
            'waktu_selesai' => '09:00',
            'judul_kegiatan' => 'Rapat Pagi Dibatalkan User',
            'pic_kegiatan' => 'PIC Pagi',
            'jenis_pic' => 'Organik',
            'no_wa_pic' => '081234567890',
            'jumlah_tamu' => 5,
            'status' => PemesananStatus::PENDING->value,
        ]);

        $response = $this->actingAs($this->user)->post(route('pemesanan.cancel', $pemesanan));

        $response->assertRedirect();

        $pemesanan->refresh();
        $this->assertEquals(PemesananStatus::CANCEL, $pemesanan->status);
        $this->assertEquals($this->user->id, $pemesanan->cancelled_by);
        $this->assertNotNull($pemesanan->cancelled_at);
        $this->assertEquals($this->user->id, $pemesanan->canceller->id);
    }

    public function test_room_with_bookings_cannot_be_deleted(): void
    {
        Pemesanan::create([
            'kode_pemesanan' => 'PMS-TEST-005',
            'user_id' => $this->user->id,
            'ruangan_id' => $this->ruangan->id,
            'layout_ruangan_id' => $this->layout->id,
            'tanggal_kegiatan' => Carbon::tomorrow()->format('Y-m-d'),
            'waktu_mulai' => '10:00',
            'waktu_selesai' => '11:00',
            'judul_kegiatan' => 'Pemesanan Penjaga Integritas Ruangan',
            'pic_kegiatan' => 'PIC Test',
            'jenis_pic' => 'Organik',
            'no_wa_pic' => '081234567890',
            'jumlah_tamu' => 10,
            'status' => PemesananStatus::PENDING->value,
        ]);

        $response = $this->actingAs($this->admin)->delete(route('admin.ruangan.destroy', $this->ruangan));

        $response->assertSessionHas('error');
        $this->assertDatabaseHas('ruangan', [
            'id' => $this->ruangan->id,
        ]);
    }

    public function test_user_with_bookings_cannot_be_deleted(): void
    {
        Pemesanan::create([
            'kode_pemesanan' => 'PMS-TEST-006',
            'user_id' => $this->user->id,
            'ruangan_id' => $this->ruangan->id,
            'layout_ruangan_id' => $this->layout->id,
            'tanggal_kegiatan' => Carbon::tomorrow()->format('Y-m-d'),
            'waktu_mulai' => '11:00',
            'waktu_selesai' => '12:00',
            'judul_kegiatan' => 'Pemesanan Penjaga Integritas User',
            'pic_kegiatan' => 'PIC Test',
            'jenis_pic' => 'Organik',
            'no_wa_pic' => '081234567890',
            'jumlah_tamu' => 10,
            'status' => PemesananStatus::PENDING->value,
        ]);

        $response = $this->actingAs($this->admin)->delete(route('admin.users.destroy', $this->user));

        $response->assertSessionHas('error');
        $this->assertDatabaseHas('users', [
            'id' => $this->user->id,
        ]);
    }

    public function test_calendar_events_api_returns_success(): void
    {
        $response = $this->actingAs($this->user)->getJson(route('kalender.events'));

        $response->assertStatus(200);
        $response->assertJsonStructure([
            '*' => ['id', 'title', 'start', 'end'],
        ]);
    }

    public function test_display_data_api_returns_success(): void
    {
        $response = $this->getJson(route('api.display-data'));

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'status',
            'timestamp',
            'live_count',
            'live',
            'today',
        ]);
    }

    public function test_upcoming_scope_excludes_finished_events(): void
    {
        $today = Carbon::today()->format('Y-m-d');

        // 1. Kegiatan sudah selesai hari ini (misal jam 08:00 - 09:00, atau waktu selesai di masa lalu)
        $finished = Pemesanan::create([
            'kode_pemesanan' => 'PMS-FINISHED',
            'user_id' => $this->user->id,
            'ruangan_id' => $this->ruangan->id,
            'layout_ruangan_id' => $this->layout->id,
            'tanggal_kegiatan' => $today,
            'waktu_mulai' => '07:00',
            'waktu_selesai' => '08:00',
            'judul_kegiatan' => 'Kegiatan Sudah Selesai',
            'pic_kegiatan' => 'PIC Finished',
            'jenis_pic' => 'Organik',
            'no_wa_pic' => '081234567890',
            'jumlah_tamu' => 10,
            'status' => PemesananStatus::DISETUJUI->value,
            'approved_by' => $this->admin->id,
            'approved_at' => now(),
        ]);

        // 2. Kegiatan mendatang di masa depan (misal besok)
        $future = Pemesanan::create([
            'kode_pemesanan' => 'PMS-FUTURE',
            'user_id' => $this->user->id,
            'ruangan_id' => $this->ruangan->id,
            'layout_ruangan_id' => $this->layout->id,
            'tanggal_kegiatan' => Carbon::tomorrow()->format('Y-m-d'),
            'waktu_mulai' => '14:00',
            'waktu_selesai' => '16:00',
            'judul_kegiatan' => 'Kegiatan Mendatang Besok',
            'pic_kegiatan' => 'PIC Future',
            'jenis_pic' => 'Organik',
            'no_wa_pic' => '081234567890',
            'jumlah_tamu' => 10,
            'status' => PemesananStatus::DISETUJUI->value,
            'approved_by' => $this->admin->id,
            'approved_at' => now(),
        ]);

        $upcoming = Pemesanan::approved()->upcoming()->get();

        $this->assertFalse($upcoming->contains('id', $finished->id), 'Agenda yang sudah selesai seharusnya TIDAK muncul di kegiatan mendatang.');
        $this->assertTrue($upcoming->contains('id', $future->id), 'Agenda masa depan seharusnya tetap muncul di kegiatan mendatang.');
    }

    public function test_selesai_awal_fails_if_meeting_already_finished(): void
    {
        $today = Carbon::today()->format('Y-m-d');

        $finishedMeeting = Pemesanan::create([
            'kode_pemesanan' => 'PMS-PAST',
            'user_id' => $this->user->id,
            'ruangan_id' => $this->ruangan->id,
            'layout_ruangan_id' => $this->layout->id,
            'tanggal_kegiatan' => $today,
            'waktu_mulai' => '07:00:00',
            'waktu_selesai' => '08:00:00',
            'judul_kegiatan' => 'Kegiatan Jam 8 Selesai',
            'pic_kegiatan' => 'PIC Past',
            'jenis_pic' => 'Organik',
            'no_wa_pic' => '081234567890',
            'jumlah_tamu' => 10,
            'status' => PemesananStatus::DISETUJUI->value,
            'approved_by' => $this->admin->id,
            'approved_at' => now(),
        ]);

        $this->assertFalse($finishedMeeting->canBeFinishedEarly());

        $response = $this->actingAs($this->admin)->post(route('admin.approval.selesai-awal', $finishedMeeting));

        $response->assertSessionHas('error');
        $finishedMeeting->refresh();
        $this->assertEquals('08:00:00', $finishedMeeting->waktu_selesai);
    }

    public function test_selesai_awal_succeeds_if_meeting_is_currently_live(): void
    {
        $today = Carbon::today()->format('Y-m-d');
        $nowMakassar = Carbon::now('Asia/Makassar');

        $liveMeeting = Pemesanan::create([
            'kode_pemesanan' => 'PMS-LIVE',
            'user_id' => $this->user->id,
            'ruangan_id' => $this->ruangan->id,
            'layout_ruangan_id' => $this->layout->id,
            'tanggal_kegiatan' => $today,
            'waktu_mulai' => $nowMakassar->copy()->subHours(1)->format('H:i:s'),
            'waktu_selesai' => $nowMakassar->copy()->addHours(2)->format('H:i:s'),
            'judul_kegiatan' => 'Kegiatan Sedang Berlangsung',
            'pic_kegiatan' => 'PIC Live',
            'jenis_pic' => 'Organik',
            'no_wa_pic' => '081234567890',
            'jumlah_tamu' => 10,
            'status' => PemesananStatus::DISETUJUI->value,
            'approved_by' => $this->admin->id,
            'approved_at' => now(),
        ]);

        $this->assertTrue($liveMeeting->canBeFinishedEarly());

        $response = $this->actingAs($this->admin)->post(route('admin.approval.selesai-awal', $liveMeeting));

        $response->assertSessionHas('success');
        $liveMeeting->refresh();
        $this->assertNotEquals($nowMakassar->copy()->addHours(2)->format('H:i:s'), $liveMeeting->waktu_selesai);
        $this->assertEquals(PemesananStatus::SELESAI, $liveMeeting->status);
    }

    public function test_mark_finished_agendas_updates_completed_meetings_to_selesai(): void
    {
        $pastMeeting = Pemesanan::create([
            'kode_pemesanan' => 'PMS-AUTO-FINISH',
            'user_id' => $this->user->id,
            'ruangan_id' => $this->ruangan->id,
            'layout_ruangan_id' => $this->layout->id,
            'tanggal_kegiatan' => Carbon::yesterday()->format('Y-m-d'),
            'waktu_mulai' => '09:00:00',
            'waktu_selesai' => '11:00:00',
            'judul_kegiatan' => 'Kegiatan Kemarin Sudah Selesai',
            'pic_kegiatan' => 'PIC Kemarin',
            'jenis_pic' => 'Organik',
            'no_wa_pic' => '081234567890',
            'jumlah_tamu' => 10,
            'status' => PemesananStatus::DISETUJUI->value,
            'approved_by' => $this->admin->id,
            'approved_at' => now(),
        ]);

        $this->assertTrue($pastMeeting->is_finished);

        // Kunjungi halaman approval index (yang memanggil markFinishedAgendas)
        $response = $this->actingAs($this->admin)->get(route('admin.approval.index', ['tab' => 'selesai']));

        $response->assertStatus(200);
        $pastMeeting->refresh();
        $this->assertEquals(PemesananStatus::SELESAI, $pastMeeting->status);
    }

    public function test_admin_can_access_create_meeting_page(): void
    {
        $response = $this->actingAs($this->admin)->get(route('admin.approval.create'));

        $response->assertStatus(200);
        $response->assertSee('Tambah Rapat (Admin)');
        $response->assertSee($this->ruangan->nama_ruangan);
    }

    public function test_admin_can_directly_create_approved_meeting(): void
    {
        $response = $this->actingAs($this->admin)->post(route('admin.approval.store'), [
            'ruangan_id' => $this->ruangan->id,
            'layout_ruangan_id' => $this->layout->id,
            'tanggal_kegiatan' => Carbon::tomorrow()->format('Y-m-d'),
            'waktu_mulai' => '13:00',
            'waktu_selesai' => '15:00',
            'judul_kegiatan' => 'Rapat Pimpinan Diselenggarakan Admin',
            'pic_kegiatan' => 'PIC Admin',
            'jenis_pic' => 'Organik',
            'no_wa_pic' => '081299887766',
            'jumlah_tamu' => 15,
        ]);

        $response->assertSessionHas('success');
        $response->assertRedirect(route('admin.approval.index', ['tab' => 'disetujui']));

        $this->assertDatabaseHas('pemesanan', [
            'judul_kegiatan' => 'Rapat Pimpinan Diselenggarakan Admin',
            'status' => PemesananStatus::DISETUJUI->value,
            'approved_by' => $this->admin->id,
        ]);
    }

    public function test_user_cannot_submit_pemesanan_with_past_date(): void
    {
        $yesterday = Carbon::yesterday()->format('Y-m-d');
        $file = UploadedFile::fake()->create('disposisi.pdf', 100, 'application/pdf');

        $response = $this->actingAs($this->user)->post(route('pemesanan.store'), [
            'ruangan_id' => $this->ruangan->id,
            'layout_ruangan_id' => $this->layout->id,
            'tanggal_kegiatan' => $yesterday,
            'waktu_mulai' => '09:00',
            'waktu_selesai' => '11:00',
            'judul_kegiatan' => 'Kegiatan Masa Lalu',
            'pic_kegiatan' => 'John Doe',
            'jenis_pic' => 'Organik',
            'no_wa_pic' => '081234567890',
            'jumlah_tamu' => 10,
            'file_disposisi' => $file,
        ]);

        $response->assertSessionHasErrors('tanggal_kegiatan');
    }

    public function test_user_cannot_submit_pemesanan_with_past_time_today(): void
    {
        // Set waktu ke jam 14:00 WITA
        Carbon::setTestNow(Carbon::today('Asia/Makassar')->setTime(14, 0));

        $today = Carbon::today('Asia/Makassar')->format('Y-m-d');
        $file = UploadedFile::fake()->create('disposisi.pdf', 100, 'application/pdf');

        $response = $this->actingAs($this->user)->post(route('pemesanan.store'), [
            'ruangan_id' => $this->ruangan->id,
            'layout_ruangan_id' => $this->layout->id,
            'tanggal_kegiatan' => $today,
            'waktu_mulai' => '10:00', // Jam 10:00 padahal sekarang jam 14:00
            'waktu_selesai' => '12:00',
            'judul_kegiatan' => 'Kegiatan Jam Lampau',
            'pic_kegiatan' => 'John Doe',
            'jenis_pic' => 'Organik',
            'no_wa_pic' => '081234567890',
            'jumlah_tamu' => 10,
            'file_disposisi' => $file,
        ]);

        $response->assertSessionHasErrors('waktu_mulai');

        Carbon::setTestNow(); // Reset test time
    }

    public function test_admin_cannot_submit_pemesanan_with_past_time_today(): void
    {
        Carbon::setTestNow(Carbon::today('Asia/Makassar')->setTime(15, 0));

        $today = Carbon::today('Asia/Makassar')->format('Y-m-d');

        $response = $this->actingAs($this->admin)->post(route('admin.approval.store'), [
            'ruangan_id' => $this->ruangan->id,
            'layout_ruangan_id' => $this->layout->id,
            'tanggal_kegiatan' => $today,
            'waktu_mulai' => '11:00', // 11:00 lampau dari 15:00
            'waktu_selesai' => '13:00',
            'judul_kegiatan' => 'Rapat Admin Waktu Lampau',
            'pic_kegiatan' => 'Admin PIC',
            'jenis_pic' => 'Organik',
            'no_wa_pic' => '081234567890',
            'jumlah_tamu' => 10,
        ]);

        $response->assertSessionHas('error');

        Carbon::setTestNow();
    }
}
