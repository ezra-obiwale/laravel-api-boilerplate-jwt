<?php

namespace App\Feature\Api\V1\Controllers;

use DB;
use Config;
use App\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laraquick\Tests\Traits\Common;

class SignUpControllerTest extends TestCase
{
    use Common;

    protected function tearingDown()
    {
        DB::table('users')->delete();
    }

    public function testSignUpSuccessfully()
    {
        $this->post('api/auth/signup', [
            'first_name' => 'Jane',
            'last_name' => 'Doe',
            'email' => 'jane.doe@email.com',
            'password' => 'secret'
        ])->assertJson([
            'status' => 'ok'
        ])->assertStatus(201);
    }

    public function testSignUpSuccessfullyWithTokenRelease()
    {
        Config::set('boilerplate.sign_up.release_token', true);

        $resp = $this->post('api/auth/signup', [
            'first_name' => 'Jane',
            'last_name' => 'Doe',
            'email' => 'jane.doe@email.com',
            'password' => 'secret'
        ]);
        $this->storeResponse($resp, 'auth/signup');
        $resp->assertJsonStructure([
            'status',
            'token',
            'expires_in'
        ])->assertJson([
            'status' => 'ok'
        ])->assertStatus(201);
    }

    public function testSignUpReturnsValidationError()
    {
        $this->post('api/auth/signup', [
            'first_name' => 'Jane',
            'last_name' => 'Doe',
            'email' => 'jane.doe@email.com'
        ])->assertJsonStructure([
            'error'
        ])->assertStatus(422);
    }
}
