<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Database\Seeders\RoomSeeder;
use Database\Seeders\SampleDataSeeder;
use App\Models\User;

class ExampleTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_is_redirected_to_login(): void
    {
        $response = $this->get('/');
        $response->assertRedirect('/login');
    }

    public function test_superadmin_can_access_dashboard(): void
    {
        $this->seed([RoomSeeder::class, SampleDataSeeder::class]);
        $user = User::where('role', 'superadmin')->first();

        $response = $this->actingAs($user)->get('/shri/dashboard');
        $response->assertStatus(200);
    }

    public function test_admin_ruang_can_access_dashboard_and_only_sees_assigned_room(): void
    {
        $this->seed([RoomSeeder::class, SampleDataSeeder::class]);
        $user = User::where('role', 'admin')->first();

        $response = $this->actingAs($user)->get('/admin-ruang/dashboard');
        $response->assertStatus(200);
        
        $rooms = $response->viewData('rooms');
        $this->assertCount(1, $rooms);
        $this->assertEquals($user->room_id, $rooms->first()->id);
        $response->assertDontSee('liveRealtimeClock');
    }

    public function test_superadmin_sees_all_rooms_and_no_clock(): void
    {
        $this->seed([RoomSeeder::class, SampleDataSeeder::class]);
        $user = User::where('role', 'superadmin')->first();

        $response = $this->actingAs($user)->get('/shri/dashboard');
        $response->assertStatus(200);

        $rooms = $response->viewData('rooms');
        $this->assertGreaterThan(1, count($rooms));
        $response->assertDontSee('liveRealtimeClock');
    }

    public function test_password_complexity_validation(): void
    {
        $this->seed([RoomSeeder::class, SampleDataSeeder::class]);

        // Weak password without symbols/numbers should fail
        $response = $this->post('/shri/register', [
            'name' => 'Petugas Baru',
            'username' => 'petugasbaru',
            'email' => 'baru@rssa.go.id',
            'password' => 'simplepass',
            'password_confirmation' => 'simplepass',
        ]);
        $response->assertSessionHasErrors('password');

        // Strong password with min 8 chars, letters, numbers & symbols should pass
        $responseValid = $this->post('/shri/register', [
            'name' => 'Petugas Baru Valid',
            'username' => 'petugasvalid',
            'email' => 'valid@rssa.go.id',
            'password' => 'Petugas123!',
            'password_confirmation' => 'Petugas123!',
        ]);
        $responseValid->assertSessionHasNoErrors();
        $this->assertDatabaseHas('users', ['username' => 'petugasvalid']);
    }
}



