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
	private $defaultValue = "900px";

	public $apiOption = array(
		"width" => "900px",
		"height" => "400px",
		"animation" => "fade | slide",
		"source" => "db | glob",
		"(db) sql" => "CarouselBlock::model()->findAll(array('order'=>'sequence'))",
		"(db) column" => "content",
		"(glob) pattern" => "/userdata/images/*.jpg",
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
				case "source":
					if ($val == "db")
					{
						// If db based content
						// @@NB: OI! hardcoded to jacquies here
						$carouselItems = CarouselBlock::model()->findAll(array('order'=>'sequence'));
						foreach ($carouselItems as $carouselItem):
							$content .= "<li>";
							$content .= $carouselItem->content;
							$content .= "</li>";
						endforeach;
					}
					else if ($val == "glob")
					{
						// get pattern
						$pattern = $options['pattern'];
						foreach (glob(Yii::app()->basePath . "/../" . $pattern) as $filename)
						{
							$content .= "<li>";
							$content .= "<img src='" . dirname($pattern) . "/". basename($filename) . "' style='float: none; margin: 0px;' alt=''>";
							$content .= "</li>";
						}
					}
					break;
				case "width":
					$tmp = str_replace("<substitute-width>", "width:" . $val . ";", $this->apiHtml);		// Optional field
					$this->apiHtml = $tmp;
					break;
				case "height":
					$tmp = str_replace("<substitute-height>", "height:" . $val . ";", $this->apiHtml);	// Optional field
					$this->apiHtml = $tmp;
					break;
				case "animation":
					$tmp = str_replace("<substitute-animation>", "'" . $val . "'", $this->apiJs);
					$this->apiJs = $tmp;
					break;
				default:
					// Not all array items are action items
			}
		}

		// Apply all defaults that werent overridden
		// HTML
		if (strstr($this->apiHtml, "<substitute-something>"))
		{
			$tmp = str_replace("<substitute-something>", $this->defaultValue, $this->apiHtml);
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
			    		$blockCount = 0;
					}
					$content .= "<div class='column'>";
					$content .= " <ul>";
					$content .= "<li><h3><a href='" . Yii::app()->request->baseUrl . "/index.php/site/page?url=" . $menuHeader->url . "'>" . $menuHeader->title . "</a></h3></li>";
					$criteria = new CDbCriteria;
					$criteria->addCondition("parent_id = " . $menuHeader->id);
					$menuItems = ContentBlock::model()->findAll($criteria);
					foreach ($menuItems as $menuItem):
						if ($menuItem->active)
							$content .= "<li><a href='" . Yii::app()->request->baseUrl . "/index.php/site/page?url=" . $menuItem->url . "'>" . $menuItem->title . "</a></li>";
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
		jkmegamenu.definemenu("megaanchor", "megamenu1", "mouseover");
		</script>

		<div class="span2" style="position:relative; z-index:20000;">
			<div style="XXXXXXposition:absolute; XXXXXXleft:388px; XXXXXXtop:30px">
				<!--Mega Menu Anchor-->
				<a href=<substitute-path>"/index.php/'" id="megaanchor" style="color:#000000;">Menu</a>
			</div>
			
			<!--Mega Menu Dropdown HTML-->
			<div id="megamenu1" class="megamenu" style="position: fixed; margin-top:20px; margin-left:-190px; opacity:0.925">

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

END_OF_API_JS;

}
?>
