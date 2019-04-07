<?php

namespace App\Helpers;

use Illuminate\Http\Request;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Client;

class Airtable {
    public  static function createNamePayload()
        {
            return array(
                'fields' => array(
                    'Name' => 'Josh',
                    'Class' => 'Templar',
                    'Level' => 50,
                    'Champion Points' => 256,
                    'Faction' => 'Ebonheart Pact',
                    "Race" => 'Argonian'
                )
            );
        }
    public  static function getNames($endpoint)
        {
          $body = null;

          try {
              $client = new Client(['headers' => ['Content-Type' => 'application/json', 'Authorization' => 'Bearer ' . env('AIRTABLE_APIKEY')]]);
              $res = $client->get($endpoint);

              $body = json_decode($res->getBody());
          } catch (\Exception $e) {
              \Log::error('Could not retrieve names ' . $e);
          }

          return isset($body->records) ? $body->records : false;
        }
        public static function post($endpoint, $payload)
        {
            $body = null;

            try {
                $client = new Client(['headers' => ['Content-Type' => 'application/json', 'Authorization' => 'Bearer ' . env('AIRTABLE_APIKEY')]]);

                $res = $client->post($endpoint, array('form_params' => $payload));

                $body = json_decode($res->getBody());
            } catch (\Exception $e) {
                \Log::error('Could not create name entry' . $e);
            }

            return $body;
        }
}
