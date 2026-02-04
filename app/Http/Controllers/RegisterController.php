<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Http\Requests\RegisterRequest;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{
    public function index()
    {
        return view('register.index');
    }

    public function create(RegisterRequest $request)
    {
        $form = $request->validated();
        $form['password'] = Hash::make($request->password);

        $user = new User();
        $user->fill($form)->save();
        return to_route('home')->with('success', 'アカウント登録が完了しました');
    }
}
