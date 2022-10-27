<?php

namespace App\Http\Middleware;

use App\Licence;
use Closure;

class LicenceMiddleWare
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
		$callStatsLicense   = Licence::where('app', 'call_stat_plus')->first();
		if (!$callStatsLicense) {
			return abort(403, 'permission denied');
		}

		return $next($request);
	}
}
