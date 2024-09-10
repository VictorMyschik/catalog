<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

/**
 * @OA\Info(
 *    title="APIs For Thrift Store",
 *    version="1.0.0",
 * )
 */
class ApiController extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;
}
