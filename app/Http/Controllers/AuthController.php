<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegisterUserRequest;
use App\Models\User;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use App\Http\Requests\LoginUserRequest;

class AuthController
{
    public function sendResponse($data, $message, $status = 200)
    {
        $response = [
            'data' => $data,
            'message' => $message
        ];

        return response()->json($response, $status);
    }

    public function sendError($errorData, $message, $status = 500)
    {
        $response = [];
        $response['message'] = $message;
        if (!empty($errorData)) {
            $response['data'] = $errorData;
        }

        return response()->json($response, $status);
    }

    public function register(RegisterUserRequest $request)
    {
        $user = User::create($request->validated()); // eloquent creation of data

        $success['user'] = $user;

        return $this->sendResponse($success, 'user registered successfully', 201);
    }

    public function login(LoginUserRequest $request)
    {
        try {
            // this authenticates the user details with the database and generates a token
            if (!$token = JWTAuth::attempt($request->validated())) {
                return $this->sendError([], "invalid login credentials", 400);
            }
        } catch (JWTException $e) {
            return $this->sendError([], $e->getMessage());
        }

        $success = [
            'token' => $token,
        ];
        return $this->sendResponse($success, 'successful login');
    }

    public function getUser()
    {
        try {
            $user = JWTAuth::parseToken()->authenticate();
            if (!$user) {
                return $this->sendError([], "user not found", 403);
            }
        } catch (JWTException $e) {
            return $this->sendError([], $e->getMessage());
        }

        return $this->sendResponse($user, "user data retrieved");
    }
}
