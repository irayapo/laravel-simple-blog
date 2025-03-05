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

    public function test_guest_cannot_view_draft_or_scheduled_posts()
    {
        $user = User::factory()->create(); // Buat user terlebih dahulu
        $draftPost = Post::factory()->create(['status' => 'draft', 'user_id' => $user->id]);
        $scheduledPost = Post::factory()->create(['status' => 'scheduled', 'published_at' => now()->addDay(), 'user_id' => $user->id]);
    
        $this->get(route('posts.show', $draftPost))->assertStatus(404);
        $this->get(route('posts.show', $scheduledPost))->assertStatus(404);
    }
    
    public function test_user_can_view_own_draft_or_scheduled_posts()
    {
        $user = User::factory()->create();
        $this->actingAs($user);
    
        $draftPost = Post::factory()->create(['status' => 'draft', 'user_id' => $user->id]);
        $scheduledPost = Post::factory()->create(['status' => 'scheduled', 'published_at' => now()->addDay(), 'user_id' => $user->id]);
    
        $this->get(route('posts.show', $draftPost))->assertStatus(200);
        $this->get(route('posts.show', $scheduledPost))->assertStatus(200);
    }
    

    public function test_scheduled_post_is_not_visible_before_published_date()
    {
        $user = User::factory()->create(); // Buat user terlebih dahulu
        $post = Post::factory()->create([
            'status' => 'scheduled',
            'published_at' => now()->addDays(1),
            'user_id' => $user->id, // Pastikan user_id valid
        ]);
    
        $response = $this->get("/posts/{$post->id}");
        $response->assertNotFound();
    }

    public function test_scheduled_post_becomes_visible_after_published_date()
    {
        $post = Post::factory()->create([
            'status' => 'scheduled',
            'published_at' => now()->subMinutes(1), // Sudah melewati waktu terbit
        ]);

        $response = $this->get("/posts/{$post->id}");
        $response->assertStatus(200); // Sekarang harusnya bisa dilihat
    }

    public function test_scheduled_post_is_published_when_published_date_comes()
    {
        $scheduledPost = Post::factory()->create([
            'status' => 'scheduled',
            'published_at' => now()->subMinute(), // Seharusnya sudah dipublish
        ]);

        // Jalankan tugas terjadwal
        $this->artisan('schedule:run');

        // Refresh model untuk mendapatkan data terbaru dari database
        $scheduledPost->refresh();

        $this->assertEquals('published', $scheduledPost->status);
    }

    public function test_user_cannot_update_other_users_post()
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();
        $post = Post::factory()->create(['user_id' => $user1->id]);

        $this->actingAs($user2);
        $response = $this->patch("/posts/{$post->id}", ['title' => 'Updated Title']);

        $response->assertForbidden();
    }

    public function test_user_cannot_delete_other_users_post()
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();
        $post = Post::factory()->create(['user_id' => $user1->id]);

        $this->actingAs($user2);
        $response = $this->delete("/posts/{$post->id}");

        $response->assertForbidden();
    }
}
