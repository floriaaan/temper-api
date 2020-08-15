<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;

class UserController extends Controller
{
    public function connect(Request $request)
    {
        
        $this->validate($request, [
            'login' => 'exists:users,email',
            'password' => 'required'
        ]);
        
        $post = $request->input();


        try {
            
            $user = User::where('email', $post['login'])->firstOrFail();
            
            $user->isLogged = true;
            $user->save();

            $data = [
                'user' => $user,
                'token' => $user->token
            ];
            
            if (password_verify($post['password'], $user->password)) {
                return response()->json(
                    [
                        'response' => [
                            'data' => $data
                        ],
                        'error' => null
                    ],
                    200
                );
            } else {
                return response()->json(
                    [
                        'response' => [
                            'data' => 'Wrong credentials'
                        ],
                        'error' => null
                    ],
                    400
                );
            }
        } catch (\Exception $e) {
            return response()->json(
                [
                    'response' => null,
                    'error'    => $e
                ],
                500
            );
        }
    }

    public function logout(Request $request)
    {
        $this->validate($request, [
            'token' => 'exists:users,token'
        ]);
        $post = $request->input();


        try {
            $user = User::where('token', $post['token'])->firstOrFail();
            $user->isLogged = false;
            $user->save();

            $data = [
                'user' => $user
            ];
            return response()->json(
                [
                    'response' => [
                        'data' => $data
                    ],
                    'error' => null
                ],
                200
            );
        } catch (\Exception $e) {
            return response()->json(
                [
                    'response' => null,
                    'error'    => $e
                ],
                500
            );
        }
    }

    public function register(Request $request)
    {
        $this->validate($request, [
            'login' => 'required',
            'password' => 'required|confirmed',
            'password_confirmation' => 'required',
            'email' => 'email|required|unique:users,email'
        ]);
        $post = $request->input();


        try {
            $user = new User();
            $user->name = $post['login'];
            $user->email = $post['email'];

            $user->password($post['password']);
            return response()->json(
                [
                    'response' => [
                        'data' => [
                            'token' => $user->token,
                            'user' => $user
                        ]
                    ],
                    'error' => null
                ],
                201
            );
        } catch (\Exception $e) {
            return response()->json(
                [
                    'response' => null,
                    'error'    => $e
                ],
                500
            );
        }
    }

    public function get($id)
    {
        try {
            $user = User::find($id);
            return response()->json(
                [
                    'response' => [
                        'data' => $user
                    ],
                    'error' => null
                ],
                200
            );
        } catch (\Exception $e) {
            return response()->json(
                [
                    'response' => null,
                    'error'    => $e
                ],
                500
            );
        }
    }
}
