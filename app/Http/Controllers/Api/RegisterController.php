<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Api\BaseController;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\JsonResponse;


class RegisterController extends BaseController
{
    /**
     * Register api
     *
     * @param  Request $request
     * @return JsonResponse
     */
    public function register(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email',
            'password' => 'required',
            'confirm_password' => 'required|same:password',
        ]);

        if ($validator->fails()) {
            return response()->json($this->sendError('Validation Error.', $validator->errors())->getData(true), 422);
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
        ]);

        $token = $user->createToken('MyApp')->plainTextToken;

        return response()->json(
            $this->sendResponse([
                'token' => $token,
                'name' => $user->name,
            ], 'User register successfully.')->getData(true)
        );
    }

    /**
     * Login api
     *
     * @param  Request $request
     * @return JsonResponse
     */
    public function login(Request $request): JsonResponse
    {
        // $validator = Validator::make($request->all(), [
        //     'email' => 'required|email',
        //     'password' => 'required',
        // ]);

        if (!Auth::attempt($request->only('email', 'password'))) {
            return response()->json($this->sendError('Unauthorized', ['error' => 'Invalid credentials'])->getData(true), 401);
        }

        $user = User::where('email', $request->email)->first(); // Get the user manually

        $token = $user->createToken('MyApp')->plainTextToken;

        return response()->json(
            $this->sendResponse([
                'token' => $token,
                'name' => $user->name,
            ], 'User logged in successfully.')->getData(true)
        );
    }

    /**
     * Logout api
     *
     * @return JsonResponse
     */
}
