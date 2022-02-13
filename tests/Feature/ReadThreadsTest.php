<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ReadThreadsTest extends TestCase
{
    Use DatabaseMigrations;

    protected $thread;

    public function setUp(){
        parent::setup();
        $this->thread = create('App\Thread');
    }

    /** @test  */
     public function a_user_can_browse_threads()
    {

        $response = $this->get('/threads');
        $response->assertSee($this->thread->title);

    }

    /** @test */
    function a_user_can_read_a_single_thread()
    {
        $response = $this->get('/threads/'.$this->thread->channel->slug.'/'.$this->thread->id);
        $response->assertSee($this->thread->title);
    }

    /** @test */
    function a_user_can_read_replies_that_are_associated_with_a_thread()
    {
        $reply = create('App\Reply', ['thread_id'=> $this->thread->id]);

        $this->get('/threads/'.$this->thread->channel->slug.'/'.$this->thread->id)
            ->assertSee($reply->body);
    }

    /** @test */
    function a_user_can_filter_threads_according_to_a_channel()
    {
        $channel = create('App\Channel');
        $threadInChannel = create('App\Thread', ['channel_id' => $channel->id]);
        $threadNotInChannel = create('App\Thread');


        $this->get('/threads/'.$channel->slug)
            ->assertSee($threadInChannel->title)
            ->assertDontSee($threadNotInChannel->title);
    }


    /** @test */
    function a_user_can_filter_threads_by_any_username()
    {
        $this->signIn(create('App\User', ['name' => 'JohnDoe']));

        $threadByJohn = create('App\Thread', ['user_id' => auth()->id()]);
        $threadNotByJohn = create('App\Thread');

        $this->get('threads?by=JohnDoe')
            ->assertSee($threadByJohn->title)
            ->assertDontSee($threadNotByJohn->title);
    }
}
