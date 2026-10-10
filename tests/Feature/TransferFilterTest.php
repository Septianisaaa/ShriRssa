<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Database\Seeders\RoomSeeder;
use Database\Seeders\SampleDataSeeder;
use App\Models\User;
use App\Models\Room;

class TransferFilterTest extends TestCase
{
    use RefreshDatabase;

    public function test_transfers_page_has_separate_origin_and_destination_filters_with_installation(): void
    {
        $this->seed([RoomSeeder::class, SampleDataSeeder::class]);
        $user = User::where('role', 'superadmin')->first();

        $response = $this->actingAs($user)->get('/shri/transfers');
        $response->assertStatus(200);

        // Check filter elements
        $response->assertSee('id="filterRuangAsal"', false);
        $response->assertSee('id="filterRuangTujuan"', false);
        $response->assertSee('Semua ruang asal');
        $response->assertSee('Semua ruang tujuan');

        // Check room formatting with installation
        $firstRoom = Room::where('is_active', true)->orderBy('name')->first();
        if ($firstRoom) {
            $expectedOption = "{$firstRoom->name} ({$firstRoom->installation})";
            $response->assertSee($expectedOption);
        }
    }

    public function test_audit_trail_page_does_not_have_active_module_card(): void
    {
        $this->seed([RoomSeeder::class, SampleDataSeeder::class]);
        $user = User::where('role', 'superadmin')->first();

        $response = $this->actingAs($user)->get('/shri/audit-trail');
        $response->assertStatus(200);

        $response->assertDontSee('Modul Teraktif');
        $response->assertDontSee('modul yang sering diakses');
    }
}
