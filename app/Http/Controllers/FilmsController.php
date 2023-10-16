<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Collection;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\DB;
use App\Models\Films;
use App\Imports\FilmsImport;
use App\Imports\MoviesImport;
use App\Actions\SaveIntervalAction;
use Illuminate\Contracts\Cache\LockTimeoutException;
use Illuminate\Support\Facades\Cache;
use stdClass;

class FilmsController extends Controller
{
    //
    public function all(){
       
        try{
            $movies = Films::all();

            $data = [];
            foreach($movies as $movie){
                $data[] = array(
                    "id" => $movie->id,
                    "year" => $movie->year,
                    "title" => $movie->title,
                    "studios" => $movie->studios,
                    "winner" => ($movie->winner == 'yes') ? $movie->winner : 'no',
                );
            }

            return response()->json($data, 200);
          
        } catch(\Exception $e) {
    
            return response()->json([
                'error_message' => $e->getMessage(),
            ], 500);
         }

    }
    
    public function YearsWithWinner(){
       
        try{
           
            $movies = DB::table('films')
                 ->select('year', DB::raw('count(*) as total'))
                 ->where('winner','yes')
                 ->groupBy('year')
                 ->orderBy('total', 'Desc')
                 ->limit(5)
                 ->get();

            $moviesList = [
                'year' => $movies,
            ];

            return response()->json($moviesList, 200);
          
        } catch(\Exception $e) {
    
            return response()->json([
                'error_message' => $e->getMessage(),
            ], 500);
         }

    }
    
    public function byStudios(){
       
        try{
         
            $movies = DB::table('films')
                 ->select('studios', DB::raw('count(*) as total'))
                 ->where('winner','yes')
                 ->groupBy('studios')
                 ->orderBy('total', 'Desc')
                 ->limit(3)
                 ->get();

            $moviesList = [
                'studios' => $movies,
            ];

            return response()->json($moviesList, 200);
          
        } catch(\Exception $e) {
    
            return response()->json([
                'error_message' => $e->getMessage(),
            ], 500);
         }

    }

    public function byYear(Request $request){
       
        try{
            
            if ($request->has('year')) {
                $years = $request->input('year');
            }
            if ($request->has('winner')) {
                $winners = ($request->input('winner') == 'true') ? 'yes' : 'no' ;
            }
                
            $movies = Films::where('winner', $winners )->where('year',$years )->get();

            return response()->json($movies, 200);
          
        } catch(\Exception $e) {
    
            return response()->json([
                'error_message' => $e->getMessage(),
            ], 500);
         }

    }

    public function byInterval(){
        try{
            
            $movies = Films::where('winner','yes')->get();
            
            $groupedByValue = $movies->groupBy('producers');
            $dupes = $groupedByValue->filter(function ($groups) {
                return $groups->count() > 1;
            });

            $intervalAction = new SaveIntervalAction;
            $result = $intervalAction->execute($dupes);
            
            return response()->json($result, 200);

        } catch(\Exception $e) {
    
        return response()->json([
            'error_message' => $e->getMessage(),
        ], 500);
     }
    }

    public function importCache(Request $request){

        try{
            //$data = Films::all();

            // Cache::putMany($films, 20);
            // Cache::put('movie', $films);

            // $movies = Cache::get('movie');
            $data = Excel::toArray( new MoviesImport(), $request->file('file')->store('temp'));
            $films = json_encode($data);
            Cache::put('movie', $data);
            $movies = Cache::get('movie');
          

            return response()->json($movies[0], 200);
        } catch(\Exception $e) {
    
            return response()->json([
                'error_message' => $e->getMessage(),
            ], 500);
         }

    }

    public function importModel(Request $request){
        try{
            Excel::import(new FilmsImport, $request->file('file')->store('temp'));

            return response()->json([
                'success_message' => 'Importação realizada com sucesso',
            ], 200);
        } catch(\Exception $e) {
    
            return response()->json([
                'error_message' => $e->getMessage(),
            ], 500);
         }
    }
}
