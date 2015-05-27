<?php

/**
 * API for Fancybox
 *
 * Notes
 * -----
 * None
 */

class fancybox
{
	//Defaults
	private $defaultSomething = 'someval';
	private $gallery = "";
	private $thumbnails = "0";

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
		$galleryId = 0;
		foreach ($options as $opt => $val)
		{
			switch ($opt)
			{
				case "gallery":
					if (strlen($val) > 0)
						$this->gallery = $val;
					break;
				case "thumbnails":
					$this->thumbnails = $val;
					if ($this->thumbnails == "0")	// ----- Gallery view
					{
						$content .= "<table style='margin-left:50px'>";
						$galleries = JellyGallery::model()->findAll(array('order'=>'sequence'));
						foreach ($galleries as $gallery):
							if (($gallery->active == 0) && (strlen($this->gallery) == 0))
								continue;
							if (strlen($this->gallery) > 0)
							{
								if ($gallery->id != $this->gallery)
									continue;
							}
							$galleryId++;
							$content .= "<tr>";
							$content .= "<td width='25%'>";
							$content .= "<a class='fancybox' rel='gallery" . $galleryId . "' href='" . Yii::app()->getBaseUrl(true) . "/userdata/jelly/gallery/" . $gallery->image . "' title='" . $gallery->text . "'> <img src='" . Yii::app()->getBaseUrl(true) . "/userdata/jelly/gallery/thumb_" . $gallery->image . "' alt='' /> </a>";
							$criteria = new CDbCriteria;
							$criteria->addCondition("jelly_gallery_id = " . $gallery->id);
							$criteria->order = "sequence";
							$galleryImages = JellyGalleryImage::model()->findAll($criteria);
							foreach ($galleryImages as $galleryImage):
								$content .= "<a style='display:none' class='fancybox' rel='gallery" . $galleryId . "' href='" . Yii::app()->getBaseUrl(true) . "/userdata/jelly/gallery/" . $galleryImage->image . "' title='" . $galleryImage->text . "'> <img src='" . Yii::app()->getBaseUrl(true) . "/userdata/jelly/gallery/thumb_" . $galleryImage->image . "' alt='' /> </a>";
							endforeach;
							$content .= "</td>";
							$content .= "<td width='1%'></td>";
							$content .= "<td width='74%'>";
							$content .= "<b>" . $gallery->title . "</b><br>" . $gallery->text;
							$content .= "</td></tr>";
						endforeach;
						$content .= "</table>";
					}
					else	// ----- Thumbnail view
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
							$content .= "<div style='margin-left:50px'>";
							foreach ($galleryImages as $galleryImage):
//echo "<br/>->" . Yii::app()->getBaseUrl(true) . "<-<br/>";
								$content .= '<a class="fancybox item" rel="gallery1" href="' . Yii::app()->getBaseUrl(true) . "/userdata/jelly/gallery/" . $galleryImage->image . '" title="' . $galleryImage->text . '">';
								$content .= '<img style="padding:5px" src="' . Yii::app()->getBaseUrl(true) . "/userdata/jelly/gallery/thumb_" . $galleryImage->image . '" alt="" />';
								$content .= '</a>';
							endforeach;
							$content .= "</div>";
						endforeach;
					}
				default:
			}								// END switch (opt)
		}									// END foreach ($options as $opt => $val)

		// Apply all defaults that werent overridden
		// HTML
		if (strstr($this->apiHtml, "<substitute-something>"))
			$this->apiHtml = str_replace("<substitute-something>", $this->defaultSomething, $this->apiHtml);

		// JS
		if (strstr($this->apiJs, "<substitute-animation>"))
		{
			$tmp = str_replace("<substitute-animation>", "'" . $this->defaultAnimation . "'", $this->apiJs);
			$this->apiJs = $tmp;
		}

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

		<div id="jelly-fancybox-container">
			<!--Fancybox-->

			<!-- Add mousewheel plugin (this is optional) -->
			<script type="text/javascript" src="<substitute-path>/lib/jquery.mousewheel-3.0.6.pack.js"></script>

			<!-- Add fancyBox -->
			<link rel="stylesheet" href="<substitute-path>/source/jquery.fancybox.css?v=2.1.5" type="text/css" media="screen" />
			<script type="text/javascript" src="<substitute-path>/source/jquery.fancybox.pack.js?v=2.1.5"></script>

			<!-- Optionally add helpers - button, thumbnail and/or media -->
			<link rel="stylesheet" href="<substitute-path>/source/helpers/jquery.fancybox-buttons.css?v=1.0.5" type="text/css" media="screen" />
			<script type="text/javascript" src="<substitute-path>//source/helpers/jquery.fancybox-buttons.js?v=1.0.5"></script>
			<script type="text/javascript" src="<substitute-path>/source/helpers/jquery.fancybox-media.js?v=1.0.6"></script>

			<link rel="stylesheet" href="<substitute-path>/source/helpers/jquery.fancybox-thumbs.css?v=1.0.7" type="text/css" media="screen" />
			<script type="text/javascript" src="<substitute-path>/source/helpers/jquery.fancybox-thumbs.js?v=1.0.7"></script>

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

/*****
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
*****/

	$(document).ready(function() {
		$(".fancybox").fancybox({
    		helpers:  {
        		thumbs : {
            		width: 50,
            		height: 50
        		}
    		}
		});
	});

END_OF_API_JS;

}
?>
