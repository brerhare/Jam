<?php
class Jelly
{
    /**
     * Loads in the ini file specified in filename, and Jellifies the settings in
     * the associative multi-dimensional array
     *
     * @param string $filename          The filename of the ini file to parse
     * @return array
     * @throws Exception
     */

	//public $DEBUG = true;
	public $DEBUG = false;

	private $jellyRoot = "/";

	private $beginHeader = <<<END_OF_BEGINHEADER
	<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
	<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
	<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="language" content="en" />
	<script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js" ></script>
	<link rel="shortcut icon" href="http://resources.news.com.au/cs/newscomau/images/favicon.ico" type="image/x-icon" />
	<link rel="icon" href="http://resources.news.com.au/cs/newscomau/images/favicon.ico" type="image/x-icon" />
	<style type="text/css">
		html, body {
		height: 100%;
		padding: 0;
		margin: 0;
		border: 0;
	}
	</style>

END_OF_BEGINHEADER;

	private $endHeader = <<<END_OF_ENDHEADER
	</head>
	<body>\n
END_OF_ENDHEADER;

	private $stdFooter = <<<END_OF_FOOTER
	</body>
	</html>\n
END_OF_FOOTER;

	private $cssGlobalArray = array();
	private $cssDivArray = array();
	private $bodyArray = array();
	private $scriptArray = array();

	public function processData($jellyArray, $jellyRoot)
	{
		$this->jellyRoot = $jellyRoot;
		$this->logMsg("-------------------------\n", 0);
		$this->logMsg("Tagging all top-level orphan blobs\n", 0);
		foreach ($jellyArray as $name => $value)
		{
			$this->logMsg($name . "\n", 1);
			if ((is_array($value)) && (array_key_exists("child", $value)))
			{
				$this->logMsg("child : " . $value['child'] . "\n", 2);
				$arrChild = explode(",", $value['child']);
				foreach ($arrChild as $child)
				{
					$chkChild = trim($child);
					if (array_key_exists($chkChild, $jellyArray))
						$jellyArray[$chkChild]['_parent'] = $name;
						//unset($jellyArray[$chkChild]);
				}
			}
		}
		$this->logMsg("-------------------------\n");
		array_push($this->scriptArray, "<script>\n");

		foreach ($jellyArray as $name => $value)
		{
			if (is_array($value) && (!(array_key_exists("_parent", $value))))
			{
				$this->blobProcess($jellyArray, $name, $value, false);
			}
			else
			{
				$this->logMsg("Skipping blob '" . $name . "'\n");
			}
		}
		array_push($this->scriptArray, "</script>\n");
	}

	public function outputData()
	{
		$this->emit($this->beginHeader);
		$this->emit("\n<!-- CSS start -->\n<style type='text/css'>\n");
		foreach ($this->cssGlobalArray as $css)
			$this->emit($css);
		foreach ($this->cssDivArray as $css)
			$this->emit($css);
		$this->emit("</style>\n<!-- CSS end -->\n");
		$this->emit($this->endHeader);
		foreach ($this->bodyArray as $body)
			$this->emit($body);
		foreach ($this->scriptArray as $script)
			$this->emit($script);
		$this->emit($this->stdFooter);
	}

	/**
	 * Process (usually display) a blob
	 */
	private function blobProcess($jellyArray, $blobName, $array, $float, $indentLevel = 0)
	{
		$this->logMsg("Processing tagged blob '" . $blobName . "'\n");
		$this->genInlineHtml("<div id='" . $blobName . "'>\n", $indentLevel);
		$this->genDivCSS("div#" . $blobName ." {\n");
		if ($float)
			$this->genDivCSS("float: left;\n");

		$children = array();
		$floatChildren = false;
		if ((array_key_exists("stacking", $array)) && ($array["stacking"] == "horizontal"))
			$floatChildren = true;

		foreach ($array as $name => $value)
		{
			if (trim($name) == 'child')
			{
				$ch = explode(",", $value);
				foreach ($ch as $c)
					array_push($children, trim($c));
			}
			else
			{
				if ($name[0] != "_")
					$this->wordArrayHandler($blobName, $name, $value);	// Called once for each top-level word, eg 'css'
			}
		}
		$this->genDivCSS("}\n");

		// Now recurse for each child
		foreach ($children as $child)
		{
			//logMsg("Processing child blob " . $child . "\n");
			foreach ($jellyArray as $name => $value)
			{
				if ($name == $child)
				{
					$this->blobProcess($jellyArray, $child, $value, $floatChildren, ++$indentLevel);
					--$indentLevel;
					break;
				}
			}
		}

		$this->genInlineHtml("</div> <!-- " . $blobName . " -->\n", $indentLevel);
		if ($floatChildren)
			$this->genInlineHtml("<div style='clear: both;'></div>");
	}

	/**
	 * Each blob has a single array for each word, containing the name=>value pairs. Some values will be further arrays
 	*/
	private function wordArrayHandler($blobName, $word, $value)
	{
		$this->logMsg("Handling " . $word . " with value " . $value . "\n", 1);
		switch ($word)
		{
			case "css":
				// Each blob has a div#blobname { } around ALL its css, and the name of the blob is the generated div's id
				// For example <div id='xyz'> would have css defined as -
				// div#xyz {
				// ...
				// }
				//
				// CSS lines can be either property=value or property.grouping=value
				//
				// No grouping example -'value' gets appended a semicolon
				// width = 100%           -->   width : 100% ;
				//
				// Grouping example - 'value' gets appended with a semicolon and wrapped in curly brackets
				// body,html.margin = 0   -->   body,html { margin : 0 ; }
				foreach ($value as $cssName => $cssValue)
				{
					$this->genDivCSS($cssName . ":" . $cssValue . ";\n");
				}
				break;
			case "style":
				foreach ($value as $cssName => $cssValue)
				{
					switch ($cssName)
					{
						case ("background-image"):
							$this->genDivCSS("background-image: url(" . $cssValue . ");\n
							background-size: cover;"); 
							break;
						case ("html");

							// @@TODO: NB: This only caters for one sub-level, eg 'style.html.h1.color = red'
							// @@TODO: Also, note the 2nd foreach - this is for syntax but only one nvp is ever expected
							foreach ($cssValue as $htmlTag => $htmlValue)
							{
								$tag = $this->getDupName($htmlTag);
								foreach ($htmlValue as $htmlSubTag => $htmlSubValue)
									$this->genGlobalCSS("#" . $blobName . " " . $htmlTag . "{ " . $htmlSubTag . " : " . $htmlSubValue . "; }\n");
							}


							break;
					}
				}
				break;
			case "html":	// NB: DUPS ALLOWED
				if (!is_array($value))
				{
					$this->genInlineHtml($value);
				}
				else
				{
					foreach ($value as $htmlTag => $htmlValue)
					{
						$tag = $this->getDupName($htmlTag);
						$this->genInlineHtml("<$tag>$htmlValue</$tag>");
					}
				}
				break;
			case "fx":
				foreach ($value as $cssName => $cssValue)
				{
					switch ($cssName)
					{
						case ("wallpaper-color"):
							$this->genGlobalCSS("html { background-color: " . $cssValue . ";
										-webkit-background-size: cover;
										-moz-background-size: cover;
										-o-background-size: cover;
										background-size: cover;		
										\n}\n"); 
							break;
						case "wallpaper-image":
							$this->genGlobalCSS("html { background: url(" . $cssValue . ") no-repeat center center fixed;
										-webkit-background-size: cover;
										-moz-background-size: cover;
										-o-background-size: cover;
										background-size: cover;			
										\n}\n"); 
							break;
						case "shadow":
							if ((!$cssValue) || ($cssValue == "default"))
								$cssValue = "0 0 20px black";
							$this->genDivCSS("-moz-box-shadow: " . $cssValue . "; -webkit-box-shadow: " . $cssValue . "; box-shadow: " . $cssValue . ";");
							break;
						case "opacity":
							if ((!$cssValue) || ($cssValue == "default"))
								$cssValue = "80%";
							$this->genDivCSS("zoom: 1; filter: alpha(opacity=" . $cssValue . "); opacity: " . ($cssValue / 100) . ";\n");
							break;
						case "rounding":
							if ((!$cssValue) || ($cssValue == "default"))
								$cssValue = "10px";
							$this->genDivCSS("-moz-border-radius: " . $cssValue . ";
									-webkit-border-radius: " . $cssValue . ";
									border-radius: " . $cssValue . "; /* future proofing */
									-khtml-border-radius: " . $cssValue . "; /* for old Konqueror browsers */\n");
					}
				}
				break;
			case "addon":
				$this->addonHandler($value);
				break;
			default:
				$this->logMsg("*** No handler for '" . $word . "'\n", 1);
		}
	}

	// @@TODO: This has been separated from the switch-case as it is intended for it to be recursive.
	// At the moment it has a fixed depth and structure of hierarchy, very limited
	private function addonHandler($value)
	{
		$path = $this->jellyRoot . "addon";
		$optArr = array();

		foreach ($value as $k => $v)	// just one
		{
			$path .= "/" . $k;	// 'carousel'
			foreach ($v as $k => $v)	// just one
			{
				$path .= "/" . $k;	// 'flexslider'
				// We have reached the directory location
				require($path . "/api.php");
				foreach ($v as $k => $v)	// multiple options
					$optArr[$k] = $v;
				// Run the addon's API
				$addon = new api;
				$code = $addon->init($optArr);
				$this->genInlineHtml($code[0]);		// Local html generated by the addon
				$this->genScript($code[1]);			// js/jquery generated by the addon
			}
		}
	}

	/**
	 * Exactly a var_dump at the mo
	 *
 	*/
	private function dump($array)
	{
		foreach ($array as $name => $value)
		{
			if (is_array($value))
			{
				$this->logMsg($name . "->\n");
				dump($value);
			}
			else
			{
				$this->logMsg($name . ":" . $value . "\n");
			}
		}
	}

	private function logMsg($msg, $indentLevel=0)
	{
		if ($this->DEBUG)
		{
			$indent = "";
			while ($indentLevel--)
				$indent .= "    ";
			echo  nl2br($indent . $msg);
		}
	}

	private function genDivCSS($content)
	{
		if (!$this->DEBUG)
			array_push($this->cssDivArray, $content);
	}

	private function genGlobalCSS($content)
	{
		if (!$this->DEBUG)
			array_push($this->cssGlobalArray, $content);
	}

	private function genInlineHtml($content, $indentLevel=0)
	{
		if (!$this->DEBUG)
		{
			$indent = "";
			while ($indentLevel--)
				$indent .= "    ";
			array_push($this->bodyArray, $indent . $content);
		}
	}

	// Any js goes here. It will be wrapped in <script> tags when emitted.
	// Use this for stuff like jQuery(document).ready etc
	private function genScript($content, $indentLevel=0)
	{
		if (!$this->DEBUG)
		{
			$indent = "";
			while ($indentLevel--)
				$indent .= "    ";
			array_push($this->scriptArray, $indent . $content);
		}
	}

	private function emit($content)
	{
		if (!$this->DEBUG)
			echo $content;
	}

	private function getDupName($string)
	{
		$arr = explode("___", $string);
		return $arr[0];
	}

}

?>
