<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Notifications\DatabaseNotification;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class NotificationsTest extends TestCase
{
    use DatabaseMigrations;

    public function setUp()
    {
        parent::setUp();

        $this->signIn();
    }

    /**
     * A basic test example.
     *
     * @return void
     */
    public function a_notification_is_prepared_when_a_subscribed_thread_receives_a_reply_that_is_not_by_the_current_user()
    {

        $thread = create('App\Thread')->subscribe();

        $this->assertCount(0, auth()->user()->notifications);

        $thread->addReply([
            'user_id' => auth()->id(),
            'body' => 'Some Reply Here'
        ]);

        $this->assertCount(0, auth()->user()->fresh()->notifications);

        $thread->addReply([
            'user_id' => create('App\User')->id,
            'body' => 'Some Reply Here'
        ]);

        $this->assertCount(1, auth()->user()->fresh()->notifications);
    }

    /** @test */
    function a_user_can_fetch_their_unread_notifications()
    {
        create(DatabaseNotification::class);

        $response = $this->getJson("/profiles/" . auth()->user()->name . "/notifications")->json();

        $this->assertCount(1, $response);
    }

    /** @test */
    function a_user_can_mark_a_notification_as_read()
    {

        create(DatabaseNotification::class);

        $user = auth()->user();

        $this->assertCount(1, $user->unreadNotifications);

        $notificationId = $user->unreadNotifications->first()->id;

        $this->delete("/profiles/{$user->name}/notifications/{$notificationId}");

        $this->assertCount(0, $user->fresh()->unreadNotifications);

    }

}
