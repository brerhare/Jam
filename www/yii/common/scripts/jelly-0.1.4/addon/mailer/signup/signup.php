<?php

/**
 * API for Download
 *
 * Notes
 * -----
 * No external code
 */

class signup
{
	//Defaults
	private $optionButtonColor = "grey";
	private $optionButtonTextColor = "white";

	public $apiOption = array(
	);

	/*
	 * Set up any code pre-requisites (onload/document-ready reqs)
	 * Apply options
	 * Return an array containing [0]HTML [1]JS
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
				case "buttoncolor";
					$this->optionButtonColor = $val;
					break;
				case "buttoncolour";
					$this->optionButtonColor = $val;
					break;
				case "buttontextcolor";
					$this->optionButtonTextColor = $val;
					break;
				case "buttontextcolour";
					$this->optionButtonTextColor = $val;
					break;
				default:
					// Not all array items are action items
			}
		}

		// Generate the content
		$content = "<div>";
		$content .= "<input type='text' title='Name' />";
		$content .= "<br/>";
		$content .= "<input type='text' title='Email' />";

		// Apply all substitutions
		// HTML
		$this->apiHtml = str_replace("<substitute-data>", $content, $this->apiHtml);

		// JS

		$retArr = array();
		$retArr[0] = $this->apiHtml;
		$retArr[1] = $this->apiJs;
		return $retArr;
	}

	private $apiHtml = <<<END_OF_API_HTML
		<substitute-data>
END_OF_API_HTML;

	private $apiJs = <<<END_OF_API_JS
END_OF_API_JS;

}
?>
