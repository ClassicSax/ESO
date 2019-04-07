<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use App\Helpers\Airtable;

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
       $names = Airtable::createNamePayload();
       $records = Airtable::getNames('https://api.airtable.com/v0/appKvtKtRVT9J73tH/ESO?api_key=keyCYYwuTUNjUH1EC');
       \Log::info($records);
       $this->postNames($names);

    }
    private function postNames($names)
    {
      $payload = Airtable::createNamePayload();
      try {
        $res = Airtable::post('https://api.airtable.com/v0/appKvtKtRVT9J73tH/ESO?api_key=keyCYYwuTUNjUH1EC', $payload);
      } catch (\Exception $e) {
        \Log::error('Could not post to airtable', $e);
      }

    }

}
