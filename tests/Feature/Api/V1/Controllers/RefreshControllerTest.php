<?php

namespace App\Feature\Api\V1\Controllers;

use App\TestCase;
use Laraquick\Tests\Traits\Common;

class RefreshControllerTest extends TestCase
{
    use Common;

    protected function setUpOnce()
    {
        $this->user();
    }

    public function testRefresh()
    {
        $resp = $this->login()
            ->post('api/auth/refresh', [], $this->headers());
        $this->storeResponse($resp, 'auth/refresh');
        $resp->assertJsonStructure([
                'status',
                'token',
                'expires_in'
            ])->isOk();
    }

    public function testRefreshWithError()
    {
        $response = $this->post('api/auth/refresh', [], [
            'Authorization' => 'Bearer Wrong'
        ]);

        $response->assertStatus(500);
    }
}
