<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\services\userService;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthenticationController extends Controller
{
    use ApiResponseTrait;

    public userService $userService;

    public function __constructor( $userService){
        $this->userService=$userService;
    }
    public  function register( RegisterRequest  $request){
        $validator=$request->validated();
        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());
        }
        $user=$this->userService->register($request);
        return $this->sendResponse($user, 'User register successfully.');

    }
    public function login(LoginRequest $request){
        if(Auth::attempt(['email' => $request->email, 'password' => $request->password])){
            $user = Auth::user();
            $success['token'] =  $user->createToken('MyApp')->plainTextToken;
            $success['name'] =  $user->name;
            return $this->sendResponse($success, 'User login successfully.');
        }else{
            return $this->sendError('Unauthorised.', ['error'=>'Unauthorised']);
        }
    }
public function logout($token){

        $user=Auth::guard('sanctum')->user();
        if ($token==null){
            $user->currentAccessToken()->delete();
        }
        $personalaccesstoken=$user->PersonalAccessToken::FindToken($token);
        if ($user->id=$personalaccesstoken->tokenable_id && get_class($personalaccesstoken->tokenable_type)){
            $personalaccesstoken->delete();
        }
}
    }
