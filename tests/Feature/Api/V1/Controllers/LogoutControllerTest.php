<?php

namespace App\Feature\Api\V1\Controllers;

use Hash;
use App\Entities\V1\User;
use App\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laraquick\Tests\Traits\Common;

class LogoutControllerTest extends TestCase
{
    use Common;

    protected function setUpOnce()
    {
        $this->user();
    }

    public function testLogout()
    {
        $response = $this->post('api/auth/login', [
            'email' => 'jdoe@email.com',
            'password' => 'secret'
        ]);

        $response->assertStatus(200);

        $responseJSON = json_decode($response->getContent(), true);
        $token = $responseJSON['token'];

        $resp = $this->post('api/auth/logout?token=' . $token, [], []);
        $this->storeResponse($resp, 'auth/logout');
        $resp->assertStatus(200);
        $this->post('api/auth/logout?token=' . $token, [], [])->assertStatus(401);
    }
}
