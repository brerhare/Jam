<?php

/**
 * API for Hoverzoom
 *
 * Notes
 * -----
 * None
 */

class hoverzoom
{
	//Defaults
	private $gallery = "";

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
				case "gallery":
					if (strlen($val) > 0)
						$this->gallery = $val;
					break;
				default:
					// Not all array items are action items
			}
		}


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
			$content .= "<ul class='thumb'>";
			foreach ($galleryImages as $galleryImage):

				$content .= "<li><a href='" . Yii::app()->getBaseUrl(true) . "/userdata/jelly/gallery/" . $galleryImage->image . "' /><img src='" . Yii::app()->getBaseUrl(true) . "/userdata/jelly/gallery/thumb_" . $galleryImage->image . "' alt='' /> </a></li>";

			endforeach;
			$content .= "</ul>";
			$content .= "<br clear='all'/><br/>";
		endforeach;


		// Apply all defaults that werent overridden
		// HTML


		// none
		// JS


		$this->apiHtml = str_replace("<substitute-path>", $jellyRootUrl, $this->apiHtml);
		$this->apiHtml = str_replace("<substitute-data>", $content, $this->apiHtml);

		$retArr = array();
		$retArr[0] = $this->apiHtml;
		$retArr[1] = $this->apiJs;
		return $retArr;
	}

	private $apiHtml = <<<END_OF_API_HTML

<style>
#element {
    z-index: 12000;
}
</style>

		<div id="jelly-hoverzoom-container">
			<!--HoverZoom-->

			<script src="<substitute-path>/jquery.hoverZoom.js"></script>
			<link rel="stylesheet" href="<substitute-path>/jquery.hoverZoom.css">

			<style>
				.item:hover {
				opacity:0.9;
				}
			</style>

			<substitute-data>

		</div>

END_OF_API_HTML;

	private $apiJs = <<<END_OF_API_JS

	jQuery(document).ready(function($){
	});

	$(document).ready(function(){
	/*  $('.thumb img').hoverZoom();    */
		$('.thumb img').hoverZoom({speedView:600, speedRemove:400, showCaption:true, speedCaption:600, debug:true, hoverIntent: true, loadingIndicatorPos: 'center'});
    });


$(document).ready(function() {
});



END_OF_API_JS;

}
?>
