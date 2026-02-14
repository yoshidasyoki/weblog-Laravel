<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Models\Article;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        $sort = [
            'value' => $request->query('sort', 'created_at'),
            'order' => $request->query('order','desc'),
        ];

        // N+1問題を考慮しない場合
        // $articles = Article::where('is_public', true)
        //     ->orderBy($sort['value'], $sort['order'])
        //     ->paginate(5);

        // N+1問題を考慮した場合
        $articles = Article::with('user')
            ->where('is_public', true)
            ->orderBy($sort['value'], $sort['order'])
            ->paginate(5);

        $params = [
            'articles' => $articles,
            'user' => Auth::user(),
            'sort' => $sort,
        ];
        return view('home.index', $params);
    }
}
