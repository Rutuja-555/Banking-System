<?php

namespace App\Http\Controllers;

use App\Models\Accounts;
use App\Providers\AuthServiceProvider;
use App\Providers\UserServiceProvider;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class UserController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login', 'getUserDetails', 'transactions', 'getCustomerDetails', 'getCustomerTransactions']]);
    }


    //Login function
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|string|min:6',
        ]);
        if ($validator->fails()) {
            return response()->json(
                array(
                    'error' => true,
                    'message' => $validator->errors()->first(),
                    'status' => 'error'
                ),
                422
            );
        }


        $email = $request->input("email");
        $password = $request->input("password");

        //Object for AuthServiceProvider 
        $authObj = new AuthServiceProvider($email);

        // checking Email exist 
        if (!$authObj->checkEmail()) {
            return response()->json(
                array(
                    'error' => true,
                    'status' => 'error',
                    "message" => "Account Not Found",
                ),
                401
            );
        }

        // checking password 
        if (!$authObj->checkPassword($password)) {
            return response()->json(
                array(
                    'error' => true,
                    'status' => 'error',
                    "message" => "Wrong Password",
                ),
                401
            );
        }

        // getting user details 
        $user = $authObj->getUserDetails();

        // creating 36 character token
        $token = $user->createToken(Str::random(36));

        User::where('email', $email)->update(['access_token' => $token->plainTextToken]);
        return response()->json(['access_token' => $token->plainTextToken, 'data' => $user], 200);
    }


    // getting user details 
    public function getUserDetails(Request $request)
    {
        $accessToken = $request->header('Authorization');
        $token = str_replace('Bearer ', '', $accessToken);
        $userObj = new UserServiceProvider();
        $user = $userObj->getUserDetails($token);
        return response()->json(['userDetails' => $user], 200);
    }

    // storing transaction details
    public function transactions(Request $request)
    {
        $validatedData = $request->validate([
            'transaction_type' => 'required',
            'amount' => 'required|numeric|min:0',
            'Description' => 'required'
        ]);
        $accessToken = $request->header('Authorization');
        $token = str_replace('Bearer ', '', $accessToken);
        $userObj = new UserServiceProvider();
        $user = $userObj->getUserDetails($token);
        if (!$user) {
            return response()->json(
                array(
                    'error' => true,
                    'status' => 'error',
                    "message" => "Token Expired",
                ),
                401
            );
        }
        $transaction = new Accounts();
        $transaction->acc_number = $user['user']['acc_number'];
        $transaction->transaction_type = $validatedData['transaction_type'];
        $transaction->amount = $validatedData['amount'];
        $transaction->Description = $validatedData['Description'];
        $transaction->date = now()->format('Y-m-d');

        // Set other fields as needed
        $transaction->save();

        if ($validatedData['transaction_type'] == 1) {
            $total_balance =  $user['user']['total_balance'] - $validatedData['amount'];
            User::where('acc_number', $user['user']['acc_number'])->update(['total_balance' => $total_balance]);

            return response()->json(
                array(
                    'error' => false,
                    'status' => 'success',
                    "message" => "Withdrawal Recorded Successfully",
                ),
                200
            );
        } else {
            $total_balance =  $user['user']['total_balance'] + $validatedData['amount'];
            User::where('acc_number', $user['user']['acc_number'])->update(['total_balance' => $total_balance]);

            return response()->json(
                array(
                    'error' => false,
                    'status' => 'success',
                    "message" => "Deposit Recorded Successfully",
                ),
                200
            );
        }
    }

    // getting customerdetails
    public function getCustomerDetails()
    {

        $userObj = new UserServiceProvider();
        $custDetails = $userObj->getCustDetails();
        return response()->json(['customerDetails' => $custDetails], 200);
    }

    // getting customer all transaction
    public function getCustomerTransactions(Request $request)
    {
        $accountNumber = $request->input('acc_number');
        // Retrieve transactions for the specified account number
        $transactions = Accounts::where('acc_number', $accountNumber)->get();
        return response()->json([
            'transactions' => $transactions
        ]);
    }
}
