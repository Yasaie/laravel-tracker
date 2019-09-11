<?php

namespace Yasaie\Tracker\Middleware;

use Closure;
use Yasaie\Tracker\Model\Tracker as TrackerModel;

/**
 * Class    Tracker
 *
 * @author  Payam Yasaie <payam@yasaie.ir>
 * @since   2019-08-19
 *
 * @package Yasaie\Tracker\Middleware
 */
class Tracker
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
        $tracker = new TrackerModel();

        if (class_exists(\Auth::class)) {
            $tracker->user_id = \Auth::id();
        }

        $parameters = array_values($request->route()->parameters);

        $tracker->path = urldecode($request->path());
        $tracker->route = $request->route()->getName();
        $tracker->param1 = isset($parameters[0]) ? $parameters[0] : null;
        $tracker->param2 = isset($parameters[1]) ? $parameters[1] : null;
        $tracker->param3 = isset($parameters[2]) ? $parameters[2] : null;
        $tracker->method = $request->method();
        $tracker->ip_address = $request->ip();
        $tracker->agent = isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : '';

        $tracker->save();

        return $next($request);
    }
}
