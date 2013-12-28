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
                case "source":
					if ($val == "db")
					{
						// If db based content
						$content .= "<table>";
						$galleries = JellyGallery::model()->findAll(array('order'=>'sequence'));
						foreach ($galleries as $gallery):
							$galleryId++;
							$content .= "<tr>";
							$content .= "<td width='25%'>";

							$criteria = new CDbCriteria;
							$criteria->addCondition("jelly_gallery_id = " . $gallery->id);
							$galleryImages = JellyGalleryImage::model()->findAll($criteria);
							$firstImage = 1;
							foreach ($galleryImages as $galleryImage):
								$thumb = $galleryImage->image;
								$style = "display:none";
								if ($firstImage == 1)
								{
									$firstImage = 0;
									$thumb = $gallery->image;
									$style = "";
								}
								$content .= "<a style='" . $style . "' class='fancybox' rel='gallery" . $galleryId . "' href='" . Yii::app()->baseUrl . "/userdata/jelly/gallery/" . $galleryImage->image . "' title='" . $galleryImage->text . "'> <img src='" . Yii::app()->baseUrl . "/userdata/jelly/gallery/thumb_" . $thumb . "' alt='' /> </a>";
							endforeach;
							if ($firstImage == 1)	// ie empty album
								$content .= "<a class='fancybox' rel='gallery" . $galleryId . "' href='" . Yii::app()->baseUrl . "/userdata/jelly/gallery/" . $gallery->image . "' title='" . $gallery->text . "'> <img src='" . Yii::app()->baseUrl . "/userdata/jelly/gallery/thumb_" . $gallery->image . "' alt='' /> </a>";

							$content .= "</td>";
							$content .= "<td width='1%'></td>";
							$content .= "<td width='74%'>";
							$content .= "<b>" . $gallery->title . "</b><br>" . $gallery->text;
							$content .= "</td></tr>";

							//$content .= "<a class='fancybox' rel='gallery1' href='" . Yii::app()->baseUrl . "/userdata/jelly/gallery/" . $galleryImage->image . "' title='" . $galleryImage->text . "'> <img src='" . Yii::app()->baseUrl . "/userdata/jelly/gallery/thumb_" . $galleryImage->image . "' alt='' /> </a>";

						endforeach;
						$content .= "</table>";
					}
					else if ($val == "glob")
					{
						// get pattern
						$pattern = $options['pattern'];
						foreach (glob(Yii::app()->basePath . "/../" . $pattern) as $filename)
						{
							$content .= "<li>";
							$content .= "<img src='" . Yii::app()->baseUrl . dirname($pattern) . "/". basename($filename) . "' style='float: none; margin: 0px;' alt=''>";
							$content .= "</li>";
						}
					}
					break;
				default:
					// Not all array items are action items
			}
		}

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

			<substitute-data>

		</div>

END_OF_API_HTML;

	private $apiJs = <<<END_OF_API_JS

	jQuery(document).ready(function($){
	});

	$(document).ready(function() {
		$(".fancybox").fancybox();

		/*$(".fancybox").fancybox({
    		helpers:  {
        		thumbs : {
            		width: 50,
            		height: 50
        		}
    		}
		});*/

	});

END_OF_API_JS;

}
?>
