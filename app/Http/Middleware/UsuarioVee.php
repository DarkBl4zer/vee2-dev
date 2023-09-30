<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Symfony\Component\HttpFoundation\Response;

class UsuarioVee
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $temp = (array)Session::get('UsuarioVee');
        if (is_null($temp)) {
            return response()->json(array("error"=>"No autorizado"),401);
        } else{
            if (count($temp)==0) {
                Session::forget('UsuarioVee');
                return response()->json(array("error"=>"No autorizado"),401);
            } else{
                $request->merge(["sesion"=>$temp]);
                return $next($request);
            }
        }
    }
}
