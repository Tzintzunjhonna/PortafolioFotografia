<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class UserController extends Controller
{
    
    public function index(Request $request)
    {
        $user = Auth::user();
        $data['user'] = $user;
        //* Dependiendo los roles del login redireccionara a la vista

        if($user->role == 1){

            return view('admin.dashboard.index', $data);

        }else if($user->role == 2){

            return view('admin.dashboard.index', $data);

        }else{

            return view('admin.dashboard.index', $data);

        }
        
    }

    public function loginGoogle (Request $request)
    {
        $user_google = Socialite::driver('google')->user();

        $user_exist = User::where('external_id', $user_google->id)
            ->where('external_auth', 'google')
            ->orWhere('email', $user_google->email)
            ->first();

        if(isset($user_exist)){
            Auth::login(($user_exist));
            return redirect('/home');
        }else{
            $user_new = User::create([
                'name'=> $user_google->name,
                'email'=> $user_google->email,
                'avatar'=> $user_google->avatar,
                'external_id'=> $user_google->id,
                'external_auth'=> 'google'
            ]);
            Auth::login(($user_new));
            return redirect('/home');
        }
        
        
    }
}