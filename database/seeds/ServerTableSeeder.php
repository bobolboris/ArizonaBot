<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ServerTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('servers')->insert([
            ['name' => 'Phoenix', 'number' => 1],
            ['name' => 'Tucson', 'number' => 2],
            ['name' => 'Scottdale', 'number' => 3],
            ['name' => 'Chandler', 'number' => 4],
            ['name' => 'Brainburg', 'number' => 5],
            ['name' => 'Saint Rose', 'number' => 6],
            ['name' => 'Mesa', 'number' => 7],
            ['name' => 'Red-Rock', 'number' => 8],
        ]);
    }
}
