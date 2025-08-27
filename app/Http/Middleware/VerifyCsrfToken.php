<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
    protected $except = [
        'api/*',            // Ignora CSRF para todas las rutas API
        'sanctum/csrf-cookie' // Si usas la cookie de CSRF de Sanctum
    ];
}
