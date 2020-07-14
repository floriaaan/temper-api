<?php

namespace App\Http\Controllers;

use App\Measure;
use App\Probe;
use App\User;
use Illuminate\Http\Request;

class MeasureController extends Controller
{

    public function store(Request $request)
    {
        $this->validate($request, [
            'probe'       => 'exists:probes,id|required',
            'temperature' => 'required',
            'humidity'    => 'required',
        ]);

        $post = $request->input();

        try {
            $probe = Probe::find($post['probe']);

            if ($probe->state) {
                $measure = Measure::create([
                    'probe'       => $post['probe'],
                    'temperature' => $post['temperature'],
                    'humidity'    => $post['humidity'],
                    'date'        => date('Y-m-d H:i:s')
                ]);

                return response()->json(
                    [
                        'response' => [
                            'data' => $measure,
                            'user' => User::find($probe->user)
                        ],
                        'error' => null
                    ],
                    201
                );
            } else {
                return response()->json(
                    [
                        'response' => [
                            'data' => $probe,
                            'user' => User::find($probe->user)
                        ],
                        'error' => "Probe state is disabled"
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

    public function probe($id, $limit = 0)
    {

        try {
            $list = Measure::where('probe', $id)
            ->orderBy('id', 'desc')
            ->limit($limit)
            ->get();


            return response()->json(
                [
                    'response' => [
                        'data' => $list,
                        'user' => User::find(Probe::find($id)->user)
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
