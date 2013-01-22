<?php

/**
 * Image Utilities
 */
class ImageUtils extends CComponent
{
	/**
	 * Crop an image to fit centrally into specified dimensions
	 * @param imageFile
	 * @param outImageFile
	 * @param outWidth
	 * @param outHeight
	 */

	public static function cropToFit($imageFile, $outImageFile, $outWidth, $outHeight)
	{
		list($source_width, $source_height, $source_type) = getimagesize($imageFile);
		switch ( $source_type )
		{
			case IMAGETYPE_GIF:
				$source_gdim = imagecreatefromgif($imageFile);
				break;
			case IMAGETYPE_JPEG:
				$source_gdim = imagecreatefromjpeg($imageFile);
				break;
			case IMAGETYPE_PNG:
				$source_gdim = imagecreatefrompng($imageFile);
				break;
		}

		$source_aspect_ratio = $source_width / $source_height;
		$desired_aspect_ratio = $outWidth / $outHeight;

		if ($source_aspect_ratio > $desired_aspect_ratio)
		{
			//
			// Triggered when source image is wider
			//
			$temp_height = $outHeight;
			$temp_width = (int) ($outHeight * $source_aspect_ratio);
		}
		else
		{
			//
			// Triggered otherwise (i.e. source image is similar or taller)
			//
			$temp_width = $outWidth;
			$temp_height = (int) ($outWidth / $source_aspect_ratio);
		}

		//
		// Resize the image into a temporary GD image
		//

		$temp_gdim = imagecreatetruecolor($temp_width, $temp_height);

		// The following 2 lines fix transparent png's coming out with black backgrounds
		imagealphablending($temp_gdim, false);
		imagesavealpha($temp_gdim, true);

		$res = imagecopyresampled(
			$temp_gdim,
			$source_gdim,
			0, 0,
			0, 0,
			$temp_width, $temp_height,
			$source_width, $source_height
		);

		//
		// Copy cropped region from temporary image into the desired GD image
		//

		$x0 = ($temp_width - $outWidth) / 2;
		$y0 = ($temp_height - $outHeight) / 2;

		$desired_gdim = imagecreatetruecolor($outWidth, $outHeight);

		// The following 2 lines fix transparent png's coming out with black backgrounds
		imagealphablending($desired_gdim, false);
		$res = imagesavealpha($desired_gdim, true);

		imagecopy(
			$desired_gdim,
			$temp_gdim,
			0, 0,
			$x0, $y0,
			$outWidth, $outHeight
		);

		imagejpeg($desired_gdim, $outImageFile, 90);
		imagedestroy($desired_gdim);
	}


	/**
	 * Add a watermark to an image
	 * @param imageFile
	 * @param watermark (must be a png)
	 * @param outImageFile
	 */

	public static function watermark($imageFile, $watermarkPng, $outImageFile)
	{
		list($source_width, $source_height, $source_type) = getimagesize($imageFile);
		switch ($source_type)
		{
			case IMAGETYPE_GIF:
				$source_gdim = imagecreatefromgif($imageFile);
				break;
			case IMAGETYPE_JPEG:
				$source_gdim = imagecreatefromjpeg($imageFile);
				break;
			case IMAGETYPE_PNG:
				$source_gdim = imagecreatefrompng($imageFile);
				break;
		}

		$w = imagesx($source_gdim);
		$h = imagesy($source_gdim);

		// Load the watermark
		$wmark = imagecreatefrompng($watermarkPng);
		$ww = imagesx($wmark);
		$wh = imagesy($wmark);

		// Insert watermark to the right bottom corner
		//imagecopy($image, $wmark, $w-$ww, $h-$wh, 0, 0, $ww, $wh);

		// ... or to the image center
		imagecopy($source_gdim, $wmark, (($w/2)-($ww/2)), (($h/2)-($wh/2)), 0, 0, $ww, $wh);

		// Send the image
		//header('Content-type: image/jpeg');
		imagejpeg($source_gdim, $outImageFile, 95);  // 2nd param could be 'null' to emit the image as a stream
		imagedestroy($source_gdim);
	}

}