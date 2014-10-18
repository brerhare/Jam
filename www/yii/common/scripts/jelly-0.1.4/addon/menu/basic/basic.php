<?php

// @@TODO: Addons like this one need to 'breakout' of their jelly containers, ie their size is dynamic and always wants to overlay subsequent page content. Need some mechanism to bubble up this notification to container(s) until height/width requirement is satisfied.


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
	private $default_orientation = "horizontal";
	private $default_item_separator_width = 1;
	private $default_separator_color = "#d3d3d3";
	private $default_subitem_separator_width = 1;
	private $default_item_separator_color = "#d3d3d3";
	private $height = "14px";
	private $level = 0;

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
					$this->default_orientation = $val;
					$this->apiHtml = str_replace("<substitute-orientation>", $val, $this->apiHtml);
					break;

				case "width":
					$val = str_replace("px", "", $val);
					$this->apiHtml = str_replace("<substitute-width>",
						"nav ul {width: " . $val . "px;}",
						$this->apiHtml);
					break;

                case "height":
                    $val = str_replace("px", "", $val);
                    $this->height = $val;   // Store for later
                    $this->apiHtml = str_replace("<substitute-height>",
                        "nav ul li a {height: " . $val . "px; padding: 0px 15px;} " .
                        "nav ul ul li a {height: " . $val . "px; padding: 0px 15px;} ",
                        $this->apiHtml);
                    break;

                case "menu-text-weight":
                    $this->apiHtml = str_replace("<substitute-menu-text-weight>",
                        "nav ul li a {font-weight: " . $val . " !important;}",
                        $this->apiHtml);
                    break;

				case "level":
					$this->level = $val;
					break;

				case "font-size":
					$val = str_replace("px", "", $val);
					$this->apiHtml = str_replace("<substitute-font-size>",
						"nav ul li a {font-size: " . $val . "px;}",
						$this->apiHtml);
					break;

                case "edgepadding":
                    $val = str_replace("px", "", $val);
                    $this->apiHtml = str_replace("<substitute-edgepadding>",
                        "nav ul {
                            padding: 0px " . $val . "px;
                        }",
                        $this->apiHtml);
                    break;

				case "menu-rounding":
					$val = str_replace("px", "", $val);
					$this->apiHtml = str_replace("<substitute-menu-rounding>",
						"nav ul {
							-moz-border-radius: " . $val . "px;
							-webkit-border-radius: " . $val . "px;
							border-radius: " . $val . "px; /* future proofing */
							-khtml-border-radius: " . $val . "px; /* for old Konqueror browsers */;
						}",	
						$this->apiHtml);
					break;

				case "menu-opacity":
					$this->apiHtml = str_replace("<substitute-menu-opacity>",	/* First level menu */
						"nav ul {
							zoom: 1; filter: alpha(opacity=" . $val . ");
							opacity: " . ($val / 100) . ";
						}",
						$this->apiHtml);
					$this->apiHtml = str_replace("<substitute-submenu-opacity>",	/* Second level menu */
						"nav ul ul {
							zoom: 1; filter: alpha(opacity='100');
							opacity: " . (100) . ";
						}",
						$this->apiHtml);
					break;

				case "menu-tile":
					$this->apiHtml = str_replace("<substitute-menu-tile>",
						"nav ul {
							background: url('" . Yii::app()->baseUrl . $val . "') repeat;
						}",
						$this->apiHtml);
					break;

				case "menu-stretch":
					$this->apiHtml = str_replace("<substitute-menu-stretch>",
						"nav ul {
							background: url('" . Yii::app()->baseUrl . $val . "');
							background-size: 100%;
							background-repeat: no-repeat;
							size: 100%;
						}",
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

				case "Xsubmenu-opacity":
					$this->apiHtml = str_replace("<substitute-submenu-opacity>",
						"nav ul ul {
							background-color: rgba(0,0,255,1.0);
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

                case "menu-text-weight":
                    $this->apiHtml = str_replace("<substitute-menu-text-weight>",
                        "nav ul li a {font-weight: " . $val . " !important;}",
                        $this->apiHtml);
                    break;

				case "menu-text-color":
					$this->apiHtml = str_replace("<substitute-menu-text-color>",
						"nav ul li a {color: " . $val . " !important;}",
						$this->apiHtml);
					break;
// NW!!!
				case "submenu-text-color":
					$this->apiHtml = str_replace("<substitute-submenu-text-color>",
						"nav ul li ul li a {color: " . $val . " !important;}",
						$this->apiHtml);
					break;
				case "item-text-color":
					$this->apiHtml = str_replace("<substitute-item-text-color>",
						"nav ul li:hover a {color: " . $val . " !important;}",
						$this->apiHtml);
					break;
				case "subitem-text-color":
					$this->apiHtml = str_replace("<substitute-subitem-text-color>",
						"nav ul ul li a:hover {color: " . $val . " !important;}",
						$this->apiHtml);
					break;
				case "item-separator-color":
                    $this->default_item_separator_color = $val;
					if ($this->default_orientation != "horizontal")
					{
						$str = "nav ul li + li {border-top: <substitute-default-item-separator-width>px solid " . $val . ";}";
						$this->apiHtml = str_replace("<substitute-item-separator-color>", $str, $this->apiHtml);
					}
					break;
				case "subitem-separator-color":
					$this->apiHtml = str_replace("<substitute-subitem-separator-color>",
						"nav ul ul li {border-top: <substitute-default-subitem-separator-width>px solid " . $val . ";}",
						$this->apiHtml);
					break;
				case "item-separator-width":
					$val = str_replace("px", "", $val);
					$this->default_item_separator_width = $val;
					break;
				case "subitem-separator-width":
					$val = str_replace("px", "", $val);
					$this->default_subitem_separator_width = $val;
					break;
				default:
					// Not all array items are action items
			}
		}

        // Apply all order-dependant options


        if ($this->default_orientation == "horizontal")
		{
            $css = "nav ul li~li { border-left: " . $this->default_item_separator_width . "px solid " . $this->default_item_separator_color . "}";
        	$this->apiHtml = str_replace("<substitute-item-separator-width>", $css, $this->apiHtml);
		}
/*****
        if ($this->default_orientation == "horizontal")
            $css = "nav ul li~li { border-left: " . $this->default_item_separator_width . "px solid " . $this->default_item_separator_color . "}";
        else
            $css = "nav ul li~li { border-top: " . $this->default_item_separator_width . "px solid " . $this->default_item_separator_color . "}";
        $this->apiHtml = str_replace("<substitute-item-separator-width>", $css, $this->apiHtml);
*****/

		// Apply all defaults that werent overridden
		// HTML
		if (strstr($this->apiHtml, "<substitute-orientation>"))
			$this->apiHtml = str_replace("<substitute-orientation>", $this->default_orientation, $this->apiHtml);
		$this->apiHtml = str_replace("<substitute-width>", "", $this->apiHtml);
		$this->apiHtml = str_replace("<substitute-height>", "", $this->apiHtml);
		$this->apiHtml = str_replace("<substitute-font-size>", "", $this->apiHtml);
		$this->apiHtml = str_replace("<substitute-edgepadding>", "", $this->apiHtml);
		$this->apiHtml = str_replace("<substitute-menu-color>", "", $this->apiHtml);
		$this->apiHtml = str_replace("<substitute-menu-rounding>", "", $this->apiHtml);
		$this->apiHtml = str_replace("<substitute-menu-opacity>", "", $this->apiHtml);
		$this->apiHtml = str_replace("<substitute-menu-tile>", "", $this->apiHtml);
		$this->apiHtml = str_replace("<substitute-menu-stretch>", "", $this->apiHtml);
		$this->apiHtml = str_replace("<substitute-submenu-color>", "", $this->apiHtml);
		$this->apiHtml = str_replace("<substitute-submenu-opacity>", "", $this->apiHtml);
		$this->apiHtml = str_replace("<substitute-menu-text-color>", "", $this->apiHtml);
		$this->apiHtml = str_replace("<substitute-menu-text-weight>", "", $this->apiHtml);
		$this->apiHtml = str_replace("<substitute-submenu-text-color>", "", $this->apiHtml);
		$this->apiHtml = str_replace("<substitute-item-color>", "", $this->apiHtml);
		$this->apiHtml = str_replace("<substitute-item-text-color>", "", $this->apiHtml);		
		$this->apiHtml = str_replace("<substitute-subitem-color>", "", $this->apiHtml);
		$this->apiHtml = str_replace("<substitute-subitem-text-color>", "", $this->apiHtml);		
		$this->apiHtml = str_replace("<substitute-default-item-separator-width>", $this->default_item_separator_width, $this->apiHtml);
		$this->apiHtml = str_replace("<substitute-default-subitem-separator-width>", $this->default_subitem_separator_width, $this->apiHtml);
		$this->apiHtml = str_replace("<substitute-item-separator-color>", "", $this->apiHtml);
		$this->apiHtml = str_replace("<substitute-subitem-separator-color>", "", $this->apiHtml);

		// JS

		// Substitute paths for includes
		$tmp = str_replace("<substitute-path>", $jellyRootUrl, $this->apiHtml);
		$this->apiHtml = $tmp;

		// Insert the data
//$this->level = 2;


/**/

			if ($this->level == 2)
			{
				$content = "<nav>";
				$content .= "<ul>";
				if (isset($_GET['page']))
				{
					// Get the requested URL
					$criteria = new CDbCriteria;
					$criteria->addCondition("url = '" . $_GET['page'] . "'");
$criteria->order = "sequence ASC";
					$menuItems = ContentBlock::model()->find($criteria);
					if ($menuItems)
					{
						$parent = $menuItems->id;
						$layout = $_GET['layout'];
						if ($menuItems->parent_id != 0)
							$parent = $menuItems->parent_id;
						// Now get all children
						$criteria = new CDbCriteria;
						//$criteria->condition = "url = '" . $_GET['page'] . "' OR parent_id = " . $menuItems->id;
						$criteria->addCondition("parent_id = " . $parent);
$criteria->order = "sequence ASC";
						$menuItems = ContentBlock::model()->findAll($criteria);
						foreach ($menuItems as $menuItem):
							if (!$menuItem->active)
								continue;
							$content .= "<li> <a href='" . Yii::app()->request->baseUrl . "?layout=" . $layout . "&page=" . $menuItem->url . "'>" . $menuItem->title . "</a>";
							$content .= "</li>";
						endforeach;
					}
				}
				$content .= "</ul>";
				$content .= "</nav>";
			}
/**/

		else
		{



		$content = "<nav>";
		$content .= "<ul>";
		$menuHeaders = ContentBlock::model()->findAll(array('order'=>'sequence'));
		foreach ($menuHeaders as $menuHeader):
			if (!$menuHeader->active)
				continue;
			if ($this->level == 0)
			{
				if ($menuHeader->parent_id)
					continue;
			}
			if ($this->level == 2)
			{
				//if 
			}
			//$content .= "<li> <a href='" . Yii::app()->request->baseUrl . "?layout=index&page=" . $menuHeader->url . "'>" . $menuHeader->title . "</a>";

$content .= "<li><div style='line-height:" . $this->height . "px'> <a href='" . Yii::app()->request->baseUrl . "?layout=index&page=" . $menuHeader->url . "'>" . $menuHeader->title . "</a></div>";

			$criteria = new CDbCriteria;
			$criteria->addCondition("parent_id = " . $menuHeader->id);
$criteria->order = "sequence ASC";
			$menuItems = ContentBlock::model()->findAll($criteria);
			$l2 = false;
			foreach ($menuItems as $menuItem):
				if ($l2 == false)
				{
					$content .= "<ul>";
					$l2 = true;
				}
				if ($menuItem->active)
					//$content .= "<li style='z-index:11000'><a href='" . Yii::app()->request->baseUrl . "?layout=index&page=" . $menuItem->url . "'>" . $menuItem->title . "</a></li>";

					$content .= "<li style='Xz-index:11000'><div style='line-height:" . $this->height . "px'> <a href='" . Yii::app()->request->baseUrl . "?layout=index&page=" . $menuItem->url . "'>" . $menuItem->title . "</a></div> </li>";

			endforeach;
			if ($l2 == true)
				$content .= "</ul>";
			$content .= "</li>";
		endforeach;
		$content .= "</ul>";
		$content .= "</nav>";


		}


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
		<link rel="stylesheet" type="text/css" href="<substitute-path>/basic_<substitute-orientation>.css" />

		<style>
		<substitute-width>
		<substitute-height>
		<substitute-font-size>
		<substitute-edgepadding>
		<substitute-menu-color>
		<substitute-menu-rounding>
		<substitute-menu-opacity>
		<substitute-menu-tile>
		<substitute-menu-stretch>
		<substitute-menu-text-color>
		<substitute-menu-text-weight>
		<substitute-submenu-text-color>
		<substitute-submenu-color>
		<substitute-submenu-opacity>
		<substitute-item-color>
		<substitute-item-text-color>
		<substitute-subitem-color>
		<substitute-subitem-text-color>
		<substitute-item-separator-color>
		<substitute-subitem-separator-color>
		<substitute-item-separator-width>
		<substitute-subitem-separator-width>
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
