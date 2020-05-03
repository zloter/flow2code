<?php

use Illuminate\Database\Seeder;

class GenreSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $genres = [
            ["name" => "horror"],
            ["name" => "dramat"],
            ["name" => "comedy"],
            ["name" => "sci-fi"]
        ];
        foreach ($genres as $genre) {
            DB::table('genres')->insert($genre);
        }
    }
}
