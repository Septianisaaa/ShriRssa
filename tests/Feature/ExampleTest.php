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

    public function test_admin_ruang_can_access_dashboard(): void
    {
        $this->seed([RoomSeeder::class, SampleDataSeeder::class]);
        $user = User::where('role', 'admin')->first();

        $response = $this->actingAs($user)->get('/admin-ruang/dashboard');
        $response->assertStatus(200);
    }
}

