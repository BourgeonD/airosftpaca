<?php
namespace App\Http\Middleware;
use App\Models\Setting;
use Closure;
use Illuminate\Http\Request;

class CheckMaintenance
{
    public function handle(Request $request, Closure $next)
    {
        // Toujours laisser passer les admins et les routes admin/login
        if ($request->is('admin*') || $request->is('login') || $request->is('logout')) {
            return $next($request);
        }

        if (Setting::get('maintenance_mode') === '1') {
            if (auth()->check() && auth()->user()->role === 'admin') {
                return $next($request);
            }
            $message = Setting::get('maintenance_message', 'Site en maintenance.');
            return response()->view('maintenance', compact('message'), 503);
        }

        return $next($request);
    }
}
