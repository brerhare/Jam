<?php

/**
 * API for Megamenu
 *
 * Notes
 * -----
 * None
 */

class megamenu
{
	//Defaults
	private $defaultAnchorText = "Menu";

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
				case "anchortext":
					$tmp = str_replace("<substitute-anchortext>", $val, $this->apiHtml);
					$this->apiHtml = $tmp;
					break;
				default:
					// Not all array items are action items
			}
		}

		// Apply all defaults that werent overridden
		// HTML
		if (strstr($this->apiHtml, "<substitute-anchortext>"))
		{
			$tmp = str_replace("<substitute-anchortext>", $this->defaultAnchorText, $this->apiHtml);
			$this->apiHtml = $tmp;
		}
		// JS

		// Substitute paths for includes
		$tmp = str_replace("<substitute-path>", $jellyRootUrl, $this->apiHtml);
		$this->apiHtml = $tmp;

		// Insert the data
		$content = "";
		$menuHeaders = ContentBlock::model()->findAll(array('order'=>'sequence'));
		$blockCount = 0;
		foreach ($menuHeaders as $menuHeader):
			if (($menuHeader->parent_id) || (!$menuHeader->active))
				continue;
			if (++$blockCount > 4)
			{
	    		$content .= "<br style='clear: left' />";
	    		$content .= "<br style='clear: left' />";
	    		$blockCount = 1;
			}
			$content .= "<div class='column'>";
			$content .= " <ul>";
			$content .= "<li><h3><a href='" . Yii::app()->request->baseUrl . "?layout=index&page=" . $menuHeader->url . "'>" . $menuHeader->title . "</a></h3></li>";
			$criteria = new CDbCriteria;
			$criteria->addCondition("parent_id = " . $menuHeader->id);
			$menuItems = ContentBlock::model()->findAll($criteria);
			foreach ($menuItems as $menuItem):
				if ($menuItem->active)
					$content .= "<li><a href='" . Yii::app()->request->baseUrl . "?layout=index&page=" . $menuItem->url . "'>" . $menuItem->title . "</a></li>";
			endforeach;
			$content .= " </ul>";
			$content .= "</div>";
		endforeach;



		$html = str_replace("<substitute-data>", $content, $this->apiHtml);
		$js = $this->apiJs;

		$retArr = array();
		$retArr[0] = $html;
		$retArr[1] = $js;
		return $retArr;
	}

	// @@TODO: Thought: maybe all sites use a jelly db, and each has their own table prefix? Could avoid a lot of hassle

	private $apiHtml = <<<END_OF_API_HTML

	<div id="jelly-megamenu-container">
		<!--Mega Menu-->
		<link rel="stylesheet" type="text/css" href="<substitute-path>/jkmegamenu.css" />
		<script type="text/javascript" src="<substitute-path>/jkmegamenu.js"></script>
		<script>
		<!--Mega Menu-->
		//jkmegamenu.definemenu("anchorid", "menuid", "mouseover|click");
		//jkmegamenu.definemenu("megaanchor", "megamenu1", "mouseover");
		jkmegamenu.definemenu("megaanchor", "megamenu1", "click");
		</script>

		<div style="position:relative; z-index:20000;">
			<div id="megaanchor1" style="z-index:20000; cursor: pointer; cursor: hand;">
				<!--Mega Menu Anchor-->


<!--
				<a onmouseover="javascript:recalcPos();" href=<substitute-path>"/index.php/'" id="megaanchor" style="color:#000000;"><substitute-anchortext></a>
-->
				<a onmouseover="javascript:recalcPos();" id="megaanchor" style="color:#000000;"><substitute-anchortext></a>



			</div>

			<!--Mega Menu Dropdown HTML-->
			<div id="megamenu1" class="megamenu" style="position:fixed; margin-top:20px; opacity:0.925">
					<br style="clear: left" /> <!--Break after 3rd column. Move this if desired-->
					<br style="clear: left" /> <!--Break after 3rd column. Move this if desired-->
					<br style="clear: left" /> <!--Break after 3rd column. Move this if desired-->
						<substitute-data>
				</div>
			</div>
		</div>

END_OF_API_HTML;

	private $apiJs = <<<END_OF_API_JS

	jQuery(document).ready(function($){
    	jkmegamenu.render($);
	})

	function recalcPos()
	{
		var width4Col = 190;
		var rectAnchor = document.getElementById("megaanchor1").getBoundingClientRect();
		var adjustLeft = (rectAnchor.left - width4Col);
		var adjustTop = (rectAnchor.bottom + 0);
		//alert(adjustTop);
		//document.getElementById("megamenu1").setAttribute("style", "left:" + (rectAnchor.left - width4Col) + "px;");
		document.getElementById("megamenu1").style.left = adjustLeft + "px";
		document.getElementById("megamenu1").style.top = adjustTop + "px";
	}
END_OF_API_JS;

}
?>
