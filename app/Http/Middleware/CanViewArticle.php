<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

use App\Models\Article;
use Illuminate\Support\Facades\Auth;

class CanViewArticle
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $articleId = $request->route()->parameter('article');
        $article = Article::find($articleId);

        if (!$article) {
            abort(404);
        }

        $userId = Auth::user()->id ?? null;
        $article = Article::find($article->id)->where('user_id', $userId)->first();
        if (!$article) {
            abort(403);
        }

        return $next($request);
    }
}
