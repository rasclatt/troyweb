<?php
namespace App\Http\Middleware;

use Closure;
use App\Helpers\Users;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckUserAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!Users::isAdmin()) {
            return redirect('/')->with('error', 'You do not have access to this resource.');
        }
        return $next($request);
    }
}
