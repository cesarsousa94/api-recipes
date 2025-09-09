<?php

namespace Tests\Feature;

use Tests\TestCase;

class AuthTest extends TestCase
{
    public function test_registers_and_logins(): void
    {
        $email = 'mike'.uniqid().'@ex.com';

        $this->postJson('/api/auth/register', [
            'name'=>'Mike', 'email'=>$email, 'password'=>'secret123'
        ])->assertCreated();

        $res = $this->postJson('/api/auth/login', [
            'email'=>$email, 'password' =>'secret123'
        ])->assertOk()->json();

        $this->assertArrayHasKey('token', $res);

        $this->assertArrayHasKey('user', $res);
    }
}
