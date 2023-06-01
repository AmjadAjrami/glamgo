<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AuthCheck
{

    /**
     * @param Request $request
     * @param Closure $next
     * @return \Illuminate\Http\JsonResponse|mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if (auth('sanctum')->check() == false){
            return mainResponse_2(false, __('Not Authenticated'), (object)[], [], 200);
        }
        return $next($request);
    }
}
