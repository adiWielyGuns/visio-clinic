<?php

namespace App\Http\Middleware;

use App\Models\Pasien;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PasienAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function __construct(Pasien $auth)
    {
        $this->auth = $auth;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        if (!Auth::guard('pasien')->check()) {
            return redirect('/');
        }

        $check = Pasien::where('id_pasien', Auth::guard('pasien')->user()->id_pasien)
            ->where('tanggal_lahir', Auth::guard('pasien')->user()->tanggal_lahir)
            ->first();

        if (!$check) {
            return back()->withErrors([
                'credential' => 'Tidak ada data pasien terdaftar'
            ]);
        }
        return $next($request);
    }
}
