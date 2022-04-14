<?php

namespace App\Http\Middleware;

use Closure;

class isChofer
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
        if(auth()->check() && auth()->user()->type_user == 2){
            return $next($request);
        }else{
            return redirect('/login');
        }
    }
}
