<?php

namespace App\Http\Middleware;

use Closure;
use PDOException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class CheckDbConnectivity
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        try
        {
            DB::connection()->getPdo();
        }
        catch (PDOException $e)
        {
            $request->session()->invalidate();
            return redirect('/')->with('fail', 'F&aacute;llo en la conexi&oacute;n a base de datos. Por favor intente acceder al sistema cuando se haya restablecido el servicio.');
        }
        return $next($request);
    }
}
