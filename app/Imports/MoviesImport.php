<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;

class MoviesImport implements ToCollection
{
    /**
    * @param Collection $collection
    */
    public function collection(Collection $collection)
    {
        //
        $data = [];
        
        
            $data[] = array(
                'year'      => $collection[0],
                'title'     => $collection[1],
                'studios'     => $collection[2],
                'producers'    => $collection[3],
                'winner' => $collection[4]
            );

        return $data;
    }
}
