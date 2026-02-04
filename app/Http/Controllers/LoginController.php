<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\LoginRequest;
use App\Models\User;

class LoginController extends Controller
{
    public function index()
    {
        return view('login.index', ['user' => Auth::user()]);
    }

    public function login(LoginRequest $request)
    {
        $form = $request->validated();

        if (Auth::attempt([
            'email' => $form['email'],
            'password' => $form['password'],
        ])) {
            $request->session()->regenerate();
            return redirect()->intended(route('home'))
                ->with('success', 'ログインに成功しました');
        }

        return back()->withErrors([
            'login' => 'メールアドレスまたはパスワードに誤りがあります'
        ])->withInput();
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return to_route('home')->with('success', 'ログアウトしました');
    }
}
