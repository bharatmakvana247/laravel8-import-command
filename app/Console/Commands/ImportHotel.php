<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Carbon\Carbon;
use App\Models\Hotels;
use Illuminate\Support\Facades\Storage;

class ImportHotel extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:hotels';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import hotel details from CSV file.';

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
     * @return int
     */
    public function handle()
    {
        $path = "storage/hotels.csv";
        $csv_data = array_map(function($v){return str_getcsv($v, ";");}, file($path));
        $error = false;
        if(count($csv_data) > 0) {
            $i = 0;
            foreach ($csv_data as $data) {
                if($i == 0){
                    $i++;
                    continue;
                }
                if (is_null($data[0]) || $data[0] == '') {
                    echo "The value is empty for column 'name' in row ", ($i + 1);
                    $error = true;
                }
                if (is_null($data[2])) {
                    echo "The value is empty for column 'city' in row ", ($i + 1);
                    $error = true;
                }
                if (is_null($data[3])) {
                    echo "The value is empty for column 'address' in row ", ($i + 1);
                    $error = true;
                }
                $i++;
            }
        }
        if (!$error) {
            if(count($csv_data) > 0) {
                $i = 0;
                foreach ($csv_data as $data) {
                    if($i == 0){
                        $i++;
                        continue;
                    }
                    $filename = null;
                    if ($data[1] != '' && !is_null($data[1])) {
                        $contents = file_get_contents($data[1]);
                        $filename = Carbon::now()->format('YmdHis').'_'.substr($data[1], strrpos($data[1], '/') + 1);
                        Storage::disk('public')->put('images/'.'/'.$filename, file_get_contents($data[1]));
                    }
                    Hotels::create(
                        array(
                            'name' => $data[0],
                            'image' => $filename,
                            'city' => $data[2],
                            'address' => $data[3],
                            'description' => $data[4],
                            'stars' => $data[5],
                            'latitude' => $data[6],
                            'longitude' => $data[7],
                        ));
                        
                    $i++;
                }
                echo "Hotels imported successfully.";
            }
        }
    }
}
