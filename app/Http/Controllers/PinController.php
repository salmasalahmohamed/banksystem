<?php

namespace App\Http\Controllers;

use App\services\userService;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\Request;

class PinController extends Controller
{
    use ApiResponseTrait;

    public function setPin(Request $request,userService $userService){

        $validated = $request->validate([
            'pin'=>'request|string|min:4|max:4'
        ]);
        $user=$request->user();
        $userService->setUpPin($user,$validated->pin);
        return $this->sendResponse([], 'pin set successfully.');

    }
    public function validatePin(Request $request,userService $userService){
        $validated = $request->validate([
            'pin'=>'request|string|min:4|max:4'
        ]);
        $user=$request->user();
        $isValid=$userService->validatePin($user->id,$validated->pin);
        return $this->sendResponse(['isValid'=>$isValid], 'pin set successfully.');


    }


}
