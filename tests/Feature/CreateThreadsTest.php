<?php

namespace Tests\Feature;

use App\Activity;
use App\Thread;
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
            ->assertRedirect(route('login'));

        $this->post(route('threads'))
            ->assertRedirect(route('login'));

    }

//      Two functions combined into the one on top

//    /** @test */
//    function guests_may_not_create_threads()
//    {
//        $this->expectException('Illuminate\Auth\AuthenticationException');
//
//        $thread = make('App\Thread');
//
//        $this->post(route('threads'), $thread->toArray());
//    }
//
//    /** @test  */
//    function guests_cannot_see_the_create_thread_page()
//    {
//        $this->withExceptionHandling()
//            ->get('threads/create')
//            ->assertRedirect(route('login'));
//    }

    /** @test */
    function new_users_must_first_confirm_their_email_address_before_creating_threads()
    {
        $user = factory('App\User')->states('unconfirmed')->create();

        $this->signIn($user);

        $thread = make('App\Thread');

        $this->post(route('threads'), $thread->toArray())
            ->assertRedirect(route('threads'))
            ->assertSessionHas('flash', 'You must first confirm your email address');
    }


    /** @test */
    function a_user_can_create_new_forum_threads()
    {
        $this->signIn();

        $thread = make('App\Thread');

        $response = $this->post(route('threads'), $thread->toArray());

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
//        $this->post(route('threads'), $thread->toArray())
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
    function a_thread_requires_a_unique_slug()
    {
        $this->signIn();

        $thread = create('App\Thread', ['title' => 'Foo Title', 'slug' => 'foo-title']);

        $this->assertEquals($thread->fresh()->slug, 'foo-title');

        $this->post(route('threads'), $thread->toArray());

        $this->assertTrue(Thread::whereSlug('foo-title-2')->exists());

        $this->post(route('threads'), $thread->toArray());

        $this->assertTrue(Thread::whereSlug('foo-title-3')->exists());
    }


    /** @test  */
    function unauthorized_users_may_not_delete_threads()
    {
        $this->withExceptionHandling();

        $thread = create('App\Thread');

        $this->delete($thread->path())
            ->assertRedirect(route('login'));

        $this->signIn();
        $this->delete($thread->path())
            ->assertStatus(403);
    }


    /** @test  */
    function authorized_users_can_delete_a_thread()
    {
        $this->signIn();

        $thread = create('App\Thread', ['user_id' => auth()->id()]);

        $reply = create('App\Reply', ['thread_id' => $thread->id]);

        $response = $this->json('DELETE', $thread->path());

        $response->assertStatus(204);

        $this->assertDatabaseMissing('threads', ['id' => $thread->id]);
        $this->assertDatabaseMissing('replies', ['id' => $reply->id]);
        $this->assertEquals(0, Activity::count());
    }


    function publishThread($overrides=null)
    {
        $this->withExceptionHandling()->signIn();

        $thread = make('App\Thread', $overrides ?: []);

        return $this->post(route('threads'), $thread->toArray());
    }

}
