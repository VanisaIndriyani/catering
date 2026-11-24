<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class PastikanPeran
{
    public function handle(Request $request, Closure $next, string ...$peran): Response
    {
        $user = $request->user();

        if (! $user || ! in_array($user->peran, $peran, true)) {
            if ($user) {
                return redirect($user->beranda())->with('error', 'Akun ini tidak memiliki akses ke halaman tersebut.');
            }

            return redirect()->route('login');
        }

        return $next($request);
    }
}
