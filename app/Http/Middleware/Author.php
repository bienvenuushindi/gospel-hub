<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class Author
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
//        dd(Auth::user()->type()->get()->pluck('name')->first());
//        dd(Auth::user()->type()->pluck('name'));
        if (Auth::check()) {
            if (Auth::user()->type()->get('name')[0]->name === 'author' || Auth::user()->type()->get('name')[0]->name === 'admin') {
                return $next($request);
            }
        }
        return redirect('login');
    }
}
