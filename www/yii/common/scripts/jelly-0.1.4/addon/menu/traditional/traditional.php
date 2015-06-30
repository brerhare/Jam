<?php

/**
 * API for traditional menu
 *
 * Notes
 * -----
 * Taken from http://line25.com/tutorials/how-to-create-a-pure-css-dropdown-menu
 */

class traditional
{
	// Notes
	// align doesnt apply to vertical
	// item-width-variable and item-width doesnt apply to vertical - use higher level menu width to set the width for vertical menu
	// margin doesnt apply to vertical
	

	// Defaults
	private $default_orientation = "horizontal";			// horizontal, vertical
	private $default_width = "";							// blank or a px value. uses 100% of container width if a px isnt specified
	private $default_height = "40";							// actually sets height=font-size & shares remaining px between padding top & bottom
	private $default_menu_font_family = "";
	private $default_menu_font_size = "14";
	private $default_menu_font_weight = "";
	private $default_menu_text_color = "";
	private $default_align = "left";						// left, center
	private $default_item_width = "";						// variable, uniform or a px value. Defaults to variable
	private $default_item_margin = "0";						// split equally between left and right. @@TODO handle 1st and last
	private $default_menu_rounding = "";					// px
	private $default_menu_opacity = "";						// %
	private $default_menu_tile = "";						// /path/to/image
	private $default_menu_stretch = "";						// /path/to/image
	private $default_menu_color = "transparent";			// color name or value - from and optionally to (in which case its a gradient)
	private $default_item_separator_width = 1;				// px
	private $default_item_separator_color = "transparent";	// specify a color or one of tile|stretch
	private $default_item_separator_tile = "";				// either ..
	private $default_item_separator_stretch = "";			// .. or
	private $default_edgepadding = "";						// px
	private $default_menu_hover_text_color = "";
	private $default_menu_hover_background_color = "";
	private $default_menu_hover_font_family = "";
	private $default_menu_hover_font_size = "14";
	private $default_menu_hover_font_weight = "";
	private $default_menu_selected_text_color = "";
	private $default_menu_selected_background_color = "";
	private $default_menu_selected_font_family = "";
	private $default_menu_selected_font_size = "14";
	private $default_menu_selected_font_weight = "";
	private $default_submenu_height = "40";					// actually sets height=font-size & shares remaining px between padding top & bottom
	private $default_submenu_font_family = "";
	private $default_submenu_font_size = "14";
	private $default_submenu_font_weight = "";
	private $default_submenu_text_color = "";
	private $default_submenu_background_color = "";			// specify a color or one of tile|stretch
	private $default_submenu_background_tile = "";			// either ..
	private $default_submenu_background_stretch = "";		// .. or
	private $default_submenu_separator_width = "1";
	private $default_submenu_separator_color = "grey";
	private $default_submenu_separator_tile = "";
	private $default_submenu_separator_stretch = "";
	private $default_submenu_width = "";					// variable or a px value (uniform n/a here). Defaults to variable

	private $level = 0;									// future use? like cmsms menus can 'come in' at any level

	/*
	 * Set up any code pre-requisites (onload/document-ready reqs)
	 * Apply options
	 * Return an array containing [0]localContent [1]globalContent
	 */
	public function init($options, $jellyRootUrl)
	{
		$content = "";

		// Override defaults for any given options

		foreach ($options as $opt => $val)
		{
			$val = str_replace("px", "", $val);
			$val = str_replace("%", "", $val);
			$val = trim($val);

// Top level basic settings
			if 		($opt == "orientation")							$this->default_orientation = $val;
			else if ($opt == "width")								$this->default_width = $val;
			else if ($opt == "height")								$this->default_height = $val;
			else if ($opt == "align")								$this->default_align = $val;
			else if ($opt == "item-margin")							$this->default_item_margin = $val;
			else if ($opt == "item-width")							$this->default_item_width = $val;
// Top level font settings
			else if ($opt == "menu-font-size")						$this->default_menu_font_size = $val;
			else if ($opt == "menu-font-family")					$this->default_menu_font_family = $val;
			else if ($opt == "menu-font-weight")					$this->default_menu_font_weight = $val;
			else if ($opt == "menu-text-color")						$this->default_menu_text_color = $val;
// Top level background settings
			else if ($opt == "menu-rounding")						$this->default_menu_rounding = $val;
			else if ($opt == "menu-opacity")						$this->default_menu_opacity = $val;
			else if ($opt == "menu-tile")							$this->default_menu_tile = $val;
			else if ($opt == "menu-stretch")						$this->default_menu_stretch = $val;
			else if ($opt == "menu-color")							$this->default_menu_color = $val;
			else if ($opt == "item-separator-width")				$this->default_item_separator_width = $val;
			else if ($opt == "item-separator-color")				$this->default_item_separator_color = $val;
			else if ($opt == "item-separator-tile")					$this->default_item_separator_tile = $val;
			else if ($opt == "item-separator-stretch")				$this->default_item_separator_stretch = $val;
			else if ($opt == "edgepadding")							$this->default_edgepadding = $val;
// Top level hover settings
			else if ($opt == "menu-hover-text-color")				$this->default_menu_hover_text_color = $val;
			else if ($opt == "menu-hover-background-color")			$this->default_menu_hover_background_color = $val;
			else if ($opt == "menu-hover-font-size")				$this->default_menu_hover_font_size = $val;
			else if ($opt == "menu-hover-font-family")				$this->default_menu_hover_font_family = $val;
			else if ($opt == "menu-hover-font-weight")				$this->default_menu_hover_font_weight = $val;
// Top level selected settings
			else if ($opt == "menu-selected-text-color")			$this->default_menu_selected_text_color = $val;
			else if ($opt == "menu-selected-background-color")		$this->default_menu_selected_background_color = $val;
			else if ($opt == "menu-selected-font-size")				$this->default_menu_selected_font_size = $val;
			else if ($opt == "menu-selected-font-family"	)		$this->default_menu_selected_font_family = $val;
			else if ($opt == "menu-selected-font-weight")			$this->default_menu_selected_font_weight = $val;
// Second level settings
			else if ($opt == "submenu-height")						$this->default_submenu_height = $val;
			else if ($opt == "submenu-width")						$this->default_submenu_width = $val;
			else if ($opt == "submenu-font-size")					$this->default_submenu_font_size = $val;
			else if ($opt == "submenu-font-family")					$this->default_submenu_font_family = $val;
			else if ($opt == "submenu-font-weight")					$this->default_submenu_font_weight = $val;
			else if ($opt == "submenu-text-color")					$this->default_submenu_text_color = $val;
			else if ($opt == "submenu-background-color")			$this->default_submenu_background_color = $val;
			else if ($opt == "submenu-background-tile")				$this->default_submenu_background_tile = $val;
			else if ($opt == "submenu-background-stretch")			$this->default_submenu_background_stretch = $val;
			else if ($opt == "submenu-separator-width")				$this->default_submenu_separator_width = $val;
			else if ($opt == "submenu-separator-color")				$this->default_submenu_separator_color = $val;
			else if ($opt == "submenu-separator-tile")				$this->default_submenu_separator_tile = $val;
			else if ($opt == "submenu-separator-stretch")			$this->default_submenu_separator_stretch = $val;
		}

		// All options collected - now do the sums

		// orientation
		$this->apiHtml = str_replace("<substitute-orientation>", $this->default_orientation, $this->apiHtml);

		// width
		if ($this->default_width != "")
		{
			if ($this->default_orientation == "horizontal")
				$this->apiHtml = str_replace("<substitute-width>", "xnav ul {width: $this->default_width" . "px;}", $this->apiHtml);
			else
				$this->apiHtml = str_replace("<substitute-width>", "xnav ul {width: $this->default_width" . "px;}", $this->apiHtml);
		}

		// height
		$paddingTop    = ($this->default_height - $this->default_menu_font_size) / 2;
		$paddingBottom = ($this->default_height - $this->default_menu_font_size - $paddingTop);
		$this->apiHtml = str_replace("<substitute-height>",
			"xnav ul li a {height: $this->default_menu_font_size" . "px;} " .
			"xnav ul li a {padding: " . $paddingTop . "px " .  $paddingBottom . "px;} " ,
			$this->apiHtml);

		//  font size
		$this->apiHtml = str_replace("<substitute-menu-font-size>", "xnav ul li a {font-size: $this->default_menu_font_size" . "px;}", $this->apiHtml);

		//  font family
		if ($this->default_menu_font_family != "")
		{
			$this->apiHtml = str_replace("<substitute-menu-font-family>", "xnav ul li a {font-family: $this->default_menu_font_family ;}", $this->apiHtml);
		}

		//  text color
		$this->apiHtml = str_replace("<substitute-menu-text-color>", "xnav ul li a, xnav ul li a:visited {color: $this->default_menu_text_color ;}", $this->apiHtml);
		//$this->apiHtml = str_replace("<substitute-menu-text-color>", "xnav ul li a {color: $this->default_menu_text_color ;}", $this->apiHtml);

		//  font weight
		$this->apiHtml = str_replace("<substitute-menu-font-weight>", "xnav ul li a {font-weight: $this->default_menu_font_weight ;}", $this->apiHtml);

		// align
		if ($this->default_orientation == "horizontal")
		{
			if ($this->default_align == "center")
				$this->apiHtml = str_replace("<substitute-align>", "xnav {text-align: $this->default_align;}", $this->apiHtml);
		}

		// item-margin
		if ($this->default_orientation == "horizontal")
		{
			if ($this->default_item_margin != "")
			{
				$left = ($this->default_item_margin / 2);
				$right = ($this->default_item_margin - $left);
				$this->apiHtml = str_replace("<substitute-item-margin>", "xnav ul li a {margin: 0px $left" . "px 0px $right" . "px;}", $this->apiHtml);
			}
		}

		// item-width. Variable, uniform or a px value. Dont have to do anything if 'variable'
		if ($this->default_orientation == "horizontal")	// Dont use this for vertical, it messes up the submenu positioning
		{
			if (is_numeric($this->default_item_width))					// px value
			{
				$this->apiHtml = str_replace("<substitute-item-width>",
					"xnav ul li a {width: $this->default_item_width" . "px;}" .
					"xnav ul ul li a {width: auto;}" ,
					$this->apiHtml);
			}
			if ($this->default_item_width == "uniform")
			{
				// This is set inline
				$this->apiHtml = str_replace("<substitute-item-width>", "xnav { width: 100% } <inline-item-width>", $this->apiHtml);
			}
		}

		// menu-rounding
		if ($this->default_menu_rounding != "")
		{
			if ($this->default_orientation == "horizontal")
				$element = "xnav";
			else
				$element = "xnav ul";
			$this->apiHtml = str_replace("<substitute-menu-rounding>",
				"$element {
					-moz-border-radius: " . $this->default_menu_rounding . "px;
					-webkit-border-radius: " . $this->default_menu_rounding . "px;
					border-radius: " . $this->default_menu_rounding . "px; /* future proofing */
					-khtml-border-radius: " . $this->default_menu_rounding . "px; /* for old Konqueror browsers */;
				}",	
			$this->apiHtml);
		}

		// menu-opacity
		if ($this->default_menu_opacity != "")
		{
			if ($this->default_orientation == "horizontal")
				$element = "xnav";
			else
				$element = "xnav ul";
			$this->apiHtml = str_replace("<substitute-menu-opacity>",	/* First level menu */
				"$element {
					zoom: 1; filter: alpha(opacity=" . $this->default_menu_opacity . ");
					opacity: " . ($this->default_menu_opacity / 100) . ";
				}",
				$this->apiHtml);
		}

		// menu-tile
		if ($this->default_menu_tile != "")
		{
			if ($this->default_orientation == "horizontal")
				$element = "xnav";
			else
				$element = "xnav ul";
			$this->apiHtml = str_replace("<substitute-menu-tile>",
				"$element {
					background: url('" . Yii::app()->baseUrl . $this->default_menu_tile . "') repeat;
				}",
			$this->apiHtml);

		}

		// menu-stretch
		if ($this->default_menu_stretch != "")
		{
			if ($this->default_orientation == "horizontal")
			{
				$element = "xnav";
				$item2 = "";
			}
			else
			{
				$element = "xnav ul li";
				$item2 = " ul ul li { background-size: 0%;} ";		// @@NB setting this to 0% to hide inheriting the image - may cause invisibility?
			}
			$this->apiHtml = str_replace("<substitute-menu-stretch>",
				"$element {
					background: url('" . Yii::app()->baseUrl . $this->default_menu_stretch . "');
					background-size: 100%;
					background-repeat: no-repeat;
					size: 100%;
				}
				$item2 ",
			$this->apiHtml);
		}

		// menu-color
		if ($this->default_menu_color != "")
		{
			if ($this->default_orientation == "horizontal")
			{
				$element = "xnav ul";
				$item2 = "";
			}
			else
			{
				$element = "xnav ul li";
				$item2 = "";				//  ul ul li { background-size: 0%;} ";	// @@NB weird invisibility possibility
			}
			$vals = explode(" ", $this->default_menu_color);
			if (count($vals == 1)) array_push($vals, $vals[0]);
			$this->apiHtml = str_replace("<substitute-menu-color>",
				"$element {
					background: " . $vals[0] . ";
					background: linear-gradient(top, " . $vals[0] . " 0%, " . $vals[1] . " 100%);  
					background: -moz-linear-gradient(top, " . $vals[0] . " 0%, " . $vals[1] . " 100%); 
					background: -webkit-linear-gradient(top, " . $vals[0] . " 0%, " . $vals[1] . " 100%); 
				}
				$item2 ",	
			$this->apiHtml);
		}

		// item-separator-*		(width, color, image)
		if (($this->default_item_separator_color != "") || ($this->default_item_separator_tile != "") || ($this->default_item_separator_stretch != ""))
		{
			$str = "xnav ul li {border:0;}";
			if ($this->default_orientation == "horizontal")
			{
				$borderStr = "left";
				$borderImageParams = " 0 0 0 $this->default_item_separator_width ";
			}
			else
			{
				$borderStr = "top";
				$borderImageParams = " $this->default_item_separator_width 0 0 0 ";
			}
			$str .= "xnav ul li + li {border-" . $borderStr . "-style: solid;}";
			$str .= "xnav ul li + li {border-" . $borderStr . "-width: " . $this->default_item_separator_width . "px;}";
			if ($this->default_item_separator_color != "")
				$str .= "xnav ul li + li {border-" . $borderStr . "-color: $this->default_item_separator_color ;}";
			// image
			if ($this->default_item_separator_tile != "")
			{
				$str .= "xnav ul li + li {-moz-border-image: url('". $this->default_item_separator_tile    . "') $borderImageParams repeat;}";
				$str .= "xnav ul li + li {     border-image: url('". $this->default_item_separator_tile    . "') $borderImageParams repeat;}";
			}
			if ($this->default_item_separator_stretch != "")
			{
				$str .= "xnav ul li + li {-moz-border-image: url('". $this->default_item_separator_stretch . "') $borderImageParams stretch;}";
				$str .= "xnav ul li + li {     border-image: url('". $this->default_item_separator_stretch . "') $borderImageParams stretch;}";
			}
			// submenu
			$str .= "xnav ul li ul li + li {border-" . $borderStr . " : 0px; }";	// @@ use this for the submenu image
			$this->apiHtml = str_replace("<substitute-item-separator-*>", $str, $this->apiHtml);
		}

		// edgepadding
		if ($this->default_edgepadding != "")
		{
			//$str = "xnav ul li a {padding-left: " . $this->default_edgepadding . "px; padding-right: " . $this->default_edgepadding . "px;}";
			$str = "xnav {padding-left: " . $this->default_edgepadding . "px; padding-right: " . $this->default_edgepadding . "px;}";
			$this->apiHtml = str_replace("<substitute-edgepadding>", $str, $this->apiHtml);
		}

		// menu hover text color
		if ($this->default_menu_hover_text_color != "")
		{
			//$str  = "xnav ul li:hover   { color: $this->default_menu_hover_text_color;}";
			$str  = "xnav ul li:visited:hover a, xnav ul li:hover a { color: $this->default_menu_hover_text_color ;}";
//k			$str .= "xnav ul li ul li a { color: #fff;}";	// @@NB inheritance @@NB not working
			$this->apiHtml = str_replace("<substitute-menu-hover-text-color>", $str, $this->apiHtml);
		}

		// menu hover background color
		if ($this->default_menu_hover_background_color != "")
		{
			//$str  = "xnav ul li:hover   { background-color: $this->default_menu_hover_background_color;}";
			$str  = "xnav ul li:hover a { background-color: $this->default_menu_hover_background_color;}";
//k			$str .= "xnav ul li ul li a { background-color: #fff;}";	// @@NB inheritance @@NB not working
			$this->apiHtml = str_replace("<substitute-menu-hover-background-color>", $str, $this->apiHtml);
		}

		//  menu hover font size
		$this->apiHtml = str_replace("<substitute-menu-hover-font-size>", "xnav ul li:hover a {font-size: $this->default_menu_hover_font_size" . "px;}", $this->apiHtml);

		//  menu hover font family
		if ($this->default_menu_hover_font_family != "")
		{
			$this->apiHtml = str_replace("<substitute-menu-hover-font-family>", "xnav ul li:hover a {font-family: $this->default_menu_hover_font_family ;}", $this->apiHtml);
		}

		//  menu hover font weight
		$this->apiHtml = str_replace("<substitute-menu-hover-font-weight>", "xnav ul li: hover a {font-weight: $this->default_menu_hover_font_weight ;}", $this->apiHtml);

		// menu selected text color
		if ($this->default_menu_selected_text_color != "")
		{
			//$str  = "xnav ul li:selected   { color: $this->default_menu_selected_text_color;}";
			$str  = "xnav .selected-item a { color: $this->default_menu_selected_text_color !important;}";
			$this->apiHtml = str_replace("<substitute-menu-selected-text-color>", $str, $this->apiHtml);
		}

		// menu selected background color
		if ($this->default_menu_selected_background_color != "")
		{
			//$str  = "xnav ul li:selected   { background-color: $this->default_menu_selected_background_color;}";
			$str  = "xnav .selected-item { background-color: $this->default_menu_selected_background_color;}";
			$this->apiHtml = str_replace("<substitute-menu-selected-background-color>", $str, $this->apiHtml);
		}

		//  menu selected font size
		$this->apiHtml = str_replace("<substitute-menu-selected-font-size>", "xnav .selected-item a {font-size: $this->default_menu_selected_font_size" . "px ;}", $this->apiHtml);

		//  menu selected font family
		if ($this->default_menu_selected_font_family != "")
		{
			$this->apiHtml = str_replace("<substitute-menu-selected-font-family>", "xnav .selected-item {font-family: $this->default_menu_selected_font_family ;}", $this->apiHtml);
		}

		//  menu selected font weight
		$this->apiHtml = str_replace("<substitute-menu-selected-font-weight>", "xnav .selected {font-weight: $this->default_menu_selected_font_weight ;}", $this->apiHtml);

// SUBMENU --------------------------------------------------------------------------------------------------------

		// height
		$paddingTop    = ($this->default_submenu_height - $this->default_submenu_font_size) / 2;
		$paddingBottom = ($this->default_submenu_height - $this->default_submenu_font_size - $paddingTop);
		$this->apiHtml = str_replace("<substitute-submenu-height>",
			"xnav ul li ul li a {height: $this->default_submenu_font_size" . "px;} " .
			"xnav ul li ul li a {padding: " . $paddingTop . "px " .  $paddingBottom . "px;} " ,
			$this->apiHtml);

		// submenu-width. Variable, uniform or a px value. Dont have to do anything if 'variable'
		//if ($this->default_orientation == "horizontal")	// Not sure if vertical should be set here as menu-width has the same meaning
		{
			if (is_numeric($this->default_submenu_width))					// px value
			{
				$this->apiHtml = str_replace("<substitute-submenu-width>",
					"xnav ul li ul li  { width: $this->default_submenu_width" . "px ;}" ,
					$this->apiHtml);
			}
		}

		//  submenu font size
		$this->apiHtml = str_replace("<substitute-submenu-font-size>", "xnav ul li ul li a {font-size: $this->default_submenu_font_size" . "px !important;}", $this->apiHtml);

		//  submenu font family
		if ($this->default_submenu_font_family != "")
		{
			$this->apiHtml = str_replace("<substitute-submenu-font-family>", "xnav ul li ul li a {font-family: $this->default_submenu_font_family !important ;}", $this->apiHtml);
		}

		//  submenu text color
		$this->apiHtml = str_replace("<substitute-submenu-text-color>", "xnav ul li ul li, xnav ul li ul li a, xnav ul li ul li a:visited {color: $this->default_submenu_text_color !important ;}", $this->apiHtml);

		//  submenu font weight
		$this->apiHtml = str_replace("<substitute-submenu-font-weight>", "xnav ul li ul li a {font-weight: $this->default_submenu_font_weight !important;}", $this->apiHtml);

		// submenu-background-tile
		if ($this->default_submenu_background_tile != "")
		{
			$this->apiHtml = str_replace("<substitute-submenu-background-tile>",
				"xnav ul li ul a { background: url('" . Yii::app()->baseUrl . $this->default_submenu_background_tile . "') repeat !important; }",
			$this->apiHtml);
		}

		// submenu-background-stretch
		if ($this->default_submenu_background_stretch != "")
		{
			$this->apiHtml = str_replace("<substitute-submenu-background-stretch>",
				"xnav ul li ul li a { background: url('" . Yii::app()->baseUrl . $this->default_submenu_background_stretch . "') ;
				 background-repeat: no-repeat ;
				 background-size: cover ;
				 size: cover ; }",
			$this->apiHtml);
		}

		// submenu-background-color
		if ($this->default_submenu_background_color != "")
		{
			$vals = explode(" ", $this->default_submenu_background_color);
			if (count($vals == 1)) array_push($vals, $vals[0]);
			$this->apiHtml = str_replace("<substitute-submenu-background-color>",
				"xnav ul li ul li a {
					background: " . $vals[0] . ";
					background: linear-gradient(top, " . $vals[0] . " 0%, " . $vals[1] . " 100%);  
					background: -moz-linear-gradient(top, " . $vals[0] . " 0%, " . $vals[1] . " 100%); 
					background: -webkit-linear-gradient(top, " . $vals[0] . " 0%, " . $vals[1] . " 100%); 
				}",
			$this->apiHtml);
		}

		// submenu-separator-*		(width, color, image)
		if (($this->default_submenu_separator_color != "") || ($this->default_submenu_separator_tile != "") || ($this->default_submenu_separator_stretch != ""))
		{
			$str = "xnav ul li ul li {border:0;}";
			$borderImageParams = " $this->default_submenu_separator_width 0 0 0 ";
			if ($this->default_orientation == "horizontal")
				$target = "xnav ul li ul li ";
			else
				$target = "xnav ul li ul li + li";
			$str .= "$target {border-top-style: solid;}";
			$str .= "$target {border-top-width: " . $this->default_submenu_separator_width . "px;}";
			if ($this->default_submenu_separator_color != "")
				$str .= "$target {border-top-color: $this->default_submenu_separator_color ;}";
			// image
			if ($this->default_submenu_separator_tile != "")
			{
				$str .= "$target {-moz-border-image: url('". $this->default_submenu_separator_tile    . "') $borderImageParams repeat;}";
				$str .= "$target {     border-image: url('". $this->default_submenu_separator_tile    . "') $borderImageParams repeat;}";
			}
			if ($this->default_submenu_separator_stretch != "")
			{
				$str .= "$target {-moz-border-image: url('". $this->default_submenu_separator_stretch . "') $borderImageParams stretch;}";
				$str .= "$target {     border-image: url('". $this->default_submenu_separator_stretch . "') $borderImageParams stretch;}";
			}
			$this->apiHtml = str_replace("<substitute-submenu-separator-*>", $str, $this->apiHtml);
		}



/****************************************************************

					$this->apiHtml = str_replace("<substitute-submenu-opacity>",	// Second level menu 
						"xnav ul ul {
							zoom: 1; filter: alpha(opacity='100');
							opacity: " . (100) . ";
						}",
						$this->apiHtml);
					break;

				case "Xsubmenu-opacity":
					$this->apiHtml = str_replace("<substitute-submenu-opacity>",
						"xnav ul ul {
							background-color: rgba(0,0,255,1.0);
						}",
						$this->apiHtml);
					break;

				case "menu-hover-background-image-stretch":
					if ($this->default_orientation != "horizontal")
					{
						$this->apiHtml = str_replace("<substitute-menu-hover-image-stretch>",
							"xnav ul li:hover {
								background: url('" . Yii::app()->baseUrl . $val . "');
								background-size: 100%;
								background-repeat: no-repeat;
								size: 100%;
							}",
							$this->apiHtml);
					}
					else
					{
						$this->apiHtml = str_replace("<substitute-menu-hover-image-stretch>",
							"xnav ul li:hover {
								background: url('" . Yii::app()->baseUrl . $val . "');
								Xbackground-size: 100%;
								background-repeat: no-repeat;
								Xsize: 100%;
							}",
							$this->apiHtml);
					}
					break;
****************************************************************/


		// Clear all <substitute> tags that werent overridden (inline done later)

		// HTML
		if (strstr($this->apiHtml, "<substitute-orientation>"))
			$this->apiHtml = str_replace("<substitute-orientation>", $this->default_orientation, $this->apiHtml);
		$this->apiHtml = str_replace("<substitute-width>", "", $this->apiHtml);
		$this->apiHtml = str_replace("<substitute-height>", "", $this->apiHtml);
		$this->apiHtml = str_replace("<substitute-menu-font-size>", "", $this->apiHtml);
		$this->apiHtml = str_replace("<substitute-menu-font-family>", "", $this->apiHtml);
		$this->apiHtml = str_replace("<substitute-menu-text-color>", "", $this->apiHtml);
		$this->apiHtml = str_replace("<substitute-menu-font-weight>", "", $this->apiHtml);
		$this->apiHtml = str_replace("<substitute-align>", "", $this->apiHtml);
		$this->apiHtml = str_replace("<substitute-item-width>", "", $this->apiHtml);
		$this->apiHtml = str_replace("<substitute-item-margin>", "", $this->apiHtml);
		$this->apiHtml = str_replace("<substitute-menu-rounding>", "", $this->apiHtml);
		$this->apiHtml = str_replace("<substitute-menu-opacity>", "", $this->apiHtml);
		$this->apiHtml = str_replace("<substitute-menu-tile>", "", $this->apiHtml);
		$this->apiHtml = str_replace("<substitute-menu-stretch>", "", $this->apiHtml);
		$this->apiHtml = str_replace("<substitute-menu-color>", "", $this->apiHtml);
		$this->apiHtml = str_replace("<substitute-item-separator-*", "", $this->apiHtml);
		$this->apiHtml = str_replace("<substitute-edgepadding>", "", $this->apiHtml);
		$this->apiHtml = str_replace("<substitute-menu-hover-text-color>", "", $this->apiHtml);
		$this->apiHtml = str_replace("<substitute-menu-hover-background-color>", "", $this->apiHtml);		
		$this->apiHtml = str_replace("<substitute-menu-hover-font-size>", "", $this->apiHtml);
		$this->apiHtml = str_replace("<substitute-menu-hover-font-family>", "", $this->apiHtml);
		$this->apiHtml = str_replace("<substitute-menu-hover-font-weight>", "", $this->apiHtml);
		$this->apiHtml = str_replace("<substitute-menu-selected-text-color>", "", $this->apiHtml);
		$this->apiHtml = str_replace("<substitute-menu-selected-background-color>", "", $this->apiHtml);		
		$this->apiHtml = str_replace("<substitute-menu-selected-font-size>", "", $this->apiHtml);
		$this->apiHtml = str_replace("<substitute-menu-selected-font-family>", "", $this->apiHtml);
		$this->apiHtml = str_replace("<substitute-menu-selected-font-weight>", "", $this->apiHtml);
		$this->apiHtml = str_replace("<substitute-submenu-height>", "", $this->apiHtml);
		$this->apiHtml = str_replace("<substitute-submenu-font-size>", "", $this->apiHtml);
		$this->apiHtml = str_replace("<substitute-submenu-font-family>", "", $this->apiHtml);
		$this->apiHtml = str_replace("<substitute-submenu-text-color>", "", $this->apiHtml);
		$this->apiHtml = str_replace("<substitute-submenu-font-weight>", "", $this->apiHtml);
		$this->apiHtml = str_replace("<substitute-submenu-background-tile>", "", $this->apiHtml);
		$this->apiHtml = str_replace("<substitute-submenu-background-stretch>", "", $this->apiHtml);
		$this->apiHtml = str_replace("<substitute-submenu-background-color>", "", $this->apiHtml);
		$this->apiHtml = str_replace("<substitute-submenu-separator-*", "", $this->apiHtml);
		$this->apiHtml = str_replace("<substitute-submenu-width", "", $this->apiHtml);

		$this->apiHtml = str_replace("<substitute-submenu-opacity>", "", $this->apiHtml);



		// JS

		// Substitute paths for includes
		$tmp = str_replace("<substitute-path>", $jellyRootUrl, $this->apiHtml);
		$this->apiHtml = $tmp;

		// Insert the data
		$levelItemCount = 0;

		// @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@
		//$content .= "<div id='xnav-container' style='width:100%; background-color:#6e99c0'>";	// @@TODO: remove
		// @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@

		$content .= "<ynav>";
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

			// Is this the selected page?
			$selectedItem = "";
			if ((isset($_GET['page'])) && (trim($_GET['page']) != ""))
			{
				if ($menuHeader->url == trim($_GET['page']))
					$selectedItem = " class = 'selected-item' ";
			}
			else
			{
				if ($menuHeader->home == 1)
					$selectedItem = " class = 'selected-item' ";
			}

			//$content .= "<li $selectedItem ><div> <a href='" . Yii::app()->request->baseUrl . "?layout=index&page=" . $menuHeader->url . "'>" . $menuHeader->title . "</a></div>";
			$content .= "<li><div $selectedItem > <a href='" . Yii::app()->request->baseUrl . "?layout=index&page=" . $menuHeader->url . "'>" . $menuHeader->title . "</a></div>";
			$levelItemCount++;

			// Now pick up all this item's subitems
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
					$content .= "<li><div> <a href='" . Yii::app()->request->baseUrl . "?layout=index&page=" . $menuItem->url . "'>" . $menuItem->title . "</a></div> </li>";
			endforeach;
			if ($l2 == true)
				$content .= "</ul>";
			$content .= "</li>";
		endforeach;

		$content .= "</ul>";
		$content .= "</nav>";

		// @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@
		//$content .= "</div>";				// Closing tag. See above
		// @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@

		// Do the inline substitutions (the ones that depended on the data)

		// item-width (if 'uniform')
		if ($this->default_item_width == "uniform")
		{
			$tmp = floor((100 / $levelItemCount)-1);
			$this->apiHtml = str_replace("<inline-item-width>",
				"xnav ul li { width: " . $tmp . "% ;}
				xnav ul li ul li { width: auto ;}" ,
				$this->apiHtml);
		}

		// Clear all <inline> tags that werent overridden

		$this->apiHtml = str_replace("<substitute-item-width>", "", $this->apiHtml);

		// Personalise this nav
		$role = rand();
		// css
		$cssFile =  dirname(__FILE__) . "/traditional_" . $this->default_orientation . ".css";
		$css = "<style>" . str_replace("nav", "nav[role='xnav-" . $role . "']", file_get_contents($cssFile)) . "</style>";
		// html
		$this->apiHtml = str_replace("xnav", "nav[role='xnav-" . $role . "']", $this->apiHtml);
		$content       = str_replace("<ynav>", "<nav role='xnav-" . $role . "'>", $content);
		// Prepend css to html
		$this->apiHtml = $css . $this->apiHtml;

		// Prepare to return to the caller

		$html = str_replace("<substitute-data>", $content, $this->apiHtml);
		$js = $this->apiJs;

		$retArr = array();
		$retArr[0] = $html;
		$retArr[1] = $js;
		return $retArr;
	}

	// This is the HTML code we generate for this addon. If any external js/css/etc is used, it must be pulled in here
	private $apiHtml = <<<END_OF_API_HTML

	<div id="jelly-tradntionalmenu-container">
		<!--Basic Menu includes -->
		<!-- <link rel="stylesheet" type="text/css" href="<substitute-path>/traditional_<substitute-orientation>.css" /> -->

		<style>
		<substitute-width>
		<substitute-height>
		<substitute-menu-font-size>
		<substitute-menu-font-family>
		<substitute-menu-text-color>
		<substitute-menu-font-weight>
		<substitute-align>
		<substitute-item-width>
		<substitute-item-margin>
		<substitute-menu-rounding>
		<substitute-menu-opacity>
		<substitute-menu-tile>
		<substitute-menu-stretch>
		<substitute-menu-color>
		<substitute-item-separator-*>
		<substitute-edgepadding>
		<substitute-menu-hover-text-color>
		<substitute-menu-hover-background-color>
		<substitute-menu-hover-font-size>
		<substitute-menu-hover-font-family>
		<substitute-menu-hover-font-weight>
		<substitute-menu-selected-text-color>
		<substitute-menu-selected-background-color>
		<substitute-menu-selected-font-size>
		<substitute-menu-selected-font-family>
		<substitute-menu-selected-font-weight>
		<substitute-submenu-height>
		<substitute-submenu-font-size>
		<substitute-submenu-font-family>
		<substitute-submenu-text-color>
		<substitute-submenu-font-weight>
		<substitute-submenu-background-tile>
		<substitute-submenu-background-stretch>
		<substitute-submenu-background-color>
		<substitute-submenu-separator-*>
		<substitute-submenu-width>

		<substitute-submenu-opacity>
		</style>

		<!--Basic Menu HTML-->
		<div id="traditionalmenu">
			<substitute-data>
		</div>
	</div>

END_OF_API_HTML;

	private $apiJs = <<<END_OF_API_JS
END_OF_API_JS;

}
?>
