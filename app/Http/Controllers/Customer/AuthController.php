<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function login(LoginRequest $request)
    {

        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $credentials = $request->only(['email', 'password']);

        $customer = User::where('email', $request->email)->first();
        if ($customer) {
            if ($customer->is_active == 0) {
                return $this->sendError('User Not Active');
            } else {
                if (Hash::check($request->password, $customer->password)) {
                    Auth::attempt($credentials);
                    $success['token'] =  $customer->createToken('customer-unface')->accessToken;
                    $success['roles'] = $customer->getRoleNames();
                    return $this->sendResponse($success, 'User login Succesfully');
                } else {
                    return $this->sendError('Password Wrong');
                }
            }
        } else {
            return $this->sendError('Email Not Found');
        }
    }

    public function logout()
    {
        if (Auth::check()) {
            $adminToken = Auth::user()->token();
            try {
                $adminToken->revoke();
                return $this->sendResponse(1, 'User Logout Succesfully');
            } catch (Exception $err) {
                $this->sendError('Ther is something wrong', $err);
            }
        } else {
            $this->sendError('Unauthenticated');
        }
    }
}
