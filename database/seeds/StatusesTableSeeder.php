<?php

use Illuminate\Database\Seeder;

class StatusesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            ['id' => 1, 'name' => 'FALSE'],
            ['id' => 2, 'name' => 'TRUE'],
            ['id' => 3, 'name' => 'NEW']
        ];

        DB::table('statuses')->insert($data);
    }
}
