<?php

/**
 * Image Utilities
 */
class ImageUtils extends CComponent
{
	/**
	 * Add a watermark to an image
	 * @param imageFile
	 * @param watermark (must be a png)
	 * @param outImageFile
	 */

	public static function watermark($imageFile, $watermarkPng, $outImageFile)
	{
		$image = _loadImage($imageFile);

		$w = imagesx($image);
		$h = imagesy($image);

		// Load the watermark
		$wmark = imagecreatefrompng($watermarkPng);
		$ww = imagesx($wmark);
		$wh = imagesy($wmark);

		// Insert watermark to the right bottom corner
		//imagecopy($image, $wmark, $w-$ww, $h-$wh, 0, 0, $ww, $wh);

		// ... or to the image center
		imagecopy($image, $wmark, (($w/2)-($ww/2)), (($h/2)-($wh/2)), 0, 0, $ww, $wh);

		// Send the image
		//header('Content-type: image/jpeg');
		imagejpeg($image, $outImageFile, 95);  // 2nd param could be 'null' to emit the image as a stream
		imagedestroy($image);
	}

	/**
	 * Load an image resource, figuring out its type
	 * @param image file
	 * @return image resource
	 */
	private static function _loadImage(&$imageFile)
	{
		list($source_width, $source_height, $source_type) = getimagesize($imageFile);

		switch ($source_type)
		{
			case IMAGETYPE_GIF:
				return imagecreatefromgif($imageFile);

			case IMAGETYPE_JPEG:
				return imagecreatefromjpeg($imageFile);

			case IMAGETYPE_PNG:
				return imagecreatefrompng($imageFile);
		}

	}
}
