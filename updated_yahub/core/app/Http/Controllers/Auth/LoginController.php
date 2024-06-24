<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Auth;
class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;
    public function redirectTo()
    {
        if (Auth::check()) {
            $user = Auth::user();
            $user->is_online = 1;
            $user->save();
        }
        switch(Auth::user()->role){
            
            case 1:
                $this->redirectTo = '/admin';
                return $this->redirectTo;
                break;
            case 2:
                if(!Auth::user()->is_active){
                     $this->redirectTo = '/';
                     session()->flash('error', 'Please wait for admin to approve your account.');
                    return $this->redirectTo;
                }else{
                    $this->redirectTo = '/agency/projects';
                    return $this->redirectTo;
                }
                
                break; 
            case 3:
                $this->redirectTo = '/client/projects';
                return $this->redirectTo;
                break; 
            case 4:
                 if(!Auth::user()->is_active){
                     $this->redirectTo = '/';
                     session()->flash('error', 'Supplier must be approved by admin first.');
                    return $this->redirectTo;
                }else{
                    $this->redirectTo = '/lite-agency/projects';
                    return $this->redirectTo;
                }
                break; 
            default:
                $this->redirectTo = '/login';
                return $this->redirectTo;
        }
         
        // return $next($request);
    } 
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //$this->middleware('guest')->except('logout');
    }
}
