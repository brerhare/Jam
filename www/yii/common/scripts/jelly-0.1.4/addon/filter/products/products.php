<?php
//error_reporting(E_ALL | E_STRICT);
/**
 * API for Product filter
 *
 * Notes
 * -----
 * This will use the width and height of your container
 */

class products
{
	//Defaults
	private $defaultDepartment = "";
    private $defaultWidth = "100%";

// Not used ...
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
				case "department":
					$department = $val;
					break;
				default:
					break;
			}
		}

		// Apply all defaults that werent overridden

		// HTML

        // JS
		if (strstr($this->apiJs, "<substitute-department>"))
		{
			$tmp = str_replace("<substitute-department>", $this->defaultDepartment, $this->apiJs);
			$this->apiJs = $tmp;
		}

        // This is kind of a standard replace
		$tmp = str_replace("<substitute-path>", $jellyRootUrl, $this->apiHtml);
        $this->apiHtml = $tmp;

		$retArr = array();
		$retArr[0] = $this->apiHtml;
		$retArr[1] = $this->apiJs;;
		return $retArr;
	}
	// @@TODO: Thought: maybe all sites use a jelly db, and each has their own table prefix? Could avoid a lot of hassle

	private $apiHtml = <<<END_OF_API_HTML

        <div id="jelly-product-filter-container">
kkkkkk
        </div>

END_OF_API_HTML;

	private $apiJs = <<<END_OF_API_JS

	jQuery(document).ready(function($){

	});

END_OF_API_JS;

}
?>
