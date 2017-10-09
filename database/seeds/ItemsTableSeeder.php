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
    	$url = "http://cancan.lt/sriubos";
    	$html = file_get_contents( $url);
    	libxml_use_internal_errors( true);
    	$doc = new DOMDocument; $doc->loadHTML( $html);
    	$xpath = new DOMXpath( $doc);

    	$name = $xpath->query('//h2[@itemprop="name"]');
    	$content = $xpath->query( '//p[@itemprop="description"]');
    	$images = $xpath->query('//img[@itemprop="image"]');

    	for ($i=1; $i < $name->length; $i++) 
    	{
    		$pizzaName = str_replace('pica','',$name->item($i)->nodeValue);
    		$pizzaName = str_replace('(aštri)','',$pizzaName);

    		$pizzaContent = str_replace('Alergenai','',$content->item($i)->nodeValue);

    		// $pizzas[$pizzaName] = $pizzaContent;

    		$item_id = DB::table('items')->insertGetId([
    			'title'=>$pizzaName,
    			'content'=>$pizzaContent,
    			'category_id'=>8
    		]);

    		$imageString = file_get_contents($images->item($i)->getAttribute('src'));
    		$save = file_put_contents(public_path().'/images/items/item_id_'. $item_id .'.png',$imageString);
    	}
    }
}
