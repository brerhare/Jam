<?php

/**
 * API for basic menu
 *
 * Notes
 * -----
 * Taken from http://line25.com/tutorials/how-to-create-a-pure-css-dropdown-menu
 */

class basic
{
	//Defaults
	private $defaultOrientation = "horizontal";

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
				case "orientation":		// vertical (default is horizontal)
					$tmp = str_replace("<substitute-orientation>", $val, $this->apiHtml);
					$this->apiHtml = $tmp;
					break;

				case "font-size":
						$val = str_replace("px", "", $val);
					$this->apiHtml = str_replace("<substitute-font-size>",
						"nav ul li a {font-size: " . $val . "px;}",
						$this->apiHtml);
					break;

				case "menu-color":
					$vals = explode(" ", $val);
					if (count($vals == 1)) array_push($vals, $vals[0]);
					$this->apiHtml = str_replace("<substitute-menu-color>",
						"nav ul {
							background: " . $vals[0] . ";
							background: linear-gradient(top, " . $vals[0] . " 0%, " . $vals[1] . " 100%);  
							background: -moz-linear-gradient(top, " . $vals[0] . " 0%, " . $vals[1] . " 100%); 
							background: -webkit-linear-gradient(top, " . $vals[0] . " 0%, " . $vals[1] . " 100%); 
						}",	
						$this->apiHtml);
					break;
				case "submenu-color":
					$vals = explode(" ", $val);
					if (count($vals == 1)) array_push($vals, $vals[0]);
					$this->apiHtml = str_replace("<substitute-submenu-color>",
						"nav ul ul {
							background: " . $vals[0] . ";
							background: linear-gradient(top, " . $vals[0] . " 0%, " . $vals[1] . " 100%);  
							background: -moz-linear-gradient(top, " . $vals[0] . " 0%, " . $vals[1] . " 100%); 
							background: -webkit-linear-gradient(top, " . $vals[0] . " 0%, " . $vals[1] . " 100%); 
						}",	
						$this->apiHtml);
					break;

				case "item-color":
					$vals = explode(" ", $val);
					if (count($vals == 1)) array_push($vals, $vals[0]);
					$this->apiHtml = str_replace("<substitute-item-color>",
						"nav ul li:hover {
							background: " . $vals[0] . ";
							background: linear-gradient(top, " . $vals[0] . " 0%, " . $vals[1] . " 100%);  
							background: -moz-linear-gradient(top, " . $vals[0] . " 0%, " . $vals[1] . " 100%); 
							background: -webkit-linear-gradient(top, " . $vals[0] . " 0%, " . $vals[1] . " 100%); 
						}",	
						$this->apiHtml);
					break;
				case "subitem-color":
					$vals = explode(" ", $val);
					if (count($vals == 1)) array_push($vals, $vals[0]);
					$this->apiHtml = str_replace("<substitute-subitem-color>",
						"nav ul ul li a:hover {
							background: " . $vals[0] . ";
							background: linear-gradient(top, " . $vals[0] . " 0%, " . $vals[1] . " 100%);  
							background: -moz-linear-gradient(top, " . $vals[0] . " 0%, " . $vals[1] . " 100%); 
							background: -webkit-linear-gradient(top, " . $vals[0] . " 0%, " . $vals[1] . " 100%); 
						}",	
						$this->apiHtml);
					break;

				case "subitem-separator-color":
					$this->apiHtml = str_replace("<substitute-subitem-separator-color>",
						"nav ul ul li {border-top: 1px solid " . $val . ";}",
						$this->apiHtml);
					break;


//				case "text-color":
//					break;


				case "item-text-color":
					$this->apiHtml = str_replace("<substitute-item-text-color>",
						"nav ul li:hover a {color: " . $val . ";}",
						$this->apiHtml);
					break;
				case "subitem-text-color":
					$this->apiHtml = str_replace("<substitute-subitem-text-color>",
						"nav ul ul li a:hover {color: " . $val . ";}",
						$this->apiHtml);
					break;

				default:
					// Not all array items are action items
			}
		}

		// Apply all defaults that werent overridden
		// HTML
//@@TODO: vertical isnt handled yet, only the default horiz
		if (strstr($this->apiHtml, "<substitute-orientation>"))
			$this->apiHtml = str_replace("<substitute-orientation>", $this->defaultOrientation, $this->apiHtml);
		$this->apiHtml = str_replace("<substitute-font-size>", "", $this->apiHtml);
		$this->apiHtml = str_replace("<substitute-menu-color>", "", $this->apiHtml);
		$this->apiHtml = str_replace("<substitute-submenu-color>", "", $this->apiHtml);
		$this->apiHtml = str_replace("<substitute-item-color>", "", $this->apiHtml);
		$this->apiHtml = str_replace("<substitute-item-text-color>", "", $this->apiHtml);		
		$this->apiHtml = str_replace("<substitute-subitem-color>", "", $this->apiHtml);
		$this->apiHtml = str_replace("<substitute-subitem-text-color>", "", $this->apiHtml);		
		$this->apiHtml = str_replace("<substitute-subitem-separator-color>", "", $this->apiHtml);

		// JS

		// Substitute paths for includes
		$tmp = str_replace("<substitute-path>", $jellyRootUrl, $this->apiHtml);
		$this->apiHtml = $tmp;

		// Insert the data
		$content = "<nav>";
		$content .= "<ul>";
		$menuHeaders = ContentBlock::model()->findAll(array('order'=>'sequence'));
		foreach ($menuHeaders as $menuHeader):
			if (($menuHeader->parent_id) || (!$menuHeader->active))
				continue;
			$content .= "<li> <a href='" . Yii::app()->request->baseUrl . "?layout=index&page=" . $menuHeader->url . "'>" . $menuHeader->title . "</a>";
			$criteria = new CDbCriteria;
			$criteria->addCondition("parent_id = " . $menuHeader->id);
			$menuItems = ContentBlock::model()->findAll($criteria);
			$l2 = false;
			foreach ($menuItems as $menuItem):
				if ($l2 == false)
				{
					$content .= "<ul>";
					$l2 = true;
				}
				if ($menuItem->active)
					$content .= "<li style='z-index:11000'><a href='" . Yii::app()->request->baseUrl . "?layout=index&page=" . $menuItem->url . "'>" . $menuItem->title . "</a></li>";
			endforeach;
			if ($l2 == true)
				$content .= "</ul>";
			$content .= "</li>";
		endforeach;
		$content .= "</ul>";
		$content .= "</nav>";

		$html = str_replace("<substitute-data>", $content, $this->apiHtml);
		$js = $this->apiJs;

		$retArr = array();
		$retArr[0] = $html;
		$retArr[1] = $js;
		return $retArr;
	}

	// This is the HTML code we generate for this addon. If any external js/css/etc is used, it must be pulled in here
	private $apiHtml = <<<END_OF_API_HTML

	<div id="jelly-basicmenu-container">
		<!--Basic Menu includes -->
		<link rel="stylesheet" type="text/css" href="<substitute-path>/basic.css" />

<style>
<substitute-font-size>
<substitute-menu-color>
<substitute-submenu-color>
<substitute-item-color>
<substitute-item-text-color>
<substitute-subitem-color>
<substitute-subitem-text-color>
<substitute-subitem-separator-color>
</style>





		<!--Basic Menu HTML-->
		<div id="basicmenu">
			<substitute-data>
		</div>
	</div>

END_OF_API_HTML;

	private $apiJs = <<<END_OF_API_JS
END_OF_API_JS;

}
?>
