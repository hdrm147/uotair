<?php

use App\User;
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
//         $this->call(AirportsSeeder::class);
//         $this->call(AirportsSeeder::class);
        $admin = new User;
        $admin->name = "Haidar Mahmoud";
        $admin->password = \Illuminate\Support\Facades\Hash::make("admin");
        $admin->email = "me@hdrm.dev";
        $admin->save();
    }
}
