<?php

namespace App\Imports;

use App\Models\Films;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class FilmsImport implements ToModel
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        $movies = Films::where('title', '=', $row[1])->first();

        if(!$movies){
        
            return new Films([
                'year'      => $row[0],
                'title'     => $row[1],
                'studios'     => $row[2],
                'producers'    => $row[3],
                'winner' => $row[4]
            ]);
        }

    }
}
