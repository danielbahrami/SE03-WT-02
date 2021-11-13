<?php

namespace App\Http\Controllers;

use App\Models\Adoption;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function index()
    {
        $adoptions = Adoption::latest()->unadopted()->get();
        return view('adoptions.list', ['adoptions' => $adoptions, 'header' => 'Available for adoption']);
    }

    public function login()
    {
        return view('login');
    }

    public function doLogin(Request $request) {
        $user = $request -> validate([
            'email' => ['required', 'email'],
            'password' => ['required']
        ]);

        if (Auth::attempt($user)) {
            $request -> session() -> regenerate();
            return redirect('/');
        }
    }

    public function register()
    {
        return view('register');
    }

    public function doRegister(Request $request) {
        $request -> validate([
            'name' => ['required'],
            'email' => ['email'],
            'password' => ['required'], ['confirmed']
        ]);

        $user = new User();
        $user -> name = $request -> name;
        $user -> email = $request -> email;
        $user -> password = bcrypt($request['password']);
        $user -> save();

        Auth::login($user);

        return redirect('/');
    }

    public function logout()
    {
        /*
        |-----------------------------------------------------------------------
        | Task 2 User, step 3. You should implement this method as instructed
        |-----------------------------------------------------------------------
        */
    }
}
