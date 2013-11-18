<?php

/**
 * API for Google analytics
 *
 * Notes
 * -----
 * None
 */

class google_analytics
{
	//Defaults
	// None

	// Globals
	private $clipBoard = "";

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
		$onReady = "";
		$inputMode = "";
		foreach ($options as $opt => $val)
		{
			switch ($opt)
			{
				case "ua":
					$tmp = str_replace("<substitute-ua>", strtoupper($val), $this->apiHeader);
					$this->apiHeader = $tmp;
					break;
				default:
					die("Script error - Google Analytics requires at least a UA code to function");
			}
		}

		// Apply all defaults that werent overridden
		// This addon has no defaults that can be overridden

		$html = $this->apiHtml;
		$js   = $this->apiJs;

		$retArr = array();
		$retArr[0] = $this->apiHtml;
		$retArr[1] = $this->apiJs;
		$retArr[2] = $this->clipBoard;
		$retArr[3] = $this->apiHeader;
		return $retArr;
	}

//---------------------------------------------------------------------------------------------------------

	private $apiHtml = <<<END_OF_API_HTML
END_OF_API_HTML;

	private $apiJs = <<<END_OF_API_JS
END_OF_API_JS;

	private $apiHeader = <<<END_OF_API_HEADER
	<script type="text/javascript">
		var _gaq = _gaq || [];
		_gaq.push(['_setAccount', '<substitute-ua>']);
		_gaq.push(['_trackPageview']);

		(function() {
			var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
			ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
			var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
			})();
	</script>
END_OF_API_HEADER;

}
?>
