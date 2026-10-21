<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use App\Models\AuditTrail;
use Database\Seeders\RoomSeeder;
use Database\Seeders\SampleDataSeeder;

class AuditTrailTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_is_redirected_from_audit_trail(): void
    {
        $response = $this->get('/shri/audit-trail');
        $response->assertRedirect('/login');
    }

    public function test_admin_ruang_cannot_access_audit_trail(): void
    {
        $this->seed([RoomSeeder::class, SampleDataSeeder::class]);
        $user = User::where('role', 'admin')->first();

        $response = $this->actingAs($user)->get('/shri/audit-trail');
        $response->assertStatus(403);
    }

    public function test_superadmin_can_access_audit_trail_and_see_records(): void
    {
        $this->seed([RoomSeeder::class, SampleDataSeeder::class]);
        $superadmin = User::where('role', 'superadmin')->first();

        $response = $this->actingAs($superadmin)->get('/shri/audit-trail');
        $response->assertStatus(200);
        $response->assertSee('Audit Trail & Log Aktivitas');
        $response->assertSee('Catatan Aktivitas Pengguna');
        $this->assertGreaterThan(0, $response->viewData('totalActivities'));
    }

    public function test_audit_trail_filtering_by_action_and_module(): void
    {
        $this->seed([RoomSeeder::class, SampleDataSeeder::class]);
        $superadmin = User::where('role', 'superadmin')->first();

        $response = $this->actingAs($superadmin)->get('/shri/audit-trail?action=login');
        $response->assertStatus(200);

        $activities = $response->viewData('activities');
        foreach ($activities as $act) {
            $this->assertEquals('login', $act->action);
        }
    }

    public function test_mutating_action_automatically_records_audit_trail(): void
    {
        $this->seed([RoomSeeder::class, SampleDataSeeder::class]);
        $superadmin = User::where('role', 'superadmin')->first();

        $initialCount = AuditTrail::count();

        // Perform a mutating action: store a new installation
        $response = $this->actingAs($superadmin)->post('/shri/kelola-ruangan/instalasi', [
            'name' => 'Instalasi Diagnostik Baru',
        ]);
        $response->assertSessionHasNoErrors();

        // Verify that audit trail logged this action
        $this->assertEquals($initialCount + 1, AuditTrail::count());

        $latestLog = AuditTrail::latest('id')->first();
        $this->assertEquals('Kelola Ruangan', $latestLog->module);
        $this->assertEquals('create', $latestLog->action);
        $this->assertStringContainsString('Instalasi Diagnostik Baru', $latestLog->description);
    }
}
