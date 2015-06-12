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
	private $defaultId = "1";
	private $defaultWidth = "200px";
	private $defaultInternalWidth = "100%";
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
				case "id":
					$this->defaultId = $val;
					break;
				case "width":
					$this->defaultWidth = $val . "px";
					break;
				case "internalwidth":
				case "internal-width":
					$this->defaultInternalWidth = $val . "px";
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
			$content .= " <style> #col-outer { border: 1px solid blue; } #col-inner { border: 1px solid red; } </style>";

		$widthStyle = "width: " . $this->defaultWidth . ";";

		$bgStyle = "background-color:" . $this->defaultBackgroundColor . ";";
		if ($this->defaultBackgroundImage != "")
			$bgStyle = "background: url(" . Yii::app()->baseUrl . $this->defaultBackgroundImage . ") no-repeat center;";

		$internalWidthStyle = "width: " . $this->defaultInternalWidth . ";";

		$criteria = new CDbCriteria;
		$criteria->addCondition("column_id = " . $this->defaultId);
 		//$criteria->order = "column_id ASC, sequence ASC, title ASC";
		$columnItems = JellyColumn::model()->findAll($criteria);
		foreach ($columnItems as $columnItem):
			$content .= "<div id='col-outer' style='" .$bgStyle . $widthStyle . "'>";
				// Internal box
				$content .= "<div id='col-inner' style='margin:auto; overflow:hidden; word-wrap:break-word; " . $internalWidthStyle . "'>";
					$content .= $columnItem->content;
				$content .= "</div>";
			$content .= "</div>";
		endforeach;


/*
		// Apply all defaults that werent overridden
		// HTML
		if (strstr($this->apiHtml, "<substitute-width>"))
			$this->apiHtml = str_replace("<substitute-width>", "width:" . $this->defaultWidth . ";", $this->apiHtml);
		if (strstr($this->apiHtml, "<substitute-height>"))
			$this->apiHtml = str_replace("<substitute-height>", "height:" . $this->defaultHeight . ";", $this->apiHtml);
		$this->apiHtml = str_replace("<substitute-border-width>", $this->defaultBorderWidth,  $this->apiHtml);
		$this->apiHtml = str_replace("<substitute-border-color>", $this->defaultBorderColor,  $this->apiHtml);
		$this->apiHtml = str_replace("<substitute-active-dotcolor>", $this->defaultActiveDotColor,  $this->apiHtml);
		$this->apiHtml = str_replace("<substitute-inactive-dotcolor>", $this->defaultInactiveDotColor,  $this->apiHtml);
		$this->apiHtml = str_replace("<substitute-dot-margin-top>", "0px",  $this->apiHtml);
		$this->apiHtml = str_replace("<substitute-dot-margin-bottom>", "0px",  $this->apiHtml);
		$this->apiHtml = str_replace("<substitute-dot-margin-left>", "0px",  $this->apiHtml);
		$this->apiHtml = str_replace("<substitute-dot-margin-right>", "0px",  $this->apiHtml);
*/
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
