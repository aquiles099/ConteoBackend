<?php

namespace App\Http\Middleware;

use App\Role;
use Closure;
use JWTAuth;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $roles = array_slice(func_get_args(), 2); // [default, admin, manager]

        foreach ($roles as $roleName) {
            try {
                $role=Role::where("name",$roleName)->firstOrFail(); // make sure we got a "real" role

                $user = JWTAuth::parseToken()->authenticate();

                if ($user->id_role==$role->id) {
                    return $next($request);
                }
            } catch (ModelNotFoundException $exception) {
                return response()->json(['error'=>'Could not find role ' . $roleName]);
            }
        }

        return response()->json([
            'message'=>'Acceso denegado, no tiene autorización para ver el contenido o realizar la operación.'
        ], 401);
    }
}
