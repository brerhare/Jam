<?php

/**
 * API for Download
 *
 * Notes
 * -----
 * No external code
 */

class jellybasic
{
	//Defaults
	private $optionMode = "all";
	private $optionValue = "";

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
				case "mode";
					if (($val == "file") || ($val == "collection"))
						$this->optionMode = $val;
						break;
				case "value";
						$this->optionValue = $val;
						break;
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

							$content .= "<a class='fancybox' rel='gallery" . $galleryId . "' href='" . Yii::app()->baseUrl . "/userdata/jelly/gallery/" . $gallery->image . "' title='" . $gallery->text . "'> <img src='" . Yii::app()->baseUrl . "/userdata/jelly/gallery/thumb_" . $gallery->image . "' alt='' /> </a>";

							$criteria = new CDbCriteria;
							$criteria->addCondition("jelly_gallery_id = " . $gallery->id);
							$galleryImages = JellyGalleryImage::model()->findAll($criteria);
							foreach ($galleryImages as $galleryImage):

								$content .= "<a style='display:none' class='fancybox' rel='gallery" . $galleryId . "' href='" . Yii::app()->baseUrl . "/userdata/jelly/gallery/" . $galleryImage->image . "' title='" . $galleryImage->text . "'> <img src='" . Yii::app()->baseUrl . "/userdata/jelly/gallery/thumb_" . $galleryImage->image . "' alt='' /> </a>";

							endforeach;

							$content .= "</td>";
							$content .= "<td width='1%'></td>";
							$content .= "<td width='74%'>";
							$content .= "<b>" . $gallery->title . "</b><br>" . $gallery->text;
							$content .= "</td></tr>";

						endforeach;
						$content .= "</table>";
					}
					break;
				default:
					// Not all array items are action items
			}
		}

		// Generate the links
		if ($this->optionMode == "file")
		{
			$criteria = new CDbCriteria;
			$criteria->addCondition("description = '" . $this->optionValue . "'");
			$jellyDownloadFile = JellyDownloadFile::model()->find($criteria);
			if ($jellyDownloadFile)
				$content = "<a style='text-decoration:underline' href='" . Yii::app()->baseUrl . "/userdata/jelly/download/" . $jellyDownloadFile->filename . "'>" . $jellyDownloadFile->description . "</a>";
			else
				$content = "Missing link - " . $this->optionValue;
		}
		else if ($this->optionMode == "collection")
		{
			$criteria = new CDbCriteria;
			$criteria->addCondition("name = '" . $this->optionValue . "'");
			$jellyDownloadCollection = JellyDownloadCollection::model()->find($criteria);
			if ($jellyDownloadCollection)
			{
				$criteria = new CDbCriteria;
				$criteria->addCondition("jelly_download_collection_id = " . $jellyDownloadCollection->id);
				$jellyDownloadFiles = JellyDownloadFile::model()->findAll($criteria);
				if ($jellyDownloadFiles)
				{
					foreach ($jellyDownloadFiles as $jellyDownloadFile)
					{
						$content = "<a style='text-decoration:underline' href='" . Yii::app()->baseUrl . "/userdata/jelly/download/" . $jellyDownloadFile->filename . "'>" . $jellyDownloadFile->description . "</a>";
					}
				}
				else
					$content = "Missing link - " . $this->optionValue;
			}
			else
				$content = "Missing collection - " . $this->optionValue;
		}

		// Apply all substitutions
		// HTML
		$this->apiHtml = str_replace("<substitute-data>", $content, $this->apiHtml);

		// JS

		$retArr = array();
		$retArr[0] = $this->apiHtml;
		$retArr[1] = $this->apiJs;
		return $retArr;
	}

	private $apiHtml = <<<END_OF_API_HTML
		<substitute-data>
END_OF_API_HTML;

	private $apiJs = <<<END_OF_API_JS
END_OF_API_JS;

}
?>
