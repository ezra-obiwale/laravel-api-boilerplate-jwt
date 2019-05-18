<?php

namespace App\Feature\Api\V1\Controllers;

use App\Entities\V1\User;
use App\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laraquick\Tests\Traits\Common;

class ForgotPasswordControllerTest extends TestCase
{
    use Common;

    protected function setUpOnce()
    {
        $this->user();
    }

    public function testForgotPasswordRecoverySuccessfully()
    {
        $resp = $this->post('api/auth/recovery', [
            'email' => 'jdoe@email.com'
        ]);
        $this->storeResponse($resp, 'auth/recovery');
        $resp->assertJson([
            'status' => 'ok'
        ])->isOK();
    }

    public function testForgotPasswordRecoveryReturnsUserNotFoundError()
    {
        $this->post('api/auth/recovery', [
            'email' => 'unknown@email.com'
        ])->assertJsonStructure([
            'error'
        ])->assertStatus(404);
    }

    public function testForgotPasswordRecoveryReturnsValidationErrors()
    {
        $this->post('api/auth/recovery', [
            'email' => 'i am not an email'
        ])->assertJsonStructure([
            'error'
        ])->assertStatus(422);
    }
}
