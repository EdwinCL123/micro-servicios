<?php


namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckApiKey
{
    public function handle(Request $request, Closure $next)
    {
        $apiKey = $request->header('X-API-KEY');
        if (!$apiKey || $apiKey !== env('INVENTARIO_API_KEY')) {
            return response()->json([
                'errors' => [
                    [
                        'status' => '401',
                        'title' => 'Unauthorized',
                        'detail' => 'API Key inválida o faltante'
                    ]
                ]
            ], 401);
        }
        return $next($request);
    }
}
