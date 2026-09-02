<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureTeacherApproved
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (!$user || !$user->teacherProfile || !$user->teacherProfile->isApproved()) {
            abort(403, 'Your account is pending approval by the administrator.');
        }

        return $next($request);
    }
}
