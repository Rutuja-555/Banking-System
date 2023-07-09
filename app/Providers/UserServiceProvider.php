<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Accounts;


class UserServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function __construct()
    {
    }
    public function getUserDetails($token)
    {
        $user =  User::where('access_token', $token)->get()->toArray();
        $transaction = Accounts::where('acc_number',$user[0]['acc_number'])->get();
        $userDetails = array(
            'user' => $user[0],
            'transaction' => $transaction,
        );
        return $userDetails;
    }

    public function getCustDetails(){

        $customers = User::select('users.id','users.email','users.name','users.total_balance','users.acc_number','users.created_at')->where('users.user_type',1)
        ->get()->toArray();

        return $customers;
    }
}
