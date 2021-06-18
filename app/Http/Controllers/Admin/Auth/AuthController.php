<?php

namespace App\Http\Controllers\Admin\Auth;

use App\Http\Controllers\BaseController;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\User;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Throwable;

class AuthController extends BaseController
{
    public function login(Request $request)
    {

        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $credentials = $request->only(['email', 'password']);

        $admin = User::where('email', $request->email)->first();
        if ($admin) {
            if ($admin->hasRole('superadmin')) {
                if ($admin->is_active == 0) {
                    return $this->sendError('User Not Active');
                } else {
                    if (Hash::check($request->password, $admin->password)) {
                        Auth::attempt($credentials);
                        $token = $admin->createToken('admin-unface');
                        $success['user'] = Auth::user()->makeHidden(['roles']);
                        $success['token'] =  $token->accessToken;
                        $success['expired_token'] = $token->token->expires_at->diffInSeconds(Carbon::now());
                        $success['expired_in'] = $token->token->expires_at->diffForHumans();
                        $success['roles'] = $admin->getRoleNames();
                        return $this->sendResponse($success, 'User login Succesfully');
                    } else {
                        return $this->unauthorized('Password Wrong');
                    }
                }
            } else {
                return $this->forbidden('You Dont Have Permission To Access This');
            }
        } else {
            return $this->unauthorized('Email Not Found');
        }
    }

    public function getUser()
    {
        $user = Auth::user()->makeHidden(['roles']);
        $success = $user;
        $success['role'] = $user->getRoleNames();
        return $this->sendResponse($success, 'User Fetched Succesfully');
    }

    public function validateToken()
    {
        try {
            if (Auth::guard('api')->check()) {
                return $this->sendResponse(true, 'Validated');
            }
        } catch (Throwable $th) {
            return $this->sendError('Not Validated', $th);
        }
        // return Auth::guard('api')->check();
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
            $this->unauthorized('Unauthenticated');
        }
    }
}
