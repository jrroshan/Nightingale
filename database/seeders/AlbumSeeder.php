<?php

namespace Database\Seeders;

use App\Models\Album\Album;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AlbumSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Album::create(['name'=>'Unknown Album', 'artist_id'=> '1', 'cover' => ' ',]);
    }
}
