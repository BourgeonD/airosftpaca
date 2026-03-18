<?php
namespace App\Http\Middleware;

use App\Models\Setting;
use Closure;
use Illuminate\Http\Request;

class CheckMaintenance
{
    // Sections et leurs préfixes d'URL
    const SECTIONS = [
        'maintenance_forum'    => 'forum',
        'maintenance_events'   => 'parties',
        'maintenance_squads'   => 'escouades',
        'maintenance_profiles' => 'joueur',
        'maintenance_listings'  => 'annonces',
    ];

    public function handle(Request $request, Closure $next)
    {
        // Toujours laisser passer admins et routes système
        if ($request->is('admin*') || $request->is('login') || $request->is('logout') || $request->is('register')) {
            return $next($request);
        }

        $isAdmin = auth()->check() && auth()->user()->role === 'admin';

        // Maintenance globale
        if (Setting::get('maintenance_mode') === '1') {
            if ($isAdmin) return $next($request);
            $message = Setting::get('maintenance_message', 'Site en maintenance.');
            return response()->view('maintenance', compact('message'), 503);
        }

        // Maintenance par section
        if (!$isAdmin) {
            foreach (self::SECTIONS as $key => $prefix) {
                if (Setting::get($key) === '1' && $request->is($prefix.'*')) {
                    $message = 'Cette section est temporairement en maintenance. Revenez bientôt !';
                    return response()->view('maintenance', compact('message'), 503);
                }
            }
        }

        return $next($request);
    }
}
