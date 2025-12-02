<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\Click;
use App\Models\Article;
use Illuminate\Support\Facades\Log;

class StatMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        preg_match('/\d+/', $request->path(), $matches);
        Log::info('StatMiddleware сработал для пути: ' . $request->path());
        Log::info('Найденные ID: ', $matches);
        
        if (!empty($matches)) {
            $article = Article::find($matches[0]);
            
            if ($article) {
                Click::create([
                    'article_id' => $article->id,
                    'article_title' => $article->title,
                ]);
                Log::info('Создан клик для статьи: ' . $article->title);
            } else {
                Log::warning('Статья не найдена для ID: ' . $matches[0]);
            }
        } else {
            Log::warning('ID статьи не найден в пути: ' . $request->path());
        }
        
        return $next($request);
    }
}