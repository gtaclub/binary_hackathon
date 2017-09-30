<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use ApiAi\Client;
use Carbon\Carbon;
use Redis;

class ChatController extends Controller
{

    public function sendMessage(Request $request){
        try {
            $client = new Client('cf05a7fccde04722a4f1b5e84ce033cf');

            $query = $client->get('query', [
                'query' => $request->input('message'),
                'sessionId' => time(),
            ]);

            $response = json_decode((string) $query->getBody(), true);

            $replied = $response['result']['fulfillment']['messages'][0]['speech'];

        } catch (\Exception $error) {
            echo $error->getMessage();
        }

        $redis = Redis::connection();
        $data = ['message' => $request->input('message'), 'replied' => $replied];
        $redis->publish('message', json_encode($data));
        return json_encode($data);
    }

}
