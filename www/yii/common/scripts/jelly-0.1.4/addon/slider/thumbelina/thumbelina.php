<?php

/**
 * API for addons
 *
 * Notes
 * -----
 * None
 */

class thumbelina
{
	//Defaults
	private $defaultMode = 'vertical';		// 'vertical' or 'horizontal'
	private $defaultWidth = "900px";
	private $defaultHeight = "250px";

	/*
	 * Set up any code pre-requisites (onload/document-ready reqs)
	 * Apply options
	 * Return an array containing html[0] and js[1]
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
				case "mode":
					$this->defaultMode = $val;
					break;
				case "width":
					$this->defaultWidth = $val;
					break;
				case "height":
					$this->defaultHeight = $val;
					break;
				default:
					// Not all array items are action items
			}
		}

		// Apply all defaults that werent overridden
		// HTML
		if (strstr($this->apiHtml, "<substitute-width>"))
			$this->apiHtml = str_replace("<substitute-width>", "width:" . $this->defaultWidth . ";", $this->apiHtml);
		if (strstr($this->apiHtml, "<substitute-height>"))
			$this->apiHtml = str_replace("<substitute-height>", "height:" . $this->defaultHeight . ";", $this->apiHtml);
		$this->apiHtml = str_replace("<substitute-path>", $jellyRootUrl, $this->apiHtml);
		$this->apiHtml = str_replace("<substitute-data>", $content, $this->apiHtml);

		// JS
		$this->apiJs = str_replace("<substitute-path>", $jellyRootUrl, $this->apiJs);

		$retArr = array();
		$retArr[0] = $this->apiHtml;
		$retArr[1] = $this->apiJs;
		return $retArr;
	}

	// @@TODO: Thought: maybe all sites use a jelly db, and each has their own table prefix? Could avoid a lot of hassle

	private $apiHtml = <<<END_OF_API_HTML

        <div id="jelly-thumbelina-container">
            <!--Thumbelina Slider-->
            <link rel="stylesheet" href="<substitute-path>/thumbelina.css" type="text/css">
            <script src="<substitute-path>/thumbelina.js"></script>

            <div class="thumbelinaslider">
				<substitute-data>
            </div>
        </div>

END_OF_API_HTML;

	private $apiJs = <<<END_OF_API_JS

	// Any custom js and/or startup functions

END_OF_API_JS;

}
?>
