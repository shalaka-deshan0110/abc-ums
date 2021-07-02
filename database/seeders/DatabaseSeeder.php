<?php

namespace Database\Seeders;


use Illuminate\Database\Eloquent\Model;
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
        Model::unguard();

        $this->call(RolesAndPermissionsSeeder::class);

        /*
        |-------------------------------------------------------------------------------
        | Add production-safe seeders here. DO NOT ADD HERE IF IT ALTERS EXISTING DATA
        |-------------------------------------------------------------------------------
        */

        Model::reguard();


    }
}
