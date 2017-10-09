<?php

use Illuminate\Database\Seeder;

class ComplexesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $complexes = [
    		'Dienos Pietus',
    		'Specialus Menu',
    		'V.I.P'
    		];

    	foreach ($complexes as $complex) {
    		DB::table('complexes')->insert([
    			'title'=>$complex
    			]);
    	}
    }
}
