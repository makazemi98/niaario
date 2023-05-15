<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Resources\User\UserResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AuthController extends Controller
{
    /**
     * Issue token for specified user
     *
     * @param LoginRequest $request
     * @return \Illuminate\Http\JsonResponse | UserResource
     */
    public function login(LoginRequest $request)
    {
        if (Auth::attempt(['email' => $request->input('email'), 'password' => $request->input('password')])) {
            $user = Auth::user();

            $user->load('roles');

            $token = $user->createToken('Bearer');

            return ( new UserResource($user) )
                ->additional([
                    'token' => $token->plainTextToken
                ]);

        }

        return response()->json(
            [
                'message' => 'Invalid email or password'
            ],
            Response::HTTP_NOT_FOUND
        );
    }


    /**
     * Revoke user tokens
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();

        return response()->json(['message' => 'Logout successful']);
    }
}
