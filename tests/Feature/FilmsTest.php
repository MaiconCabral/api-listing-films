<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Tests\Feature\Response;
use App\Models\Films;

class FilmsTest extends TestCase
{
    use DatabaseMigrations;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testFillable()
    {
        $films = new Films;

        $expected = [
            'year',
            'title',
            'studios',
            'producers',
            'winner',
        ];

        $arrayCompared = array_diff($expected, $films->getFillable());
        $this->assertEquals(0, count($arrayCompared));
    }

    public function testFilmsList(){

        $movies = factory(Films::class)->create();
        $this->assertCount(1, Films::all());
        $fields = array_keys($movies->getAttributes());
        $this->assertEqualsCanonicalizing([
            'id',
            'year',
            'title',
            'studios',
            'producers',
            'winner',
            'created_at',
            'updated_at'
        ], $fields);
    }

    public function testFilmsInterval(){
        
        Films::insert([
                [
                    "year" => "1990",
                    "title" => "Dirty Grandpa",
                    "producers" => "Bill Block",
                    "studios" => "Lionsgate",
                    "winner" => "yes",
                ],
                [
                    "year" => "2016",
                    "title" => "Gods of Egypt",
                    "producers" => "Alex Proyas",
                    "studios" => "Summit Entertainment",
                    "winner" => "yes",
                ],
                [
                    "year" => "2016",
                    "title" => "Independence Day => Resurgence",
                    "producers" => "Bill Block",
                    "studios" => "20th Century Fox",
                    "winner" => "yes",
                ],
                [
                    "year" => "2016",
                    "title" => "Zoolander 2",
                    "producers" => "Stuart Cornfeld, Scott Rudin, Ben Stiller and Clayton Townsend",
                    "studios" => "Paramount Pictures",
                    "winner" => "no",
                ],
                [
                    "year" => "2017",
                    "title" => "The Emoji Movie",
                    "producers" => "Alex Proyas",
                    "studios" => "Columbia Pictures",
                    "winner" => "yes",
                ]
        ]);


        $response = $this->getJson('/api/films/filter/interval');
        // dd(json_decode($response->getContent()));
        $response->assertStatus(200)->getContent();
               
    }
}
