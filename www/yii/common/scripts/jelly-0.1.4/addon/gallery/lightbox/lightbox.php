<?php

/**
 * API for Lightbox2
 *
 * Notes
 * -----
 * None
 */

class lightbox
{
	//Defaults
	private $width = 'auto';
	private $height = 'auto';
	private $gallery = "";
	private $image = "";
	private $thumb = "";

	public $apiOption = array(
	);

	/*
	 * Set up any code pre-requisites (onload/document-ready reqs)
	 * Apply options
	 * Return an array containing [0]localContent [1]globalContent
	 */
	public function init($options, $jellyRootUrl)
	{
//		var_dump( $options );

		// Generate the content into the html, replacing any <substituteN> tags
		$content = "";
		foreach ($options as $opt => $val)
		{
			switch ($opt)
			{
				// Single image options - nor really a gallery
				case "image":
					if (strlen($val) > 0)
						$this->image = $val;
					break;
				case "thumb":
					if (strlen($val) > 0)
						$this->thumb = $val;
					break;
				case "width":						//
					$width = str_replace("px", "", $val) . "px";
					$this->width = $val + "px";
					break;
				case "height":
					$height = str_replace("px", "", $val) . "px";
					$this->height = $val + "px";
					break;

				// Gallery options
				case "gallery":
					$val = str_replace("&nbsp;", " ", $val);
					$val = str_replace("&nbsp", " ", $val);
					$this->gallery = strstr($val, " ", true);
					break;

				default:
					// Not all array items are action items
			}
		}

/******
		<div class="image-row">
			<div class="image-set">
				<a class="example-image-link" href="img/demopage/image-3.jpg" data-lightbox="example-set" title="Click on the right side of the image to move forward."><img class="example-image" src="img/demopage/thumb-3.jpg" alt="Plants: image 1 0f 4 thumb" width="150" height="150"/></a>
				<a class="example-image-link" href="img/demopage/image-4.jpg" data-lightbox="example-set" title="Or press the right arrow on your keyboard."><img class="example-image" src="img/demopage/thumb-4.jpg" alt="Plants: image 2 0f 4 thumb" width="150" height="150"/></a>
				<a class="example-image-link" href="img/demopage/image-5.jpg" data-lightbox="example-set" title="The script preloads the next image in the set as you're viewing."><img class="example-image" src="img/demopage/thumb-5.jpg" alt="Plants: image 3 0f 4 thumb" width="150" height="150"/></a>
				<a class="example-image-link" href="img/demopage/image-6.jpg" data-lightbox="example-set" title="Click anywhere outside the image or the X to the right to close."><img class="example-image" src="img/demopage/thumb-6.jpg" alt="Plants: image 4 0f 4 thumb" width="150" height="150"/></a>
			</div>
		</div>
******/

		if ($this->image != "")
		{
			$content .= "<a class='example-image-link' href='" . $this->image . "' data-lightbox='example-set' title=''><img class='example-image' src='" . $this->thumb . "' alt='' width='" . $this->width . "' height='" . $this->height . "'/></a>";
		}
		else
		{
			$galleries = JellyGallery::model()->findAll(array('order'=>'sequence'));
			foreach ($galleries as $gallery):
				if (($gallery->active == 0) && (strlen($this->gallery) == 0))
					continue;
				if (strlen($this->gallery) > 0)
				{
					if ($gallery->id != $this->gallery)
						continue;
				}
				$criteria = new CDbCriteria;
				$criteria->addCondition("jelly_gallery_id = " . $gallery->id);
				$criteria->order = "sequence ASC";
				$galleryImages = JellyGalleryImage::model()->findAll($criteria);
				$content .= "<div class='image-row'>";
				$content .= "<div class='image-set'>";
				foreach ($galleryImages as $galleryImage):
					//$content .= '<a class="example-image-link" href="' . Yii::app()->getBaseUrl(true) . "/userdata/jelly/gallery/" . $galleryImage->image . '" data-lightbox="gallery-' . $gallery->id . '" title="' . $galleryImage->text . '"><img class="example-image" src="' . Yii::app()->getBaseUrl(true) . "/userdata/jelly/gallery/thumb_" . $galleryImage->image . '" alt="' . $galleryImage->text . '" width="100" height="100"/></a>';

					$content .= '<a style="padding:5px 5px 0px 0px" class="example-image-link" href="' . Yii::app()->getBaseUrl(true) . "/userdata/jelly/gallery/" . $galleryImage->image . '" data-lightbox="gallery-' . $gallery->id . '" title="' . $galleryImage->text . '"><img class="example-image" src="' . Yii::app()->getBaseUrl(true) . "/userdata/jelly/gallery/thumb_" . $galleryImage->image . '" alt="' . $galleryImage->text . '" width="100" height="100"/></a>';

					$content .= '</a>';
				endforeach;
				$content .= "</div>";
				$content .= "</div>";
			endforeach;
		}

		// Apply all defaults that werent overridden

		// HTML

		// JS


		$this->apiHtml = str_replace("<substitute-path>", $jellyRootUrl, $this->apiHtml);
		$this->apiHtml = str_replace("<substitute-data>", $content, $this->apiHtml);

		// 0 is inlineHtml
		// 1 is inline JS
		// 2 is clipboard
		// 3 is header html
		$retArr = array();
		$retArr[0] = $this->apiHtml;
		$retArr[1] = $this->apiJs;
		$retArr[2] = "";	// Clipboard
//		$retArr[3] = "<script src=$jellyRootUrl/js/lightbox-2.6.js></script>";
//		$retArr[3] .= "<link href=$jellyRootUrl/css/lightbox.css rel='stylesheet' />";
		return $retArr;
	}

	private $apiHtml = <<<END_OF_API_HTML

<style>
#element {
    z-index: 12000;
}
</style>

		<div id="jelly-lightbox2-container">
			<!--Lightbox2-->

<!--			<script src="<substitute-path>/js/modernizr.custom.js"></script> -->
			<script src="<substitute-path>/js/lightbox-2.6.js"></script>
<!--			<link rel="stylesheet" href="http://fonts.googleapis.com/css?family=Karla:400,700"> -->
<!--			<link rel="stylesheet" href="<substitute-path>/css/screen.css" media="screen"/> -->
			<link rel="stylesheet" href="<substitute-path>/css/lightbox.css" media="screen"/>

			<substitute-data>

		</div>

END_OF_API_HTML;

	private $apiJs = <<<END_OF_API_JS

	jQuery(document).ready(function($){

		// Disable right click
		$(document).on({
			"contextmenu": function(e) {
				console.log("ctx menu button:", e.which);
				e.preventDefault();				// Stop the context menu
			},
			"mousedown": function(e) {
				console.log("normal mouse down:", e.which);
			},
			"mouseup": function(e) {
				console.log("normal mouse up:", e.which);
			}
		});

	});

END_OF_API_JS;

}
?>
