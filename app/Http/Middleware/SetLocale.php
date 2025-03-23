<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

class SetLocale
{
    public function handle(Request $request, Closure $next)
    {
        // الحصول على اللغة من الـ Header
        $locale = $request->header('Accept-Language', 'en'); // اللغة الافتراضية هي الإنجليزية

        // تغيير لغة التطبيق باستخدام App::setLocale()
        App::setLocale($locale);

        return $next($request);
    }
}
