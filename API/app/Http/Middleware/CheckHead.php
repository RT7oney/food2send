<?php

namespace App\Http\Middleware;

use Closure;

class CheckHead {
	/**
	 * Handle an incoming request.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  \Closure  $next
	 * @return mixed
	 */
	public function handle($request, Closure $next) {
		if (!env("APP_DEBUG")) {
			if (!isset($_SERVER['HTTP_X_SECRET_KEY'])) {
				return response('您没有接口调用权限', 400);
			}
		}
		return $next($request);
	}
}
