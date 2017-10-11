<?php

namespace App\Helpers;

class ImageHelper {

	public static function store_image($image)
	{
		\Cloudder::upload($image);
		$c=\Cloudder::getResult();

		return ['img_id' => $c['public_id'], 'img_url' => $c['url']];
	}

	public static function delete_image($img_id)
	{
		\Cloudder::destroyImage($img_id);
		\Cloudder::delete($img_id);
	}

	public static function update_image($image,$img_id)
	{

		self::delete_image($img_id);
		return self::store_image($image);
	}
}
