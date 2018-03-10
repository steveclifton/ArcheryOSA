<?php

namespace App\Http\Middleware;

use Closure;
use Auth;
class IsAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {

        if (Auth::user()) {
            if ( Auth::user()->usertype == 1 || Auth::user()->usertype == 2 ) {
                return $next($request);
            }
        }

        return redirect('/');
    }
}
