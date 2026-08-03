<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckPlan
{
    public function handle(Request $request, Closure $next, string $feature = 'pro'): Response
    {
        $user = $request->user();

        if (! $user) {
            return redirect()->route('login');
        }

        $proGated = in_array($feature, config('plan_features', []), true);
        $allowed = $proGated && ($user->isPro() || $user->isOnTrial());

        if (! $allowed) {
            return redirect()->route('dashboard.plan')
                ->with('aviso', 'Esta funcionalidade requer o plano Pro.');
        }

        return $next($request);
    }
}
