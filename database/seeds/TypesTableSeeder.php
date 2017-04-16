<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TypesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            ['id' => 1, 'name' => 'HTTP'],
            ['id' => 2, 'name' => 'HTTPS'],
            ['id' => 3, 'name' => 'SOCKS4'],
            ['id' => 4, 'name' => 'SOCKS5'],
        ];

        DB::table('types')->insert($data);
    }
}
