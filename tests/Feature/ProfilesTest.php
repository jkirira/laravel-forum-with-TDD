<?php
//
//namespace Tests\Feature;
//
//use Illuminate\Foundation\Testing\DatabaseMigrations;
//use Tests\TestCase;
//use Illuminate\Foundation\Testing\WithFaker;
//
//class ProfilesTest extends TestCase
//{
//    use DatabaseMigrations;
//
//   /** @test  */
//    public function user_has_a_profile()
//    {
//        $user = create('App\User');
//
//        $response = $this->get( route('profile', $user->name) )
//            ->assertSee($user->name);
//    }
//
//    /** @test  */
//    public function profiles_display_all_threads_created_by_user()
//    {
//        $this->signIn();
//
//        $thread = create('App\Thread', ['user_id' => auth()->id()]);
//
//        $this->get(route('profile', auth()->user()->name ))
//            ->assertSee($thread->title)
//            ->assertSee($thread->body);
//    }
//
//}