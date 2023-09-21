<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class HadaraSms
{
    protected string $baseUrl = 'http://smsservice.hadara.ps:4545/SMS.ashx/bulkservice/sessionvalue';

    public function __construct(protected string $key)
    {
    }

//    public function send

    public function send($to, $message)
    {
        $response = Http::baseUrl($this->baseUrl)
//            ->withHeaders([
//                'x-api-key' => $this->key,
//                'Authorization' => 'Bearer ' . $this->key
//            ])
//            ->withToken($this->key)
            ->get('sendmessage', [
                'apikey' => $this->key,
                'to' => $to,
                'message' => $message,
            ]);

//        dd($response->body());
        return $response->json();
    }
}
