<?php

use Illuminate\Database\Seeder;

class AnonymiLevelsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            ['id' => 1, 'name' => 'Transparent'],
            ['id' => 2, 'name' => 'Anonymous'],
            ['id' => 3, 'name' => 'Distorting'],
            ['id' => 4, 'name' => 'High Anonymous'],
        ];

        DB::table('anonymi_levels')->insert($data);
    }
}
