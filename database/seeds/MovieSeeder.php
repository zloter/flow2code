<?php

use Illuminate\Database\Seeder;

class MovieSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $movies = [
            [
                'title' => "Gran Torino",
                'description' => "Gran Torino to film Clinta Eastwood.",
                'country_id' => 2,
                "image_id" => 1
            ],
            [
                'title' => "Jeszcze dalej niż północ",
                'description' => "To francuska komedia",
                'country_id' => 4,
                "image_id" => 1
            ]
        ];
        $relations = [
            [
                "movie_id" => 1,
                "genre_id" => 2
            ],
            [
                "movie_id" => 2,
                "genre_id" => 2
            ],
            [
                "movie_id" => 2,
                "genre_id" => 3
            ]
        ];
        foreach ($movies as $movie) {
            DB::table('movies')->insert($movie);
        }
        foreach ($relations as $relation) {
            DB::table('genre_movie')->insert($relation);
        }
    }
}
