<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserCollection;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Laravel\Passport\PersonalAccessTokenResult;

class UserAuthController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api')->except(['register', 'login']);
    }

    public function register(Request $request)
    {
        $data = $request->validate([
            'name'     => 'required|max:255',
            'email'    => 'required|email|unique:users',
            'password' => 'required|confirmed',
        ]);

        $data['password'] = bcrypt($request->password);

        $user = User::create($data);

        $token = $user->createToken('API Token')->accessToken;

        return response(
            [
                'user'  => new UserResource($user),
                'token' => $token,
            ],
            200
        );
    }

    public function login(Request $request)
    {
        $data = $request->validate([
            'email'    => 'email|required',
            'password' => 'required',
        ]);

        if (!auth()->attempt($data)) {
            return response(
                [
                    'message' => 'Incorrect Details. Please try again',
                ],
                403
            );
        }

        $token = auth()->user()->createToken('API Token')->accessToken;

        return response(
            [
                'user'  => new UserResource(auth()->user()),
                'token' => $token,
            ],
            200
        );
    }

    public function delete(Request $request)
    {
        if (auth()?->user()?->id !== 1) {
            return response(
                [
                    'error' => "You don't have sufficient privileges for this action.",
                ],
                403
            );
        }

        $data = $request->validate([
            'id' => 'required|integer'
        ]);

        $user = User::find($data['id']);
        if ($user === null) {
            return response(
                [
                    'message' => 'User not found.',
                ],
                404
            );
        }

        $user->delete();
        if (User::find($data['id']) !== null) {
            return response(
                [
                    'message' => 'Error deleting user.',
                ],
                422
            );
        }

        return response(
            [
                'message' => 'User successfully deleted.',
            ],
            200
        );
    }

    public function list()
    {
        if (auth()?->user()?->id !== 1) {
            return response(
                [
                    'error' => "You don't have sufficient privileges for this action.",
                ],
                403
            );
        }

        $users = User::all();

        return new UserCollection($users);
    }
}
