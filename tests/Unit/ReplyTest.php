<?php

namespace Tests\Feature;

use App\Models\Reply;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ReplyTest extends TestCase
{
    use DatabaseMigrations;

    function test_it_has_an_owner(){
        $reply = Reply::factory()->create();

        $this->assertInstanceOf(User::class, $reply->owner);
    }
}
