<?php

use Illuminate\Database\Seeder;

class ItemsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    	$url = "http://cancan.lt/picos-visos";
    	$html = file_get_contents( $url);
    	libxml_use_internal_errors( true);
    	$doc = new DOMDocument; $doc->loadHTML( $html);
    	$xpath = new DOMXpath( $doc);

    	$name = $xpath->query('//h2[@itemprop="name"]');
    	$content = $xpath->query( '//p[@itemprop="description"]');

    	$pizzaNames = array();
    	$pizzaContents = array();
    	$pizzas = array();

    	for ($i=0; $i < $name->length; $i++) 
    	{
    		$pizzaName = str_replace('pica','',$name->item($i)->nodeValue);
    		$pizzaName = str_replace('(aštri)','',$pizzaName);

    		$pizzaContent = str_replace('Alergenai','',$content->item($i)->nodeValue);

    		$pizzas[$pizzaName] = $pizzaContent;
    	}

    	foreach ($pizzas as $pizzaName => $pizzaContent) {
    		DB::table('items')->insert([
    			'title'=>$pizzaName,
    			'content'=>$pizzaContent,
    			'category_id'=>1
    			]);
    	}
    }
}
