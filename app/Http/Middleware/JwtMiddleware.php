<?php

namespace App\Http\Middleware;

use App\Http\Resources\BaseResource;
use Closure;
use Exception;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Tymon\JWTAuth\Facades\JWTAuth;

class JwtMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {

        try {
            if (!$request->hasHeader('Authorization')) {
                abort(401, 'Unauthorized');
            }
            if (!$request->bearerToken()) {
                abort(401, 'Unauthorized');
            }

            $user = JWTAuth::parseToken()->authenticate();

            if(!$user) {
                abort(401, 'unauthorized');
            }

            auth()->setUser($user);

        } catch (\Tymon\JWTAuth\Exceptions\TokenExpiredException $e) {
            abort(401, 'Token expired');
        } catch (\Tymon\JWTAuth\Exceptions\TokenInvalidException $e) {
            abort(401, 'Token invalid');
        } catch (\Tymon\JWTAuth\Exceptions\JWTException $e) {
            abort(401, 'Token absent');
        } catch (Exception $e) {
            abort(401, 'Unauthorized');
        }

        return $next($request);
    }
}
