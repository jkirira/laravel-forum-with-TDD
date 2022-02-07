<?php

namespace Tests\Feature;

use App\Models\Thread;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class ThreadsTest extends TestCase
{
    use DatabaseMigrations;
    /**
     * A basic test example.
     *
     * @return void
     */
    public function test_a_user_can_view_all_threads()
    {
        $thread = Thread::factory()->create();

        $response = $this->get('/threads');
        $response->assertSee($thread->title);

    }

    public function test_a_user_can_view_a_single_threads()
    {
        $thread = Thread::factory()->create();

        $response = $this->get('/threads/'. $thread->id);
        $response->assertSee($thread->title);

    }
}
