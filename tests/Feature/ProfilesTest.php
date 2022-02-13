<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;

class ProfilesTest extends TestCase
{
    use DatabaseMigrations;

   /** @test  */
    public function user_has_a_profile()
    {
        $user = creeate('App\User');

        $this->get("/profiles/{ $user->name }")
            ->assertSee($user->name);
    }

    /** @test  */
    public function profiles_display_all_threads_created_by_user()
    {
        $user = create('App\User');

        $thread = create('App\Thread', ['user_id' => $user->id]);


        $this->get("/profiles/{ $user->name }")
            ->assertSee($thread->title)
            ->assertSee($thread->body);
    }

}