<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\Films;
use App\Imports\FilmsImport;
use App\Imports\MoviesImport;
use File;

class importCsv extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:csv';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Importar arquivo excel ao iniciar projeto';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {

        try{
            $movies = [];

            if (($open = fopen(public_path() . "/movielist.csv", "r")) !== FALSE) {
    
                while (($data = fgetcsv($open, 1000, ";")) !== FALSE) {
                    $movies[] = $data;
                }
    
                fclose($open);
            }

            foreach($movies as $row){

                Films::create([
                    'year'      => $row[0],
                    'title'     => $row[1],
                    'studios'     => $row[2],
                    'producers'    => $row[3],
                    'winner' => $row[4]
                ]);
            }

            return 'Importação realizada com sucesso';
            
        } catch(\Exception $e) {
    
            return $e->getMessage() ;
         }
        // return Command::SUCCESS;
    }
}
