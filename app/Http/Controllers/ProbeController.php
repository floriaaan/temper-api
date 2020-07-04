<?php

namespace App\Http\Controllers;

use App\Measure;
use App\Probe;
use App\User;

class ProbeController extends Controller
{

    public function get($id)
    {
        try {
            $probe = Probe::find($id);
            $lastMeasure = Measure::where('probe', $id)->orderBy('id', 'desc')->firstOrFail();

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
                    'temperature' => $lastMeasure->temperature,
                    'humidity' => $lastMeasure->humidity,
                    'date' => $lastMeasure->date
                ]
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

    public function getUser($id)
    {

        try {
            $probes = Probe::where('user', $id)->get();
            $list = [];

            foreach ($probes as $probe) {
                $list[] = $probe->id;
            }



            return response()->json(
                [
                    'response' => [
                        'data' => $list,
                        'user' => User::find($id)
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

    public function toggleState($id)
    {
        try {
            $probe = Probe::find($id);
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
}
