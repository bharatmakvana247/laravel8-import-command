<?php
namespace App\Console\Commands;
use Illuminate\Console\Command;
use Carbon\Carbon;
use App\Models\Products;
use Illuminate\Support\Facades\Storage;

class ImportProduct extends Command{
/**
 * * The name and signature of the console command.
 * *
 * * @var string
 * */
protected $signature = 'import:product';
/**
 * * The console command description.
 * *
 * * @var string
 * */
protected $description = 'Import product details from CSV file.';
/**
 * * Create a new command instance.
 * *
 * * @return void
 * */
public function __construct(){
    parent::__construct();
}
/**
 * * Execute the console command.
 * *
 * * @return int
 * */
public function handle(){
    $path = "storage/product.csv";
    $customerArr = $this->csvToArray($path);
    if(sizeof($customerArr) > 0){
        foreach($customerArr as $data){
        $student_arr1 = array_unique($customerArr,SORT_REGULAR);    
        }

        $columns = array('make', 'model', 'colour', 'capacity', 'network', 'grade', 'condition');
        $file = fopen('storage/export.csv', 'w');
        fputcsv($file, $columns);
        foreach ($student_arr1 as $row) {
           fputcsv($file, $row);
        }
        fclose($file);
        print_r($student_arr1);
    }
}
function csvToArray($filename = '', $delimiter = ','){
    if (!file_exists($filename) || !is_readable($filename))
        return false;
    $header = null; //Key
    $data = array();
    if (($handle = fopen($filename, 'r')) !== false){
        while (($row = fgetcsv($handle, 1000, $delimiter)) !== false){
            if (!$header)
                $header = $row;
            else
                $data[] = array_combine($header, $row);

        }
        fclose($handle);
    }
    return $data;
}
}