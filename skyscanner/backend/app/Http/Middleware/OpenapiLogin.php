<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Redis;

class OpenapiLogin
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
        /*if(!session('user')){
            return redirect('openapi/login');
        }*/
        $results = new \results;
        $errors = new \errors;
        //判断$token是否在缓存里面
        /*if($_GET){
            if( !Redis::get($_GET['token'])){
                return redirect('openapi/login');
            }
        }else{
            return redirect('openapi/login');
        }*/

        return $next($request);
    }
}
