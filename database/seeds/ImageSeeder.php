<?php

use Illuminate\Database\Seeder;

class ImageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $images = [
            ["path" => "/movies/posters/gran_torino.jpeg"],
            ["path" => "/movies/posters/jeszcze_dalej.jpg"],
        ];
        foreach ($images as $image) {
            DB::table('images')->insert($image);
        }
    }
}
