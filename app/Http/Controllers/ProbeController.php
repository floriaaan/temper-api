<?php

namespace App\Http\Controllers;

use App\Measure;
use App\Probe;
use App\User;
use Illuminate\Http\Request;

class ProbeController extends Controller
{

    public function get($token)
    {
        try {
            $probe = Probe::where('token', $token)->firstOrFail();
            $lastMeasure = Measure::where('probe', $probe->id)->orderBy('id', 'desc')->first();

            $data = [
                'id' => $probe->id,
                'name' => $probe->name,
                'state' => $probe->state,
                'category' => $probe->category,
                'gps' => [
                    'lon' => $probe->gps_lon,
                    'lat' => $probe->gps_lat
                ],
                'lastmeasure' => [
                    'temperature' => $lastMeasure != null ? $lastMeasure->temperature : null,
                    'humidity' => $lastMeasure != null ? $lastMeasure->humidity : null,
                    'date' => $lastMeasure != null ? $lastMeasure->date : null
                ],
                'created_at' => $probe->created_at,
                'updated_at' => $probe->updated_at,
            ];

            return response()->json(
                [
                    'response' => [
                        'data' => $data,
                        'user' => User::find($probe->user)
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

            $probes = Probe::where('user', $user->id)->get();
            $list = [];

            foreach ($probes as $probe) {
                $list[] = $probe->token;
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

    public function toggleState($token)
    {
        try {
            $probe = Probe::where('token', $token)->firstOrFail();
            $probe->state = !$probe->state;
            $probe->save();
            
            return response()->json(
                [
                    'response' => [
                        'data' => $probe,
                        'user' => User::find($probe->user)
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

    public function store(Request $request) {
        $this->validate($request, [
            'name' => '',
            'user' => 'exists:users,id|required',
            'category' => '',
            'gps_lon' => '',
            'gps_lat' => '',
        ]);

        $post = $request->input();

        try {
            $probe = new Probe();

            $probe->name = (isset($post['name']) && $post['name'] != '') ? $post['name'] : null;
            $probe->category = (isset($post['category']) && $post['category'] != '') ? $post['category'] : null;
            $probe->gps_lon = (isset($post['gps_lon']) && $post['gps_lon'] != '') ? $post['gps_lon'] : null;
            $probe->gps_lat = (isset($post['gps_lat']) && $post['gps_lat'] != '') ? $post['gps_lat'] : null;

            $probe->user = $post['user'];

            $probe->save();
            
            return response()->json(
                [
                    'response' => [
                        'data' => $probe,
                        'user' => User::find($probe->user)
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
}
