<?php

namespace app\http\middleware;

class Logined
{
    public function handle($request, \Closure $next)
    {
    	if(session('?'.SESSION_ROLE_INFO)){
    		return error(301,"你已登录");
    	}
    	return $next($request);
    }
}
