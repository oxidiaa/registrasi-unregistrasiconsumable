<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ExampleTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test guest is redirected to login page.
     */
    public function test_guest_is_redirected_to_login(): void
    {
        $response = $this->get('/');

        $response->assertRedirect(route('login'));
    }

    /**
     * Test authenticated user can view dashboard.
     */
    public function test_authenticated_user_can_view_dashboard(): void
    {
        $user = User::create([
            'name'       => 'Test User',
            'email'      => 'test_user@example.com',
            'department' => 'Production',
            'role'       => 'User',
            'status'     => 'Aktif',
            'password'   => bcrypt('password'),
        ]);

        $response = $this->actingAs($user)->get('/');

        $response->assertStatus(200);
    }
}
