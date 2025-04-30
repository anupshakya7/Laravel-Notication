<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class HomeTest extends TestCase
{
    use RefreshDatabase;

   public function test_homepage_is_loading(){
        $response = $this->get('/');
        
        $response->assertStatus(200);
   }

   public function test_homepage_contains_laravel_text(){
        $response = $this->get('/');

        $response->assertSee('Laravel');
   }

   public function test_user_is_saved_in_database(){
        $user = User::create([
            'name'=>'Testing User',
            'email'=>'test@test.com',
            'password'=>bcrypt('password'),
        ]);

        $this->assertNotNull($user);
        $this->assertEquals('Testing User',$user->name);
   }
}
