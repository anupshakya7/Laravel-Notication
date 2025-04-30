<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;
    public function test_login_redirects_to_products(){
        $user = User::create([
            'name'=>'User',
            'email'=>'user@user.com',
            'password'=>bcrypt('password'),
        ]);

        $response = $this->post('/login',[
            'email'=>'user@user.com',
            'password'=>'password',
        ]);
        
        $response->assertStatus(302);
        $response->assertRedirect('product');
    }

   public function test_unauthenticated_user_cannot_access_product(){
    $response = $this->get('/product');

    $response->assertStatus(302);
    $response->assertRedirect('login');
   }
}
