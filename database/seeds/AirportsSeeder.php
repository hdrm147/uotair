<?php

use Illuminate\Database\Seeder;

class AirportsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = file_get_contents(__DIR__ . "/../../airports.json");
        $json = json_decode($data);
        $collection = collect($json);

        $grouped = $collection->groupBy("country")->transform(function ($country) {
            return $country->groupBy("city");
        });

        foreach ($grouped as $country => $city) {
            $country = \App\Country::create([
                "name" => $country
            ]);
            foreach ($city as $city => $airports) {
                $city = $country->cities()->create([
                    "name" => $city,
                ]);
                foreach($airports as $airport) {
                    $city->airports()->create([
                       "code"=>$airport->code,
                        "timezone"=>$airport->tz,
                        "latitude"=>$airport->lat,
                        "longitude"=>$airport->lon,
                        "name"=>$airport->name,
                        "country_id" => $country->id
                    ]);
                }
            }
        }
    }
}
