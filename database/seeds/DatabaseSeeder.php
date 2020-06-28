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
        factory(\App\Address::class, 1000)->create();
        factory(\App\User::class,500)->create();
        factory(\App\Product::class,1500)->create();
        factory(\App\Image::class,3500)->create();

    }
}
