<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(CountrySeeder::class);
        $this->call(GenreSeeder::class);
        $this->call(ImageSeeder::class);
        $this->call(MovieSeeder::class);
    }
}
