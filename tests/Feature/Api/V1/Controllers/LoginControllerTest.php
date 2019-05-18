<?php

namespace App\Feature\Api\V1\Controllers;

use Hash;
use App\Entities\V1\User;
use App\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laraquick\Tests\Traits\Common;

class LoginControllerTest extends TestCase
{
    use Common;

    protected function setUpOnce()
    {
        $this->user();
    }

    public function testLoginSuccessfully()
    {
        $resp = $this->post('api/auth/login', [
            'email' => 'jdoe@email.com',
            'password' => 'secret'
        ]);
        $this->storeResponse($resp, 'auth/login');
        $resp->assertJson([
            'status' => 'ok'
        ])->assertJsonStructure([
            'status',
            'token',
            'expires_in'
        ])->isOk();
    }

    public function testLoginWithReturnsWrongCredentialsError()
    {
        $this->post('api/auth/login', [
            'email' => 'unknown@email.com',
            'password' => 'secret'
        ])->assertJsonStructure([
            'error'
        ])->assertStatus(403);
    }

    public function testLoginWithReturnsValidationError()
    {
        $this->post('api/auth/login', [
            'email' => 'jdoe@email.com'
        ])->assertJsonStructure([
            'error'
        ])->assertStatus(422);
    }
}
