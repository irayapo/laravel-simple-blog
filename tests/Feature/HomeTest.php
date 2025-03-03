<?php
namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User; // ✅ Tambahkan ini

class HomeTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_user_sees_login_and_register_links()
    {
        $response = $this->get('/');
        $response->assertStatus(200);
        $response->assertSee('Login');
        $response->assertSee('Register');
    }

    public function test_authenticated_user_sees_their_posts()
    {
        $user = User::factory()->create(); // ✅ Laravel butuh User Model di-import
        $this->actingAs($user);

        $response = $this->get('/');
        $response->assertStatus(200);
        $response->assertSee('Your Posts');
    }
}
