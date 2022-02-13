<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CreateThreadsTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    function guests_may_not_create_threads()
    {
        $this->withExceptionHandling();

        $this->get('threads/create')
            ->assertRedirect('/login');

        $this->post('/threads')
            ->assertRedirect('/login');

    }

//      Two functions combined into the one on top

//    /** @test */
//    function guests_may_not_create_threads()
//    {
//        $this->expectException('Illuminate\Auth\AuthenticationException');
//
//        $thread = make('App\Thread');
//
//        $this->post('/threads', $thread->toArray());
//    }
//
//    /** @test  */
//    function guests_cannot_see_the_create_thread_page()
//    {
//        $this->withExceptionHandling()
//            ->get('threads/create')
//            ->assertRedirect('/login');
//    }

    /** @test */
    function an_authenticated_user_can_create_forum_threads()
    {
        $this->signIn();

        $thread = make('App\Thread');

        $response = $this->post('/threads', $thread->toArray());

        $this->get( $response->headers->get('Location'))
                ->assertSee($thread->title)
                ->assertSee($thread->body);
    }

    /** @test */
    function a_thread_requires_a_title()
    {
        $this->publishThread(['title' => null])
            ->assertSessionHasErrors('title');

//        $this->withExceptionHandling()->signIn();
//        $thread = make('App\thread', ['title' => null]);
//        $this->post('/threads', $thread->toArray())
//            ->assertSessionHasErrors('title');
    }

    /** @test */
    function a_thread_requires_a_body()
    {
        $this->publishThread(['body' => null])
            ->assertSessionHasErrors('body');

    }

    /** @test */
    function a_thread_requires_a_valid_channel()
    {
        factory('App\Channel', 2)->create();

        $this->publishThread(['channel_id' => null])
            ->assertSessionHasErrors('channel_id');

        $this->publishThread(['channel_id' => 999])
            ->assertSessionHasErrors('channel_id');
    }

    /** @test  */
    function guests_cannot_delete_threads()
    {
        $this->withExceptionHandling();

        $thread = create('App\Thread');

        $response = $this->delete($thread->path());

        $response->assertRedirect('/login');
    }

    /** @test  */
    public function threads_may_only_be_deleted_by_those_who_have_permission()
    {
        //  TODO;
    }


    /** @test  */
    function a_thread_can_be_deleted()
    {
        $this->signIn();

        $thread = create('App\Thread');

        $reply = create('App\Reply', ['thread_id' => $thread->id]);

        $response = $this->json('DELETE', $thread->path());

        $response->assertStatus(204);

        $this->assertDatabaseMissing('threads', ['id' => $thread->id]);
        $this->assertDatabaseMissing('replies', ['id' => $reply->id]);
    }


    function publishThread($overrides=null)
    {
        $this->withExceptionHandling()->signIn();

        $thread = make('App\Thread', $overrides ?: []);

        return $this->post('/threads', $thread->toArray());
    }

}
