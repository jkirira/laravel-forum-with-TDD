<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;

class SubscribeToThreadsTest extends TestCase
{
    use DatabaseMigrations;

   /** @test  */

    public function a_user_can_subscribe_to_threads()
    {
        $thread = create('App\Thread');

        $this->signIn();

        $this->post($thread->path().'/subscriptions');

        $thread->addReply([
            'user_id' => auth()->id(),
            'body' => 'Some Reply Here'
        ]);
    }

 }