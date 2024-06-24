<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use App\Models\User;
use App\Models\Setting;
use Mail;
use App\Mail\Notification;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        $fields=[];
        if($data["role"]==2)
        {
            $fields= [
            'name' => ['required', 'string', 'max:255'],
            'username' => ['required', 'string', 'max:255'],
            'role' => ['required'],
             'code' => ['required', new \App\Rules\MatchesSecretCode],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8'],
            ];
        }
        if($data["role"]==3){
            $fields= [
            'name' => ['required', 'string', 'max:255'],
            'username' => ['required', 'string', 'max:255'],
            'role' => ['required'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8'],
            ];
        }
         if($data["role"]==4){
           $fields= [
            'name' => ['required', 'string', 'max:255'],
            'username' => ['required', 'string', 'max:255'],
            'role' => ['required'],
             'code' => ['required', new \App\Rules\MatchesSecretCodeLite],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8'],
            ];
        }
        return Validator::make($data,$fields);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\Models\User
     */
    protected function create(array $data)
    {
        $user=User::create([
            'name' => $data['name'],
            'is_active'=>intval($data["role"])==2?0:1,
            'username' => $data['username'],
            'role' => $data['role'],
            'email' => $data['email'],
            'is_active'=>intval($data["role"]==4)?0:1,
            'secret_code'=>intval($data["role"])==2?$data["code"]:"",
            'password' => Hash::make($data['password']),
            'agency_contact'=>isset($data["agency_contact"]) ? $data["agency_contact"] : ''  
        ]);
        
        if($user){
            $role=intval($data["role"])==2?"Agency":"User";
            $role=intval($data["role"])==4?"Lite Agency":$role;
            $nMail=new Notification();
            $nMail->text="New ".$role." ".$data["name"]."(".$data["email"].") Registered.";
            $nMail->url=url("/");
            $nMailUser=User::where('role',1)->first();
            Mail::to($nMailUser)->send($nMail);
            session()->flash('register_success',true);
            if(intval($data["role"])==4)
            {
                session()->flash('success', 'Agencies must be approved by admin first.');
            }else{
                session()->flash('success', intval($data["role"])==2 ?'Success! Please wait for admin to approve your account.':'Success! Please go back to the main website and login. ');
            }
        }else{
             session()->flash('error', 'Something went wrong.');
        }
        return $user; 
    }
}
