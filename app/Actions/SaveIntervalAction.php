<?php

namespace App\Actions;

use Illuminate\Database\Eloquent\Model;
use App\Models\Document;

class SaveIntervalAction
{
    public function execute($loops)
    {
        $info = [];

        foreach ($loops as $key => $dup) {
        
            $info[] = array(
                    "producers" => $dup->first()->producers,
                    "interval" => $dup->last()->year - $dup->first()->year,
                    "previousWin" => $dup->first()->year,
                    "followingWin" => $dup->last()->year,
                );
        }
        
        $response = [
            'min' => $info[0] ?? [],
            'max' => $info[1] ?? [],
        ];

        return $response;
    }
}