<?php

namespace Database\Seeders;

use App\Models\Artist\Artist;
use Illuminate\Database\Seeder;

class ArtistSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $datas = [
            [
                'name' => 'Unknown Artist'
            ],
            [
                'name' => 'Various Artists'
            ]
        ];

        foreach ($datas as $data){
            Artist::create($data);
        }
    }
}
