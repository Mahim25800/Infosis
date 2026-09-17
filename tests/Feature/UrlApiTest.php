<?php

namespace Tests\Feature;

use App\Models\Url;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class UrlApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_an_authenticated_user_can_shorten_a_url(): void
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $response = $this->postJson('/api/urls', [
            'url' => 'https://example.com/a-very-long-page',
        ]);

        $response
            ->assertCreated()
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.original_url', 'https://example.com/a-very-long-page')
            ->assertJsonPath('data.click_count', 0);

        $this->assertDatabaseHas('urls', [
            'user_id' => $user->id,
            'original_url' => 'https://example.com/a-very-long-page',
        ]);
    }

    public function test_a_user_cannot_read_someone_elses_url(): void
    {
        $owner = User::factory()->create();
        $url = Url::factory()->create(['user_id' => $owner->id]);
        Sanctum::actingAs(User::factory()->create());

        $this->getJson("/api/urls/{$url->id}")
            ->assertForbidden()
            ->assertJsonPath('success', false);
    }

    public function test_visiting_a_short_url_redirects_and_increases_its_count(): void
    {
        $url = Url::factory()->create([
            'original_url' => 'https://example.com/landing-page',
            'short_code' => 'visit-me',
            'click_count' => 0,
        ]);

        $this->get('/visit-me')
            ->assertRedirect('https://example.com/landing-page');

        $this->assertDatabaseHas('urls', [
            'id' => $url->id,
            'click_count' => 1,
        ]);
    }
}
