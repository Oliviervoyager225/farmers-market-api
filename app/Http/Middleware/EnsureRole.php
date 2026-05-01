<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use App\Domain\Auth\Enums\UserRole;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureRole
{
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        $user = $request->user();

        if (!$user || !in_array($user->role, $roles, strict: true)) {
            abort(403, 'Accès refusé.');
        }

        return $next($request);
    }
}
