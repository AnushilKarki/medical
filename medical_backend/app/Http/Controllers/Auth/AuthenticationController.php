<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Support\Facades\Response;
use App\Http\Controllers\Controller;
use App\Models\Traits\ApiResponser;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class AuthenticationController extends Controller
{
    use ApiResponser;
    public function register(Request $request)
    {
        $attr = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|unique:users,email',
            'password' => 'required|string|min:6|confirmed',
            // 'mobile_number' => 'required|min:10',
        ]);
        try{
            $user = User::create([
                'name' => $attr['name'],
                // 'mobile_number'=>$attr['mobile_number'],
                'password' => bcrypt($attr['password']),
                'email' => $attr['email']
            ]);
     $msg='user created successfully';
        }
        catch (\Exception $e) {
             dd($e);
             $msg='user not created ';
            //  return $e;
        }
    
// $user->notify(new UserCreated($user));
        return $this->success([
            'token' => $user->createToken('API Token')->plainTextToken
        ],$msg);

    }
    public function login(Request $request)
    {
//        $field_type = filter_var($request->username, FILTER_VALIDATE_EMAIL) ? 'email' : 'mobile_number';
        $validate = $request->validate([
            'username' => [
                'required', 'min:10',
//                new IsValidMobileNumber(),
            ],
            'password' => 'required|string'
        ]);
        unset($validate['username']);
        // $validate['mobile_number'] = $request->username;
        $validate['name'] = $request->username;

        if (!Auth::attempt($validate)) {
            return $this->error('Credentials not match', 401);
        }
        $token = auth()->user()->createToken('API Token')->plainTextToken;
        return $this->success($token, 'logged in successfully', 200);
    }


    public function logout()
    {
        auth()->user()->tokens()->delete();
        return [
            'message' => 'Logged Out Successfully'
        ];
    }
}