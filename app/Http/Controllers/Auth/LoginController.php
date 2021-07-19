<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
//use Illuminate\Foundation\Auth\AuthenticatesUsers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; 


class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
        
    }
    //Peticion de las credenciales para el acceso al Sistema
    public function login(Request $request)
    {
        //Hace referencia a la validateLogin
        $this->validateLogin($request); 

        if (Auth::attempt(['usuario' => $request->usuario, 'password' =>$request->password, 'condicion' =>1])) {
            return redirect()->route('main');
        }
        return back()
        ->withErrors(['usuario' => trans('auth.failed')])
        ->withInput(request(['usuario']));
    }
    protected function validateLogin(Request $request){
        $this->validate($request,[
            'usuario' => 'required|string',
            'password' => 'required|string'
        ]);

    }

    protected function logout(Request $request){
        Auth::logout();
        $request->session()->invalidate();
        return redirect('/');
    }
}
