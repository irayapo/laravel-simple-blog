<?php
namespace Tests\Feature;

use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PostTest extends TestCase
{
use RefreshDatabase;

public function test_guest_user_cannot_create_post()
{
$response = $this->get('/posts/create');
$response->assertRedirect('/login');
}

public function test_authenticated_user_can_create_post()
{
$user = User::factory()->create();
$this->actingAs($user);

$response = $this->get('/posts/create');
$response->assertStatus(200);
}

public function test_post_title_length_validation()
{
$user = User::factory()->create();
$this->actingAs($user);

$response = $this->post('/posts', [
'title' => str_repeat('A', 61),
'content' => 'Test content',
'status' => 'draft',
]);

$response->assertSessionHasErrors('title');
}
}
