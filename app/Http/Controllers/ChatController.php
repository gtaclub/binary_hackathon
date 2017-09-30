<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use ApiAi\Client;
use Carbon\Carbon;
use Redis;
use GuzzleHttp\Client as Guzzle;

class ChatController extends Controller
{

    public function sendMessage(Request $request){
        $gC = new Guzzle();

        try {
            $client = new Client('cf05a7fccde04722a4f1b5e84ce033cf');

            $query = $client->get('query', [
                'query' => $request->input('message'),
                'sessionId' => time(),
            ]);

            $response = json_decode((string) $query->getBody(), true);

            $action = $response['result']['action'];

            if($action == "currency.convert"){
                $amount = $response['result']['parameters']['amount'];
                $currencyFrom = $response['result']['parameters']['currency-from'];
                $currencyTo = $response['result']['parameters']['currency-to'];
                $res = $gC->request('GET', 'http://api.fixer.io/latest?base='.$currencyFrom.'&symbols='.$currencyTo);
                $currencyRate = json_decode((string) $res->getBody(), true);

                $calc = $amount * $currencyRate['rates'][$currencyTo];
                $replied = "The conversion from " .$currencyFrom . " to " .$currencyTo. " would be " . $currencyTo . $calc . ".";
            }else{
                $replied = $response['result']['fulfillment']['messages'][0]['speech'];
            }

        } catch (\Exception $error) {
            echo $error->getMessage();
        }

        $redis = Redis::connection();
        $data = ['message' => $request->input('message'), 'replied' => $replied];
        $redis->publish('message', json_encode($data));
        return json_encode($data);
    }

}
