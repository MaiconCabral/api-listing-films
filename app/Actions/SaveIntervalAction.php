<?php

namespace App\Actions;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use App\Models\Document;
use App\Models\Films;
use \stdClass;

class SaveIntervalAction
{
    
    public function execute($movies)
    {

        $winners = $this->getWinners($movies);

        $producers = $this->searchWinners($winners); 
    
        $array = collect($producers)->sortBy('interval')->toArray();
        
        $response = [
            'min' => head($array) ?? [],
            'max' => last($array) ?? [],
        ];

        return $response;
    }

    public function getWinners($loops){
        $data = [];

            foreach($loops as $movie){
             
                $outputs = preg_split( '/(and|,)/', $movie->producers);
               
                foreach($outputs as $out){

                    $string = ltrim($out);
                    $pure =  rtrim($string);

                    $data[] = array(
                        "id" => $movie->id,
                        "year" => $movie->year,
                        "producers" => $pure,
                    );
                }
            }

            $sum = array_column($data, 'producers');
            $counts = array_count_values($sum);

            $produtosFiltrados = array_filter($counts, function($count) {
                return $count > 1;
            });

        return $produtosFiltrados;   
    }

    public function searchWinners($winners){
        $loops = [];
        
        foreach($winners as $key => $filter){
            $fields = Films::where('producers', 'LIKE', '%' . $key  . '%')->where('winner','yes')->get();
             array_push($loops, $fields);
        }

        $dados = [];

        foreach ($loops as $key => $dup) {
           $text = explode("and",$dup->first()->producers);
           $producersName = isset($text[1]) ? $text[1] : $text[0];
           $dados[] = array(
                   "producers" => ltrim($producersName),
                   "interval" => $dup->last()->year - $dup->first()->year,
                   "previousWin" => $dup->first()->year,
                   "followingWin" => $dup->last()->year,
               );
       }

        return $dados; 
    }
}

