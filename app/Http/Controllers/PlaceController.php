<?php

namespace App\Http\Controllers;

use App\Place;
use App\User;
use Illuminate\Http\Request;

class PlaceController extends Controller
{

    public function get($token)
    {
        try {
            $place = Place::where('token', $token)->firstOrFail();

            return response()->json(
                [
                    'response' => [
                        'data' => $place,
                        'user' => User::find($place->user)
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

    public function getUser($token)
    {

        try {
            $user = User::where('token', $token)->firstOrFail();

            $places = Place::where('user', $user->id)->get();
            $list = [];

            foreach ($places as $place) {
                $list[] = $place;
            }



            return response()->json(
                [
                    'response' => [
                        'data' => $list,
                        'user' => $user
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



    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'user' => 'exists:users,token|required',
            'gps_lon' => 'required',
            'gps_lat' => 'required',
        ]);

        $post = $request->input();
        $user = User::where('token', $post['user'])->firstOrFail();

        try {
            $place = Place::create([
                'name' => $post['name'],
                'user' => $user->id,
                'gps_lon' => $post['gps_lon'],
                'gps_lat' => $post['gps_lat'],
            ]);

            return response()->json(
                [
                    'response' => [
                        'data' => $place,
                        'user' => $user
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


    public function delete(Request $request)
    {
        $this->validate($request, [
            '_token' => 'exists:places,token'
        ]);

        $token = $request->input('_token');

        try {
            $place = Place::where('token', $token)->firstOrFail();
            $place->delete();
            

            return response()->json(
                [
                    'response' => [
                        'data' => "deleted",
                        'user' => $user
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
