<?php

namespace App\Feature\Api\V1\Controllers;

use Hash;
use App\Entities\V1\User;
use App\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laraquick\Tests\Traits\Common;

class UserControllerTest extends TestCase
{
    use Common;

    protected function setUpOnce()
    {
        $this->user();
    }

    public function testMe()
    {
        $resp = $this->login()
            ->withHeaders($this->headers())
            ->get('api/auth/me');
        $this->storeResponse($resp, 'auth/me');
        $resp->assertJson([
                'first_name' => 'John',
                'last_name' => 'Doe',
                'email' => 'jdoe@email.com'
            ])->isOk();
    }
}
