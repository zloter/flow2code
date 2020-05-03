<?php

use Illuminate\Database\Seeder;

class CountrySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $countries = [
            ["name" => "USA"],
            ["name" => "Canada"],
            ["name" => "Poland"],
            ["name" => "French"],
            ["name" => "Germany"]
        ];
        foreach ($countries as $country) {
            DB::table('countries')->insert($country);
        }
    }
}
