<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        if (!$request->user()) {
            return redirect()->route('login');
        }

        // Admin peut tout faire
        if ($request->user()->role === 'admin') {
            return $next($request);
        }

        // Éclater les rôles séparés par | ou , pour supporter les deux syntaxes
        $allowedRoles = [];
        foreach ($roles as $role) {
            foreach (preg_split('/[|,]/', $role) as $r) {
                $allowedRoles[] = trim($r);
            }
        }

        if (in_array($request->user()->role, $allowedRoles)) {
            return $next($request);
        }

        abort(403, 'Accès non autorisé.');
    }
}
