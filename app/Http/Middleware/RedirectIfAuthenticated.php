<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if(Auth::check()) { //checks if user is authenticated

            $user = Auth::user(); //get the authenticated user

            if($user->role === 'user') { //if the user has the role of user
                return redirect()->route('user.dashboard'); //redirect to user dashboard
            }

            if($user->role === 'admin') { //if the user has the role of admin
                return redirect()->route('admin.dashboard'); //redirect to admin dasboard
            }

             if($user->role === 'customer_care') { //if the user has the role of customer care
                return redirect()->route('customer_care.dashboard'); //redirect to customer care dashboard
            }
        }
        
        return $next($request); //if user does not match any conditions, continue to intended request
    }
}
