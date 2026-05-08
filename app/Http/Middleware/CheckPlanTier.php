<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckPlanTier
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$plans): Response
    {
        $tenantId = session('tenant_id');
        
        if (!$tenantId) {
            abort(403, 'No tenant selected.');
        }

        $tenant = \App\Models\Tenant::find($tenantId);

        if (!$tenant || !in_array($tenant->plan_tier, $plans)) {
            abort(403, 'Your current plan does not allow access to this feature.');
        }

        return $next($request);
    }
}
