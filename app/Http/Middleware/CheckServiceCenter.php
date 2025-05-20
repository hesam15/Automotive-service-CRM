<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckServiceCenter
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if(auth()->user()->serviceCenter == null && !auth()->user()->hasRole('customer')) {
            return redirect()->route("serviceCenters.create", auth()->user()->id)->with("alert", ['لطفا ابتدا مجموعه خود را ثبت کنید.', 'info']);
        }

        return $next($request);
    }
}
