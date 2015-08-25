<?php

/**
 * API for Variable columns
 *
 * Notes
 * -----
 * None
 */

class column
{
	//Defaults
	private $defaultName = "1";
	private $defaultWidth = "200px";
	private $defaultInternalWidth = "100%";
	private $defaultContentWidth = "100%";
	private $defaultInternalColor = "transparent";
	private $defaultBackgroundColor = "#ffffff";
	private $defaultBackgroundImage = "";
	private $defaultTopHeight = "0px";
	private $defaultBottomHeight = "0px";
	private $defaultTopImage = "";
	private $defaultBottomImage = "";
	private $debug = "";

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
			if (strstr($val, "px"))
				$val = str_replace("px", "", $val);
			switch ($opt)
			{
				case "name":
					$this->defaultName = $val;
					break;
				case "width":
					$this->defaultWidth = $val . "px";
					break;
				case "internalwidth":
				case "internal-width":
					$this->defaultInternalWidth = $val . "px";
					break;
				case "contentwidth":
				case "content-width":
					$this->defaultContentWidth = $val . "px";
					break;
				case "internalcolor":
				case "internal-color":
					$this->defaultInternalColor = $val;
					break;
				case "backgroundcolor":
				case "background-color":
					$this->defaultBackgroundColor = $val;
					break;
				case "backgroundcolor":
				case "background-color":
					$this->defaultBackgroundColor = $val;
					break;
				case "backgroundcolor":
				case "background-color":
					$this->defaultBackgroundColor = $val;
					break;
				case "backgroundcolor":
				case "background-color":
					$this->defaultBackgroundColor = $val;
					break;
				case "backgroundimage":
				case "background-image":
					$this->defaultBackgroundImage = $val;
					break;
				case "topheight":
				case "top-height":
					$this->defaultTopHeight = $val . "px";
					break;
				case "bottomheight":
				case "bottom-height":
					$this->defaultBottomHeight = $val . "px";
					break;
				case "topimage":
				case "top-image":
					$this->defaultTopImage = $val;
					break;
				case "bottomimage":
				case "bottom-image":
					$this->defaultBottomImage = $val;
					break;
				case "debug":
					$this->debug = 1;
				default:
					// Not all array items are action items
			}
		}

		if ($this->debug != "")
			$content .= "	<style>		#col-outer { border: 1px solid blue; }
										#col-inner { border: 1px solid red; }
										#col-top { border: 1px solid purple; }
										#col-bottom { border: 1px solid purple; }
							</style>";

		$widthStyle = "width: " . $this->defaultWidth . ";";
		$bgStyle = "background-color:" . $this->defaultBackgroundColor . ";";
		if ($this->defaultBackgroundImage != "")
			$bgStyle = "background: url(" . Yii::app()->baseUrl . $this->defaultBackgroundImage . ") no-repeat center; background-size:100%";

		$internalWidthStyle = "width: " . $this->defaultInternalWidth . ";";

		$contentWidthStyle = "width: " . $this->defaultContentWidth . ";";
		$internalColorStyle = "background-color:" . $this->defaultInternalColor . ";";

		$bgTopStyle = "background-color: transparent;";
		if ($this->defaultTopImage != "")
			$bgTopStyle = "background: url(" . Yii::app()->baseUrl . $this->defaultTopImage . ") no-repeat center; background-size:100%";

		$bgBottomStyle = "background-color: transparent;";
		if ($this->defaultBottomImage != "")
			$bgBottomStyle = "background: url(" . Yii::app()->baseUrl . $this->defaultBottomImage . ") no-repeat center; background-size:100%";

		$criteria = new CDbCriteria;
		$criteria->addCondition("column_name = '" . $this->defaultName . "'");
 		$criteria->order = "column_name ASC, sequence ASC, title ASC";
		$columnItems = JellyColumn::model()->findAll($criteria);
		foreach ($columnItems as $columnItem):
			// Outer box
			$content .= "<div id='col-outer' style='" .$bgStyle . $widthStyle . "'>";
				// Top box
				$content .= "<div id='col-top' style='height:" . $this->defaultTopHeight. "; width:100%;" .$bgTopStyle . "'></div>";
				// Inner box
				$content .= "<div id='col-inner' style='margin:auto; overflow:hidden; word-wrap:break-word; " . $internalColorStyle  . $internalWidthStyle . "'>";
					// Actual content within inner
					$content .= "<div id='col-content' style='margin:auto; overflow:hidden; " .$contentWidthStyle . "'>";
						$content .= $columnItem->content;
					$content .= "</div>";
				$content .= "</div>";
				// Bottom box
				$content .= "<div id='col-bottom' style='height:" . $this->defaultBottomHeight. "; width:100%;" .$bgBottomStyle . "'></div>";
			$content .= "</div>";
		endforeach;

		// JS
		if (strstr($this->apiJs, "<substitute-animation>"))
		{
			$tmp = str_replace("<substitute-animation>", "'" . $this->defaultAnimation . "'", $this->apiJs);
			$this->apiJs = $tmp;
		}

		$this->apiHtml = str_replace("<substitute-path>", $jellyRootUrl, $this->apiHtml);
		$this->apiHtml = str_replace("<substitute-data>", $content, $this->apiHtml);

		$html = $this->apiHtml;
		$js = $this->apiJs;

		$retArr = array();
		$retArr[0] = $html;
		$retArr[1] = $js;
		return $retArr;
	}

	// @@TODO: Thought: maybe all sites use a jelly db, and each has their own table prefix? Could avoid a lot of hassle

	private $apiHtml = <<<END_OF_API_HTML

        <div id="jelly-global-column-container">
            <!-- Global Column -->
			<substitute-data>
        </div>

END_OF_API_HTML;

	private $apiJs = <<<END_OF_API_JS

	jQuery(document).ready(function($){
	});

END_OF_API_JS;

}
?>
