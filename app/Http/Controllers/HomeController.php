<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Models\Article;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        $articles = Article::all()->where('is_public', true);
        $params = [
            'articles' => $articles,
            'user' => Auth::user(),
        ];
        return view('home.index', $params);
    }
}
