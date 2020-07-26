<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Probe;
use App\Share;

class ShareController extends Controller
{

    
    public function token_probe(Request $request) {
        $this->validate($request, [
            'user' => 'exists:users,token|required',
            'share' => 'exists:probes,share_token|required'
        ]);

        $post = $request->input();

        try {
            $user = User::where('token', $post['user'])->firstOrFail();
            $probe = Probe::where('share_token', $post['share'])->firstOrFail();

            $share = Share::create([
                'item_type' => 'probe',
                'item_token' => $probe->token,
                'duration' => 'always',
                'date' => date('Y-m-d H:i:s'),
                'user' => $user->id
            ]);
            
            return response()->json(
                [
                    'response' => [
                        'data' => $share,
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

    public function qrcode_probe(Request $request) {

    }
}
