<?php

namespace App\Http\Middleware;

use Closure;

class CheckRol
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
        //check if is Authenticated
        if(!auth()->check()){
            return redirect('/login');
        }

        //check if Authenticated user is has type_user = 3
        if(auth()->user()->type_user == 3 || auth()->user()->email == 'albertcharmelocontacto@gmail.com'){
            return $next($request);
        }elseif(auth()->user()->type_user == 2){
            return redirect('/miperfil');
        }else {
            return redirect('/login');
        }
       
    }
}
