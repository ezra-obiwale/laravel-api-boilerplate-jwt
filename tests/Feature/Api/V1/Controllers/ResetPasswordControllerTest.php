<?php

namespace App\Feature\Api\V1\Controllers;

use DB;
use Config;
use App\TestCase;
use Carbon\Carbon;
use Laraquick\Tests\Traits\Common;

class ResetPasswordControllerTest extends TestCase
{
    use Common;

    protected function setUpOnce()
    {
        $this->user();
    }

    protected function setUpAlways()
    {
        DB::table('password_resets')->insert([
            'email' => 'jdoe@email.com',
            'token' => bcrypt('my_super_secret_code'),
            'created_at' => Carbon::now()
        ]);
    }

    public function testResetSuccessfully()
    {
        $this->post('api/auth/reset', [
            'email' => 'jdoe@email.com',
            'token' => 'my_super_secret_code',
            'password' => 'mynewpass',
            'password_confirmation' => 'mynewpass'
        ])->assertJson([
            'status' => 'ok'
        ])->isOk();
    }

    public function testResetSuccessfullyWithTokenRelease()
    {
        Config::set('boilerplate.reset_password.release_token', true);

        $resp = $this->post('api/auth/reset', [
            'email' => 'jdoe@email.com',
            'token' => 'my_super_secret_code',
            'password' => 'mynewpass',
            'password_confirmation' => 'mynewpass'
        ]);
        $this->storeResponse($resp, 'auth/reset');
        $resp->assertJsonStructure([
            'status',
            'token',
            'expires_in'
        ])->assertJson([
            'status' => 'ok'
        ])->isOk();
    }

    public function testResetReturnsProcessError()
    {
        $this->post('api/auth/reset', [
            'email' => 'unknown@email.com',
            'token' => 'this_code_is_invalid',
            'password' => 'mynewpass',
            'password_confirmation' => 'mynewpass'
        ])->assertJsonStructure([
            'error'
        ])->assertStatus(500);
    }

    public function testResetReturnsValidationError()
    {
        $this->post('api/auth/reset', [
            'email' => 'jdoe@email.com',
            'token' => 'my_super_secret_code',
            'password' => 'mynewpass'
        ])->assertJsonStructure([
            'error'
        ])->assertStatus(422);
    }
}
