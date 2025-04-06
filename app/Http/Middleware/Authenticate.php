<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     */
    protected function redirectTo(Request $request): ?string
    {
        
        if ($request->is('api/*')) {
            abort(response()->json([
                'success'   => false,
                'title'     => 'UNAUTHORIZED',
                'code'      => 401,
                'data'      => null,
                'message'   => 'Unauthenticated'
            ], 401));
        } else {
            return route('login');
        }
    }
}
