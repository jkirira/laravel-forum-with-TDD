<?php

namespace Tests\Feature;

use App\Models\Thread;
use App\Models\Reply;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class ReadThreadsTest extends TestCase
{
    use DatabaseMigrations;
    /**
     * A basic test example.
     *
     * @return void
     */

    public function setUp(): void{
        parent::setUp();

        $this->thread = Thread::factory()->create();
    }

    public function test_a_user_can_view_all_threads()
    {
        $response = $this->get('/threads');
        $response->assertSee($this->thread->title);
    }

    public function test_a_user_can_view_a_single_threads()
    {
        $response = $this->get('/threads/'. $this->thread->id);
        $response->assertSee($this->thread->title);
    }

    public function test_a_user_can_read_replies_that_are_associated_with_a_thread()
    {
        $reply = Reply::factory()
            ->create(['thread_id' => $this->thread->id]);

        $response = $this->get('/threads/'. $this->thread->id)
            ->assertSee($reply->body);

    }
}
