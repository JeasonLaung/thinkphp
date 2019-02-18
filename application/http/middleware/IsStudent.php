<?php

namespace app\http\middleware;

class IsStudent
{
    public function handle($request, \Closure $next)
    {
    	if(!session('?'.SESSION_ROLE_INFO)){
    		return error(301,"请先登录");
    	}
    	if (session(SESSION_ROLE_INFO)['role'] !== 'student') {
    		return error(400);
    	}
    	return $next($request);
    }
}
