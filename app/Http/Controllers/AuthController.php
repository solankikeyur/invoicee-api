<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegisterRequest;
use Illuminate\Http\Request;
use Exception;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Laravel\Passport\TokenRepository;


class AuthController extends ApiController
{
    public function register(RegisterRequest $request) {
        try {
            $user = User::create($request->validated());
            return $this->getSuccessResponse(["message" => "Successfully registered."]);
        } catch(Exception $e) {
            return $this->getFailureResponse(["message" => $e->getMessage()]);
        }
    }
    
    public function login(Request $request) {
        try {

            $credentials = $request->validate([
                "email" => "required|email",
                "password" => "required"
            ]);
            
            if (!auth()->attempt($credentials)) {
                throw new Exception("Incorrect login details.");
            }
            
            return $this->getSuccessResponse(["message" => "Login success."]);

        } catch (Exception $e) {
            return $this->getFailureResponse(["message" => $e->getMessage()]);
        }
    }

    public function getProfile() {
        try {

            $user = auth("api")->user();
            return $this->getSuccessResponse(["user" => $user]);

        } catch (Exception $e) {
            return $this->getFailureResponse(["message" => $e->getMessage()]);
        }
    }

    public function logout(Request $request) {
        try {

            $tokenRepository = app(TokenRepository::class);
            $userId = auth("api")->user()->id;
            $tokens = $tokenRepository->forUser($userId);
            if(!empty($tokens)) {
                foreach($tokens as $token) {
                    $tokenRepository->revokeAccessToken($token->id);
                }
            }
            return $this->getSuccessResponse(["message" => "Logout success"]);

        } catch (Exception $e) {
            return $this->getFailureResponse(["message" => $e->getMessage()]);
        }
    }

    
}
