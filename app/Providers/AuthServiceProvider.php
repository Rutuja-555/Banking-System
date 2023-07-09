<?php

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;

use App\Models\User;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;

class AuthServiceProvider extends ServiceProvider
{
   
    private $email;
    
    public function __construct($email)
    {
        $this->email = $email;
    }

    public function checkEmail(){
        if(!empty(User::where('email' , $this->email)->get()->toArray())) return true;
        return false;   
    }


    public function checkPassword($password){
        $user =  User::where('email' , $this->email)->get()->toArray();
        if(Hash::check($password,$user[0]['password'])) return true ;
        return false;
    }

    public function getUserDetails(){
        $user =  User::where('email' , $this->email)->first();
        return $user;
    }

    
}
