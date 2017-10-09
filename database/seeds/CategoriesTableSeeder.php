<?php

use Illuminate\Database\Seeder;

class CategoriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $categories = [
    		'Pizza',
    		'Soup',
    		'Snacks',
    		'Salads',
    		'Main',
    		'Grill',
    		'Deserts',
    		'Drinks',
    		];

    	foreach ($categories as $category) {
    		DB::table('categories')->insert([
    			'title'=>$category
    			]);
    	}
    }
}
