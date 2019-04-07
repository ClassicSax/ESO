<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Http\Request;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Client;


class Names extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'eso:name';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
       $names = $this->createNamePayload();
       $records = $this->getNames('https://api.airtable.com/v0/appKvtKtRVT9J73tH/ESO?api_key=keyCYYwuTUNjUH1EC');
       \Log::info($records);
       $this->postNames($names);

    }
    private function postNames($names)
    {
      $payload = $this->createNamePayload();
      try {
        $res = $this->post('https://api.airtable.com/v0/appKvtKtRVT9J73tH/ESO?api_key=keyCYYwuTUNjUH1EC', $payload);
      } catch (\Exception $e) {
        \Log::error('Could not post to airtable', $e);
      }

    }
    public  static function createNamePayload()
        {
            return array(
                'fields' => array(
                    'Name' => 'Amanda',
                    'Class' => 'Badass',
                    'Level' => 'Over 9000',
                    'Champion Points' => 'Well over 90000',
                    'Faction' => 'Whatever she wants!',
                    "Race" => 'YASSSSSSSS QUEEEEEEENNNNNN'
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
