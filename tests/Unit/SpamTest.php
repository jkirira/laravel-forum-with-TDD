<?php

namespace Tests\Unit;

use App\Inspections\Spam;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;

class SpamTest extends TestCase
{
    use DatabaseMigrations;

   /** @test  */

    public function it_checks_for_invalid_keywords()
    {
        $spam = new Spam();

        $this->assertFalse($spam->detect('Innocent reply Here'));

        $this->expectException('Exception');

        $spam->detect('yahoo customer support');
    }

    function it_checks_for_any_key_being_held_down()
    {
        $spam = new Spam();

        $this->expectException('Exception');

        $spam->detect('Hello world aaaaaaaaaa');
    }

 }