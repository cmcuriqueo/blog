<?php

use Illuminate\Database\Seeder;
use App\Tag;

class TagsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    	Tag::create([
            'name' => 'Otros',
            'user_id' => 2,
        ]);
    }
}
