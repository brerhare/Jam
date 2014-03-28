<?php

// @@TODO: Addons like this one need to 'breakout' of their jelly containers, ie their size is dynamic and always wants to overlay subsequent page content. Need some mechanism to bubble up this notification to container(s) until height/width requirement is satisfied.


/**
 * API for optimo menu
 *
 * Notes
 * -----
 * Taken from http://line25.com/tutorials/how-to-create-a-pure-css-dropdown-menu
 */

class optimo
{
	//Defaults
	// None

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
				case "height":
					$val = str_replace("px", "", $val);
					$this->apiHtml = str_replace("<substitute-height>",
						"nav {height: " . $val . "px;}",
						$this->apiHtml);
					break;

				case "menu-color-1":
					if (!strstr($val, "#")) $vals = "#" . $val;
					else $vals = $val;
					$this->apiHtml = str_replace("<substitute-menu-color-1>", $vals, $this->apiHtml);
					break;
				case "menu-color-2":
					if (!strstr($val, "#")) $vals = "#" . $val;
					else $vals = $val;
					$this->apiHtml = str_replace("<substitute-menu-color-2>", $vals, $this->apiHtml);
					break;

				default:
					// Not all array items are action items
			}
		}

/*
nav {
	margin-top: -100px;
	height:300px;
	padding-top:100px;
}
*/
		// Apply all defaults that werent overridden
		// HTML
		$this->apiHtml = str_replace("<substitute-height>", "", $this->apiHtml);
		$this->apiHtml = str_replace("<substitute-menu-color-1>", "color: #2F9CC9", $this->apiHtml);
		$this->apiHtml = str_replace("<substitute-menu-color-2>", "#2F9CC9", $this->apiHtml);
		$this->apiHtml = str_replace("<substitute-menu-color-3>", "#2F9CC9", $this->apiHtml);
		$this->apiHtml = str_replace("<substitute-menu-color-4>", "#2F9CC9", $this->apiHtml);
		$this->apiHtml = str_replace("<substitute-menu-color-5>", "#2F9CC9", $this->apiHtml);
		$this->apiHtml = str_replace("<substitute-menu-color-6>", "#2F9CC9", $this->apiHtml);
		$this->apiHtml = str_replace("<substitute-menu-color-7>", "#2F9CC9", $this->apiHtml);
		$this->apiHtml = str_replace("<substitute-menu-color-8>", "#2F9CC9", $this->apiHtml);
		$this->apiHtml = str_replace("<substitute-menu-color-9>", "#2F9CC9", $this->apiHtml);
		$this->apiHtml = str_replace("<substitute-menu-color-10>", "#2F9CC9", $this->apiHtml);

		// JS

		// Substitute paths for includes
		$tmp = str_replace("<substitute-path>", $jellyRootUrl, $this->apiHtml);
		$this->apiHtml = $tmp;
/**********
        				<li><a href="#">HATS</a>
            				<ul>
                				<li><a href="#">Submenu Item 1</a></li>
                				<li><a href="#">Submenu Item 2</a></li>
                				<li><a href="#">Sub-menu Item 3</a></li>
            				</ul>
						</li>
        				<li><a class="dropdown" href="#">HATMAKING</a>
            				<ul>
                				<li><a href="#">Sub-menu Item 1</a></li>
                				<li><a href="#">Sub-menu Item 2</a></li>
                				<li><a href="#">Sub-menu Item 3</a></li>
            				</ul>
            				</li>
        				<li><a href="#">SHOP</a>
            				<ul>
                				<li><a href="#">Sub-menu Item 1</a></li>
                				<li><a href="#">Sub-menu Item 2</a></li>
                				<li><a href="#">Sub-menu Item 3</a></li>
            				</ul>
           				</li>

**********/
		// Insert the data
		$menuHeaders = ContentBlock::model()->findAll(array('order'=>'sequence'));
$content .= "<li> <a href='#'>&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp</a><li>";
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
$content .= "<li> <a href='#'>&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp</a><li>";

		$html = str_replace("<substitute-data>", $content, $this->apiHtml);
		$js = $this->apiJs;

		$retArr = array();
		$retArr[0] = $html;
		$retArr[1] = $js;
		return $retArr;
	}

	// This is the HTML code we generate for this addon. If any external js/css/etc is used, it must be pulled in here
	private $apiHtml = <<<END_OF_API_HTML

	<div id="jelly-optimomenu-container">
		<!--Optimo Menu includes -->
		<link rel="stylesheet" type="text/css" href="<substitute-path>/optimo.css" />

		<style>
		<substitute-height>
		<substitute-menu-color-1>
		<substitute-menu-color-2>
		<substitute-menu-color-3>
		<substitute-menu-color-4>
		<substitute-menu-color-5>
		<substitute-menu-color-6>
		<substitute-menu-color-7>
		<substitute-menu-color-8>
		<substitute-menu-color-9>
		<substitute-menu-color-10>
		</style>

		<!--Optimo Menu HTML-->
		<div id="optimomenu">

			<div id="navdiv" onmouseover="document.getElementById('navdiv').style.backgroundColor = 'black';" onmouseout="document.getElementById('navdiv').style.backgroundColor = 'transparent';">
				<nav>
					<ul class="cf" onmouseover="">
						<substitute-data>
					</ul>
				</nav>
			</div>
		</div>
	</div>
END_OF_API_HTML;

	private $apiJs = <<<END_OF_API_JS
END_OF_API_JS;

}
?>
