<?php

namespace App\Http\Middleware;

use Symfony\Component\HttpFoundation\Response;
use Closure;
use Illuminate\Http\Request;

class SolderMods
{
    /**
     * Handle an incoming request.
     *
     * @param  Request  $request
     * @param  Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next): Response
    {
        switch ($request->segment(2)) {
            case 'create':
                $check = 'mods_create';
                break;
            case 'delete':
                $check = 'mods_delete';
                break;
            case 'modify':
            case 'view':
            case 'list':
            case 'add-version':
            case 'rehash':
            case 'delete-version':
                $check = 'mods_manage';
                break;
            default:
                return redirect('mod/list');
        }
        $user = $request->user();
        if (! $user) {
            return redirect('dashboard')
                ->with('permission', 'You do not have permission to access this area.');
        }
        $perms = $user->permission;
        if (! $perms->solder_full && ! $perms->{$check}) {
            return redirect('dashboard')
                ->with('permission', 'You do not have permission to access this area.');
        }

        return $next($request);
    }
}
