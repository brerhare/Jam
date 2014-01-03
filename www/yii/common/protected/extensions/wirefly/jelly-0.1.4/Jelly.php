<?php

/*********************/
ini_set('display_errors',1);
error_reporting(E_ALL);
/*********************/

header("Content-Type: text/html; charset=UTF-8");

$old_error_handler = set_error_handler("myErrorHandler");
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

	private $blobUniqueId = 0;

	private $jellyRootPath = "/";
	private $jellyRootUrl = "/";

	// The @ things - clipboard and array of others
	private $clipBoard = "";
	private $homePage = 0;

	// Addons used in multiple places will want only 1 copy of their js/css/whatever loaded. In this case they send it as global, and we need to make sure no addon globals are included more than once
	private $addonHeaderPushes = array();

	private $beginHeader = <<<END_OF_BEGINHEADER
	<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
	<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
	<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="language" content="en" />




	<style>		/* This style block is for the CSS reset */
	/* http://meyerweb.com/eric/tools/css/reset/ 
	   v2.0 | 20110126
	   License: none (public domain)
	*/
	/*************/
	html, body, div, span, applet, object, iframe,
	Xh1, Xh2, Xh3, Xh4, Xh5, Xh6, Xp, Xblockquote, Xpre,
	a, abbr, acronym, address, big, cite, code,
	del, dfn, em, img, ins, kbd, q, s, samp,
	small, strike, strong, sub, sup, tt, var,
	Xb, Xu, Xi, Xcenter,
	dl, dt, dd, Xol, Xul, Xli,
	fieldset, form, label, legend,
	Xtable, caption, Xtbody, Xtfoot, Xthead, Xtr, Xth, Xtd,
	article, aside, canvas, details, embed, 
	figure, figcaption, footer, header, hgroup, 
	menu, nav, output, ruby, section, summary,
	time, mark, audio, video {
		margin: 0;
		padding: 0;
		border: 0;
		font-size: 100%;
		font: inherit;
		ertical-align: baseline;
	}
	/* HTML5 display-role reset for older browsers */
	article, aside, details, figcaption, figure, 
	footer, header, hgroup, menu, nav, section {
		display: block;
	}
	body {
		line-height: 1;
	}
	Xol, Xul {
		list-style: none;
	}
	Xblockquote, Xq {
		quotes: none;
	}
	Xblockquote:before, Xblockquote:after,
	Xq:before, Xq:after {
		content: '';
		content: none;
	}
	Xtable {
		border-collapse: collapse;
		border-spacing: 0;
	}
	/**************************/
	</style>

	<script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js" ></script>
	<link rel="shortcut icon" href="//resources.news.com.au/cs/newscomau/images/favicon.ico" type="image/x-icon" />
	<link rel="icon" href="//resources.news.com.au/cs/newscomau/images/favicon.ico" type="image/x-icon" />
	
END_OF_BEGINHEADER;

	private $endHeader = <<<END_OF_ENDHEADER
	</head>
	<body>\n
END_OF_ENDHEADER;

	private $stdFooter = <<<END_OF_FOOTER
	</body>
	</html>\n
END_OF_FOOTER;

	private $dbTable = array();
	private $dbError = array();

	private $headerArray = array();	// Anything for header goes in here

	private $cssGlobalArray = array();
	private $cssDivArray = array();
	private $bodyArray = array();
	private $scriptArray = array();

	public function processData($jellyArray, $jellyRoot)
	{
		$this->jellyRootPath = Yii::app()->basePath . "/../" . $jellyRoot;
		$this->jellyRootUrl  = Yii::app()->baseUrl . $jellyRoot;

		$this->logMsg("... Looking for blobs ...\n\n", 0);
		foreach ($jellyArray as $name => $value)
		{
			$this->logMsg('Found ' . $name . "\n", 1);

			// Check if we have a contentBlock page anywhere in the file, and if so determine if its a homepage
			// If so, set @HOMEPAGE ($this->homePage) variable
			if ((is_array($value)) && (array_key_exists("db", $value)))
			{
				$arrDb = $value['db'];
				if (array_key_exists("filter", $arrDb))
				{
					if (isset($_GET['page']))
					{
						if ((array_key_exists("table", $arrDb)) && ($arrDb['table'] == 'ContentBlock'))
						{
							// Determine whether its the home page
							$criteria = new CDbCriteria;
							$criteria->addCondition("url = '" . $_GET['page'] . "'");
							$criteria->addCondition("home = " . 1);
							$contentBlock = ContentBlock::model()->find($criteria);
							if ($contentBlock)
								$this->homePage = 1;
						}
					}
					else
						// No page asked - we will serve up the home page
						$this->homePage = 1;
//				echo $this->homePage;
				}
			}

			if ((is_array($value)) && (array_key_exists("child", $value)))
			{
				//$this->logMsg("child : " . $value['child'] . "\n", 2);
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

		//$this->logMsg("-------------------------\n");
		$this->logMsg("\n... Digging through top-level orphan entrypoints ...\n\n", 0);
		array_push($this->scriptArray, "<script>\n");

		foreach ($jellyArray as $name => $value)
		{
			if (is_array($value) && (!(array_key_exists("_parent", $value))))
			{
					$this->blobProcess($jellyArray, $name, $value, false);
			}
			else
			{
				$this->logMsg("Skipping " . $name . "\n");
			}
		}
		array_push($this->scriptArray, "</script>\n");
	}

	public function outputData()
	{
		$this->emit($this->beginHeader);

		if (file_exists($this->jellyRootPath . 'header.html'))
			$this->emit(file_get_contents($this->jellyRootPath . 'header.html'));

		foreach ($this->headerArray as $hdr)
			$this->emit($hdr);

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
		// Search array for repeating fields - we'll generate an instance of this blob for each
		$hasRepeatingField = false;

		// Skip over 'condition' blobs that fail their condition @@TODO only @HOMEPAGE presently checked
		if (array_key_exists("condition", $array))
		{
			//die('x='.$array['condition']);
			if (strstr($array['condition'], "=1"))
			{
				if ($this->homePage == "0")
					return;
			}
			else if (strstr($array['condition'], "=0"))
			{
				if ($this->homePage == "1")
					return;	
			}
		}

		foreach ($array as $name => $value)
		{
			// @@TODO: The following code needs to reuse the (almost) same code in the jelly-word processing switch/case
			if (trim($name) == 'db')
			{
				$dbTable = '';
				$fltArr = array();
				$orderArr = array();
				foreach ($value as $dbAction => $dbValue)
				{
					switch ($dbAction)
					{
						case ("fetch"):
							if ($dbValue == "multiple")
								$hasRepeatingField = true;
							break;
						case ("table"):
							$dbTable = $dbValue;
							break;
						case ("filter"):
							$fltCommaArr = explode(",", $dbValue);
							foreach ($fltCommaArr as $elemComma)
							{
								$tmpArr = explode("=", $elemComma);
								if (count($tmpArr)>1)
								{
									// Look for homepage required
									if (strstr($elemComma, '$_GET'))
									{
										if (strstr($tmpArr[1], "page"))
										{
											$fltArr = array();
											$fltArr[0]='home=1';
											break;
										}
									}

									// Look for clipboard. NB this is id=1 or id=2 or ... etc
									if (strstr(trim($elemComma) ,'@CLIPBOARD'))
									{
										$prodList = "";
										$clipArr = explode("|", $this->clipBoard);
										for ($i = 0; $i < count($clipArr); $i++)
										{
											if ($prodList != "") $prodList .= " or ";
											$prodList .= $tmpArr[0] . "=" . $clipArr[$i];	

										}
										$elemComma = $prodList;
									}

								}
								array_push($fltArr, $elemComma);
							}
							break;

						case ("filtercomplex"):
						// @@TODO WILD SEASONS. THIS IS THE EVENT TABLE
							if ((isset($_GET['date'])) && ($_GET['date'] != ''))
							{
								$dtArr = explode('-', $_GET['date']);
								$dt = $dtArr[2] . '-' . $dtArr[1] . '-' . $dtArr[0];
								array_push($fltArr, "start >= '" . $dt . "'");
							}


							break;

						case ("order"):
							$orderCommaArr = explode(",", $dbValue);
							foreach ($orderCommaArr as $elemComma)
								array_push($orderArr, $elemComma);
							break;
						case ("error"):
							$error = $dbValue;
							break;
					}
				}

                if ($hasRepeatingField)
                {
                	$validQuery = true;
                    // Build the query from the collected args
                    $query = $dbTable . '::model()->findAll($cri);';
                    // Add filters, checking for validity
					$cri=new CDbCriteria;
					foreach ($fltArr as $flt)
					{
						$chkArr = explode("=", trim($flt));
						if ($chkArr[1] ==  '')
							$validQuery = false;
						$cri->addCondition($this->dbExpand(trim($flt)));
					}
                    // Add order
                    foreach ($orderArr as $ord)
						$cri->order = $this->dbExpand(trim($ord));

                    // Do the query
                    $q = "return " . $query . ";";
Yii::log("REPEATING EVAL = " . $query , CLogger::LEVEL_WARNING, 'system.test.kim');
					if ($validQuery)
	                    $resp = eval($q);
	                else
	                	$resp = null;
                    if ($resp)
                    {
                        // Generate blobs for each iteration
                        foreach ($resp as $r)
                        {


/*******************/
							// @@TODO WILD SEASONS hardcoding
							if ((isset($_GET['grade'])) && ($_GET['grade'] != ''))
							{
								die('x');
    							$criteria = new CDbCriteria;
								$criteria->addCondition("event_id = " . $r->id);
								$Ws = Ws::model()->find($criteria);
								if (!($Ws))
									continue;
								$gradeArr = explode('|', $_GET['grade']);
								//die(count($gradeArr));
								if (count($gradeArr) > 0)
									if (!in_array($Ws->grade, $gradeArr))
										continue;

							}
/********************/
							/* @@TODO THIS IS HALF DONE. CANT WORK LIKE GRADE ABOVE AS IS MANY-MANY
							          SO ITS COMMENTED OUT FOR NOW
							if ((isset($_GET['pb'])) && ($_GET['pb'] != ''))
							{
    							$criteria = new CDbCriteria;
								$criteria->addCondition("event_id = " . $r->id);
								$Ws = Ws::model()->find($criteria);
								if (!($Ws))
									continue;
								$gradeArr = explode('|', $_GET['pb']);
								//die(count($gradeArr));
								if (count($gradeArr) > 0)
									if (!in_array($Ws->grade, $gradeArr))
										continue;

							}
							*/

                            // Store the handle for this record
                            $this->dbTable[$dbTable] = $r;
                            $this->blobProcess2($jellyArray, $blobName, $array, $float, $indentLevel);
                        }
                    }
                    else
                    {
                    	// error is a currently no-op for 'findAll()'
                    }
                }
			}
		}

		if (!($hasRepeatingField))
			$this->blobProcess2($jellyArray, $blobName, $array, $float, $indentLevel);
	}

	private function blobProcess2($jellyArray, $blobName, $array, $float, $indentLevel = 0)
	{
		$blobName .= $this->blobUniqueId++;

// @@TODO: remove this hardcoding
/*****************************************************************/
if (isset($_GET['page']))
{
 if ($_GET['page'] != 'Jacquies-Beauty-Dumfries-Salon')
 {
  if ((substr($blobName,0,4) == 'tabs') || (substr($blobName,0,13) == 'tabscontainer'))
   return;
  }
}
/*****************************************************************/
		$this->logMsg($blobName . "\n", $indentLevel);

		// Is this entire blob clickable?
		if (array_key_exists("click", $array))
			$this->genInlineHtml("<a href=" . $this->dbExpand(trim($array['click'])) . ">\n", $indentLevel);

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
			//$this->logMsg("Processing child blob " . $child . "\n", $indentLevel);
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

		if (array_key_exists("click", $array))
			$this->genInlineHtml("</a>\n", $indentLevel);

		if ($floatChildren)
			$this->genInlineHtml("<div style='clear: both;'></div>");
	}

	/**
	 * Each blob has a single array for each word, containing the name=>value pairs. Some values will be further arrays
 	*/
	private function wordArrayHandler($blobName, $word, $value)
	{
		//$this->logMsg("Handling " . $word . " with value " . $value . "\n", 1);
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
							$img = $this->dbExpand($cssValue);
							$this->genDivCSS("background-image: url('" . Yii::app()->baseUrl . str_replace("'", "\'", $img) . "');\n
							background-size: contain;
							background-repeat:no-repeat;
							background-position:center;
							"); 
							break;
						case ("background-image-bottom"):
							$img = $this->dbExpand($cssValue);
							$this->genDivCSS("background-image: url('" . Yii::app()->baseUrl . str_replace("'", "\'", $img) . "');\n
							background-size: contain;
							background-repeat:no-repeat;
							background-position:bottom;
							"); 
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
					$this->genInlineHtml($this->dbExpand($value));
				}
				else
				{
					foreach ($value as $htmlTag => $htmlValue)
					{
						$tag = $this->getDupName($htmlTag);
						if ($tag != 'raw')
							$this->genInlineHtml("<$tag>" . $this->dbExpand($htmlValue) . "</$tag>");
						else
							$this->genInlineHtml($this->dbExpand($htmlValue));
					}
				}
				break;
			case "image":
				$url = "";
				$alt = "";
				$width = "0";
				$height = "0";
				$tip = "";
				foreach ($value as $prop => $val)
				{
					switch ($prop)
					{
						case ("url"):
							$url = $val;
							break;
						case ("alt"):
							$alt = $val;
							break;
						case ("width"):
							$width = $val;
							break;
						case ("height"):
							$height = $val;
							break;
					}
				}
				if ($alt == "")
					$this->genInlineHtml('<img title="' . $tip . '" src="' . $this->dbExpand($url) . '" onerror="this.style.display=\'none\'" . " width="' . $width . '" height="' . $height . '">');
				else
					$this->genInlineHtml('<img title="' . $tip . '" src="' . $this->dbExpand($url) . '" onerror="this.onerror=null;this.src=\'' . $this->dbExpand($alt) . '\'" width="' . $width . '" height="' . $height . '">');
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
							$this->genGlobalCSS("html { background: url(" . Yii::app()->baseUrl  . $cssValue . ") no-repeat center center fixed;
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
			case "db":
				$dbTable = '';
				$fltArr = array();
				$orderArr = array();
				$error = '';
				foreach ($value as $dbAction => $dbValue)
				{
					switch ($dbAction)
					{
						case ("fetch"):
							if ($dbValue == "multiple")
								return;		// We break right out of here if we find its a multiple
							break;
						case ("table"):
							$dbTable = $dbValue;
							break;
						case ("filter"):
							$fltCommaArr = explode(",", $dbValue);
							foreach ($fltCommaArr as $elemComma)
							{
								$tmpArr = explode("=", $elemComma);
								if (count($tmpArr)>1)
								{
									// Look for homepage required
									if (strstr($elemComma, '$_GET'))
									{
										if (strstr($tmpArr[1], "page"))
										{
											$fltArr = array();
											$fltArr[0]='home=1';
											break;
										}
									}

									// Look for clipboard. NB this is id=1 or id=2 or ... etc
									if (strstr(trim($elemComma) ,'@CLIPBOARD'))
									{
										$prodList = "";
										$clipArr = explode("|", $this->clipBoard);
										for ($i = 0; $i < count($clipArr); $i++)
										{
											if ($prodList != "") $prodList .= " or ";
											$prodList .= $tmpArr[0] . "=" . $clipArr[$i];	

										}
										$elemComma = $prodList;
									}

								}
								array_push($fltArr, $elemComma);
							}
							break;

						case ("filtercomplex"):
						// THIS IS THE WS TABLE
							if (isset($_GET['grade']))
							{
								//array_push($fltArr, "gradex='" . $_GET['grade'] . "'");
															//	die(var_dump($fltArr));
							}

							break;


						case ("order"):
							$orderCommaArr = explode(",", $dbValue);
							foreach ($orderCommaArr as $elemComma)
								array_push($orderArr, $elemComma);
							break;
						case ("error"):
							$error = $dbValue;
							break;
					}
				}


                // Build the query from the collected args
                $query = $dbTable . '::model()->find($cri);';
                // Add filters
				$cri=new CDbCriteria;
				foreach ($fltArr as $flt)
					$cri->addCondition($this->dbExpand(trim($flt)));
                // Add order
                foreach ($orderArr as $ord)
				$cri->order = $this->dbExpand(trim($ord));
                // Do the query
                $q = "return " . $query . ";";
Yii::log("EVAL = " . $query , CLogger::LEVEL_WARNING, 'system.test.kim');
                $resp = eval($q);
                $this->dbTable[$dbTable] = $resp;
				$this->dbError[$dbTable] = '';
                if (!($resp))
				{
					if ($error != '')
						$this->dbError[$dbTable] = $error;
					else
						$this->dbError[$dbTable] = '0';
				}
				break;
			case "addon":
				$this->addonHandler($value);
				break;
			default:
				$this->logMsg("*** No handler for '" . $word . "'\n", 1);
		}
	}

	// @@TODO: This has been separated from the switch-case as it is intended to be recursive.
	// At the moment it has a fixed depth and structure of hierarchy, very limited
	private function addonHandler($value)
	{
//var_dump($value);
//echo '<br><br>';

		$path = $this->jellyRootPath . "addon";
		$url = $this->jellyRootUrl . "addon";
		$optArr = array();

		foreach ($value as $k => $v)	// just one
		{
			$path .= "/" . $k;	// 'carousel'
			$url .= "/" . $k;	// 'carousel'
			foreach ($v as $k => $v)	// just one
			{
				$path .= "/" . $k;	// 'flexslider'
				$url .= "/" . $k;	// 'flexslider'
				$className = $k;
				// We have reached the directory location
				foreach ($v as $k => $v)	// multiple options
					$optArr[$k] = $this->dbExpand($v);

				// Run the addon's API
				include_once($path . "/" . $className . ".php");
				$addon = new $className;
				$code = $addon->init($optArr, $url);
				$this->genInlineHtml($code[0]);		// Local html generated by the addon
				$this->genScript($code[1]);			// js/jquery generated by the addon
				if ((count($code) > 2) && (trim($code[2]) != ""))	// Clipboard content returned by the addon 
				{
					$this->clipBoard = $code[2];
				}
				if ((count($code) > 3) && (trim($code[3]) != ""))	// Header content returned by the addon
				{
					if (!in_array($code[3], $this->addonHeaderPushes))
					{
						array_push($this->headerArray, $code[3]);
						array_push($this->addonHeaderPushes, $code[3]);
					}
				}
				// Important! unset the class, as multiple records will re-declare it, otherwise crashing
				unset($addon);
			}
		}
	}

	// Convert a 'Department.name' to the actual value, using the table-handle table
	private function dbExpand($str)
	{
		foreach ($this->dbTable as $table => $handle)		// finds table 'x'
		{
			$keepTrying = true;
			while ($keepTrying)
			{
				if ($tableData = strstr($str, $table . "."))		// [matches] stuff like 'my [Product.]id=1 and blah'
				{													// returns 'Product.id=1 and blah'
					$val = explode(".", $tableData);				// array{'Product', 'id=1 and blah'}
					if (count($val) < 2)	// ie no dots
						return $str;
					if (strstr($val[1], " "))
					{
						$v = explode(" ", $val[1]);
						$val[1] = $v[0];							// 'id'
					}
					$resp = null;
					if ($handle)
					{
						$query = 'return ' . '$this->dbTable["' . $val[0] . '"]->' . $val[1] . ';';
						$resp = eval($query);
					}
					else
					{
						if ($this->dbError[$table] != '')
						{
							$resp = $this->dbError[$table];
							$keepTrying = false;
						}
					}
					// Embedded table.name's must have a trailing space unless theyre at end of line
					$tData = explode(" ", $tableData);
					if (count($tData > 1))
					$tableData = $tData[0];
	
					$str = str_replace($tableData, $resp, $str);
				}
				else
					$keepTrying = false;
			}
		}
		return $str;
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
				$indent .= "&nbsp&nbsp&nbsp&nbsp";
			echo  nl2br($indent . $msg);
		}
	}

	private function genDivCSS($content)
	{
		array_push($this->cssDivArray, $content);
	}

	private function genGlobalCSS($content)
	{
		array_push($this->cssGlobalArray, $content);
	}

	private function genInlineHtml($content, $indentLevel=0)
	{
		// Translate any @CLIPBOARD's
		if (strstr($content, "@CLIPBOARD"))
			$content = str_replace("@CLIPBOARD", $this->clipBoard, $content);

		// Translate any curly wurleys
		$p1 = strstr($content, "{{");
		$p2 = strstr(substr($p1, 2), "}}", true);
		$pOrig = "{{" . $p2 . "}}";
		$vals = explode(" ", $p2);
		$type = $vals[0];

		if (stristr($vals[0], "gallery"))
		{
			// Eg: {{gallery}}
			// ----------------------------
			$addon = array(
				"gallery" => array(
					"fancybox" => array(
             			"source" => "db"
					)
				)
			);
			$this->addonHandler($addon);
			$content = str_replace($pOrig, "", $content);
		}

		if (stristr($vals[0], "department"))
		{
			// Eg: {{department 27 Guinot}}
			// ----------------------------
			$value = $vals[1];
			$iframe = '<iframe height="670" width="850" style="border:medium double rgb(255,255,255)" style="overflow-x:hidden; overflow-y:auto;" src="https://plugin.wireflydesign.com/product/?sid=' . Yii::app()->params['sid'] . '&amp;department=' . $value . '"></iframe>';
			$content = str_replace($pOrig, $iframe, $content);
		}
		if (stristr($vals[0], "preset"))
		{
			// Eg: {{preset}}
			// --------------
			$iframe = '<iframe height="670" width="850" style="border:medium double rgb(255,255,255)" style="overflow-x:hidden; overflow-y:auto;" src="https://plugin.wireflydesign.com/product/?layout=preset&sid=' . Yii::app()->params['sid'] . '&amp;preset=true"></iframe>';
			$content = str_replace($pOrig, $iframe, $content);
		}
		if (stristr($vals[0], "checkout"))
		{
			// Eg: {{checkout}}
			// --------------
			$util = new Util;;
			// Check required parameters are set up for gateway access
			if ((trim(Yii::app()->params['checkoutEmail']) == "")
			||  (trim(Yii::app()->params['checkoutName']) == "")
			||  (trim(Yii::app()->params['checkoutGatewayUser']) == "")
			||  (trim(Yii::app()->params['checkoutGatewayPassword']) == ""))
				die("Checkout needs gateway access to be set up in the configuration file");
			$iframe = '<iframe height="670" width="850" style="border:medium double rgb(255,255,255)" style="overflow-x:hidden; overflow-y:auto;" src="https://plugin.wireflydesign.com/product/?layout=checkout&sid=' . Yii::app()->params['sid'] . '&ge=' . Yii::app()->params['checkoutEmail'] . '&gn=' . Yii::app()->params['checkoutName'] . '&gu=' . urlencode($util->encrypt(Yii::app()->params['checkoutGatewayUser'])) . '&gp=' . urlencode($util->encrypt(Yii::app()->params['checkoutGatewayPassword'])) . '"></iframe>';
			$content = str_replace($pOrig, $iframe, $content);
		}
		else if (stristr($vals[0], "blog"))
		{
			// Eg: {{blog}}
			// ------------
			$iframe = '<iframe height="900" width="900" style="border:medium double rgb(255,255,255)" style="overflow-x:hidden; overflow-y:auto;" src="https://plugin.wireflydesign.com/blog/?sid=' . Yii::app()->params['sid'] . '"></iframe>';
			$content = str_replace($pOrig, $iframe, $content);
		}

		$indent = "";
		while ($indentLevel--)
			$indent .= "    ";
		array_push($this->bodyArray, $indent . $content);
	}

	// Any js goes here. It will be wrapped in <script> tags when emitted.
	// Use this for stuff like jQuery(document).ready etc
	private function genScript($content, $indentLevel=0)
	{
		$indent = "";
		while ($indentLevel--)
			$indent .= "    ";
		array_push($this->scriptArray, $indent . $content);
	}

	private function emit($content)
	{
		echo $content;
	}

	private function getDupName($string)
	{
		$arr = explode("___", $string);
		return $arr[0];
	}

}

// error handler function
function myErrorHandler($errno, $errstr, $errfile, $errline)
{
    if (!(error_reporting() & $errno)) {
        // This error code is not included in error_reporting
        return;
    }

    switch ($errno) {
    case E_USER_ERROR:
        echo "<b>My ERROR</b> [$errno] $errstr<br />\n";
        echo "  Fatal error on line $errline in file $errfile";
        echo ", PHP " . PHP_VERSION . " (" . PHP_OS . ")<br />\n";
        echo "Aborting...<br />\n";
        exit(1);
        break;

    case E_USER_WARNING:
        echo "<b>My WARNING</b> [$errno] $errstr<br />\n";
        break;

    case E_USER_NOTICE:
        echo "<b>My NOTICE</b> [$errno] $errstr<br />\n";
        break;

    default:
        echo "Unknown error type: [$errno] $errstr<br />\n";
        break;
    }

    /* Don't execute PHP internal error handler */
    return true;
}
?>
