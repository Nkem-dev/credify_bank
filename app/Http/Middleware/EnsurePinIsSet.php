<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsurePinIsSet
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // check if the authenticated user has set pin and if the user has not set pin redirect the user to transaction pin route to set the pin
       
         if (Auth::check() && !Auth::user()->hasSetPin()) {
            return redirect()->route('pin.setup')
                ->with('info', 'Please set your transaction PIN to continue.');
        }

        return $next($request);
    }
}
