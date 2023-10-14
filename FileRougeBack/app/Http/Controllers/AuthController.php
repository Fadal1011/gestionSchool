<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpFoundation\Response;

class AuthController extends Controller
{
    public function register(Request $request)
    {


        $user = User::create([
            "nom"=>$request->nom,
            "prenom"=>$request->prenom,
            "username" => $request->username,
            "role"=>$request->role,
            "password" => Hash::make($request->password),
        ]);
        return response($user, Response::HTTP_CREATED);
    }



    public function login(Request $request): Response
    {
        $validator = Validator::make($request->all(), [
            'username' => 'required',
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            return response(['message' => $validator->errors()], 401);
        }

        $login = $request->only('username', 'password');

        if (Auth::attempt($login)) {
            $user = Auth::user();
            $success =  $user->createToken('MyApp')->plainTextToken;
            return response(['token' => $success,"user"=>$user], 200);
        }

        return response(['message' => 'Email or password is wrong'], 401);
    }


    public function user(Request $request)
    {
        return $request->user();
    }



    public function logout(): Response
    {
        $user = Auth::user();

        $user->currentAccessToken()->delete();
        return Response(['data' => 'User Logout successfully.'],200);
    }
}
