<?php

namespace app\http\middleware;

class IsLogin
{
    public function handle($request, \Closure $next)
    {
    	if(!session('?'.SESSION_ROLE_INFO)){
    		
    		json(['status'=>1])->send();
    		exit();
    	}

    	return $next($request);
    }
}
