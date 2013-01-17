<?php

/**
 * Image Utilities
 */
class ImageUtils extends CComponent
{
	/**
	 * Add a watermark to an image
	 * @param in_image
	 * @param watermark
	 * @param out_image
	 */

	public static function watermark($imagefile, $watermark, $out_imagefile)
	{
		// Load image
		$image = imagecreatefromjpeg($imagefile);
		$w = imagesx($image);
		$h = imagesy($image);

		// Load the watermark
		$wmark = imagecreatefrompng($watermark);
		$ww = imagesx($wmark);
		$wh = imagesy($wmark);

		// Insert watermark to the right bottom corner
		//imagecopy($image, $wmark, $w-$ww, $h-$wh, 0, 0, $ww, $wh);

		// ... or to the image center
		imagecopy($image, $wmark, (($w/2)-($ww/2)), (($h/2)-($wh/2)), 0, 0, $ww, $wh);

		// Send the image
		//header('Content-type: image/jpeg');
		imagejpeg($image, $out_imagefile, 95);  // 2nd param could be 'null' to emit the image as a stream
		imagedestroy($image);
	}
}
