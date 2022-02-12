<?php

namespace Tests\Unit;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ThreadTest extends TestCase
{
    use DatabaseMigrations;

    protected $thread;

    public function setUp(){
        parent::setup();
        $this->thread = factory('App\Thread')->create();
    }

    /** @test */
    function a_thread_has_replies()
    {
        $this->assertInstanceOF('Illuminate\Database\Eloquent\Collection', $this->thread->replies);
    }

    function a_thread_has_a_creator()
    {
        $this->assertInstanceOf('App\User', $this->thread->creator());
    }

    function a_thread_can_add_a_reply(){
        $this->thread->addReply([
             'body' => 'Foobar',
                'user_id' => 1,
        ]);
        $this->assertCount($this->thread->replies);
    }
}
