<?php

namespace App\Integrations;

use Illuminate\Database\Eloquent\Model;
use GuzzleHttp\Client;

class Ouvidoria extends Model
{
    /**
     * Make a GET HTTP request
     */
    public static function get($endpoint, $json = null, $token = null)
    {
        $client = new Client(['base_uri' => env("URL_API_OUVIDORIA")]);

        if (is_Null($token)) {
            $res = $client->request('GET', $endpoint, [
                'http_errors' => false,
                'json' => $json,
            ]);
        } else {
            $res = $client->request('GET', $endpoint, [
                'headers' => [
                    'Authorization' => "Bearer " . $token,
                ],
                'http_errors' => false,
                'json' => $json,
            ]);
        }
        $result['statusCode'] = $res->getStatusCode();
        $result['body'] = json_decode($res->getBody());
        return $result;
    }

    /**
     * Make a POST HTTP request
     */
    public static function post($endpoint, $json = null, $token = null)
    {
        $client = new Client(['base_uri' => env("URL_API_OUVIDORIA")]);

        if (is_Null($token)) {
            $res = $client->request('POST', $endpoint, [
                'http_errors' => false,
                'json' => $json,
            ]);
        } else {
            $res = $client->request('POST', $endpoint, [
                'headers' => [
                    'Authorization' => "Bearer $token"
                ],
                'http_errors' => false,
                'json' => $json,
            ]);
        }
        $result['statusCode'] = $res->getStatusCode();
        $result['body'] = json_decode($res->getBody());
        return $result;
    }

    public static function postMultiPart($endpoint, $fields, $token)
    {
        $client = new Client(['base_uri' => env("URL_API_OUVIDORIA")]);
        $res = $client->request('POST', $endpoint, [
            'headers' => [
                'Authorization' => "Bearer $token"
            ],
            'multipart' => $fields,
        ]);
        $result['statusCode'] = $res->getStatusCode();
        $result['body'] = json_decode($res->getBody());

        return $result;
    }
}
