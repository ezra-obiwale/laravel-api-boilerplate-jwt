<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use Laraquick\Controllers\Traits\Api;
use Dingo\Api\Routing\Helpers;

abstract class SuperController extends Controller
{
    use Api, Helpers;
}
