<?php

//require ('deviceInfo.php');

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

	private $deviceWidth = 0;

	private $blobUniqueId = 0;
	private $isSettingDeviceWidth = 0;

	private $jamTemplateArr = array();

	private $jellyRootPath = "/";
	private $jellyRootUrl = "/";

	// The @ things - clipboard and array of others
	private $clipBoard = "";
	private $homePage = 0;
	private $metaTitle = "";
	private $metaDescription = "";
	private $metaKeywords = "";

	// Addons used in multiple places will want only 1 copy of their js/css/whatever loaded. In this case they send it as global, and we need to make sure no addon globals are included more than once
	private $addonHeaderPushes = array();

	private $beginHeader = <<<END_OF_BEGINHEADER
	<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
	<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
	<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="language" content="en" />
	<substitute-meta-title>
	<substitute-meta-description>
	<substitute-meta-keywords>
	<substitute-meta-special>
	<script src="//ajax.googleapis.com/ajax/libs/angularjs/1.2.19/angular.min.js"></script>

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
	strong { font-weight: bold; }		// @@ NB: Override for CKEditor
	/**************************/

	</style>

	<script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js" ></script>

<!-- Force favicon.ico -->
<link rel="shortcut icon" href="favicon.ico" type="image/x-icon" />
<link rel="icon" href="favicon.ico" type="image/x-icon" />
	
END_OF_BEGINHEADER;

	private $endHeader = <<<END_OF_ENDHEADER
	</head>

	<link rel="stylesheet" href="/css/jelly-fx.css" />

<!-- @@TODO Remove hardcoded LEAFLET leaflet -->
<!-- <link rel="stylesheet" href="http://cdn.leafletjs.com/leaflet-0.7.3/leaflet.css" /> -->
<!-- <script src="http://cdn.leafletjs.com/leaflet-0.7.3/leaflet.js"></script> -->

	<body ng-app>\n
END_OF_ENDHEADER;

	private $stdFooter = <<<END_OF_FOOTER

<script type="text/javascript" src="/js/iframeResizer.contentWindow.min.js"></script>
	<!-- Iframe resizer -->
	<script type="text/javascript" src="/js/jquery.iframeResizer.min.js"></script> 
    <script type="text/javascript">
        jQuery('iframe').iFrameSizer({ 
            log                    : true,  // For development
            autoResize             : true,  // Trigering resize on events in iFrame
            contentWindowBodyMargin: 8,     // Set the default browser body margin style (in px)
            doHeight               : true,  // Calculates dynamic height
            doWidth                : false, // Calculates dynamic width 
            enablePublicMethods    : true,  // Enable methods within iframe hosted page 
            interval               : 0,     // interval in ms to recalculate body height, 0 to disable refreshing
            scrolling              : false, // Enable the scrollbars in the iFrame
            callback               : function(messageData){ // Callback fn when message is received
                $('p#callback').html(
                    '<b>Frame ID:</b> '    + messageData.iframe.id + 
                    ' <b>Height:</b> '     + messageData.height +
                    ' <b>Width:</b> '      + messageData.width + 
                    ' <b>Event type:</b> ' + messageData.type
                );
            }
        }); 
        </script>

<!-- Handle postmessages -->
<!--  @@NB START POSTMESSAGE -->
<script>
	var eventMethod = window.addEventListener ? "addEventListener" : "attachEvent";
	var eventer = window[eventMethod];
	var messageEvent = eventMethod == "attachEvent" ? "onmessage" : "message";

	// Listen to message from child window
	eventer(messageEvent,function(e) {
  		//console.log('parent received message!:  ',e.data);
		var n = e.data.indexOf("^");
		if (n == -1)
			return;
		msgArr = e.data.split("^");
		if (msgArr[0] == "redirect")
			window.location = msgArr[1];
	},false);
</script>
<!-- @@NB END POSTMESSAGE -->

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

// -------------------------- Entry points -----------------------------

    // (1) Expand curly wurlies embedded in content (eg a plugin embeds an addon)

    public function expandContent($content, $jellyRoot)
    {
		if ($this->gotDeviceWidth() == -1)
			return;
        $this->jellyRootPath = Yii::app()->basePath . "/../" . $jellyRoot;
        $this->jellyRootUrl  = Yii::app()->baseUrl . $jellyRoot;
        $this->genInlineHtml($content, $indentLevel=0);
		$retString = "";
		foreach ($this->bodyArray as $body)
			$retString .= $body;	
		return($retString);
    }

    // (2) Process a jelly file that has been parsed into an array (The general use case - handling entire jelly files)

	public function processData($jellyArray, $jellyRoot)
	{
		if ($this->gotDeviceWidth() == -1)
			return;
		$this->jellyRootPath = Yii::app()->basePath . "/../" . $jellyRoot;
		$this->jellyRootUrl  = Yii::app()->baseUrl . $jellyRoot;

		// Hardcoded google analytics if any
		if (!(strstr(Yii::app()->getBaseUrl(true), "plugin")))
		{
			$this->logMsg("... Preprocessing Settings addon (Google Analytics) ...\n\n", 0);
			$criteria = new CDbCriteria;
			$criteria->addCondition("id = 1");
			$setting = JellySetting::model()->find($criteria);
			if ($setting)
			{
				if (trim($setting->analyticsUA) != "")
				{
					$addonCmd = "addon.analytics.google_analytics.ua = " . $setting->analyticsUA;
					$arr = array();
					$arr['analytics'] = array();
					$arr['analytics']['google_analytics'] = array();
					$arr['analytics']['google_analytics']['ua'] = $setting->analyticsUA;
					$this->addonHandler($arr);
				}
			}
		}

		$this->logMsg("... Looking for blobs ...\n\n", 0);
		foreach ($jellyArray as $name => $value)
		{
			$this->logMsg('Found ' . $name . "\n", 1);


			// Metatags for SEO
			// Get the requested page for its metadata
			if (!strstr(Yii::app()->db->connectionString, "plugin"))	// We dont do this for plugins!
			{
				if ((isset($_GET['page'])) && (trim($_GET['page']) != ""))
				{
					$criteria = new CDbCriteria;
					$criteria->addCondition("url = '" . $_GET['page'] . "'");
					$contentBlock = ContentBlock::model()->find($criteria);
					if ($contentBlock)
					{
						$this->metaTitle = trim($contentBlock->meta_title);
						$this->metaDescription = trim($contentBlock->meta_description);
						$this->metaKeywords = trim($contentBlock->meta_keywords);
					}
				}
				if (($this->metaTitle == "") && ($this->metaDescription == "") && ($this->metaKeywords == ""))
				{
					// Get the homepage for its default metadata
					$criteria = new CDbCriteria;
					$criteria->addCondition("home = " . 1);
					$contentBlock = ContentBlock::model()->find($criteria);
					if ($contentBlock)
					{
						$this->metaTitle = trim($contentBlock->meta_title);
						$this->metaDescription = trim($contentBlock->meta_description);
						$this->metaKeywords = trim($contentBlock->meta_keywords);
					}
				}
				$this->beginHeader = str_replace("<substitute-meta-title>", "<title>" . $this->metaTitle . "</title>", $this->beginHeader);;
				$this->beginHeader = str_replace("<substitute-meta-description>", "<meta name='description' content='" . $this->metaDescription . "'/>", $this->beginHeader);
				$this->beginHeader = str_replace("<substitute-meta-keywords>", "<meta name='keywords' content='" . $this->metaKeywords . "'/>", $this->beginHeader);

				// Any site specific headers?
				if (stristr($_SERVER['HTTP_HOST'], "dgbloodbikes.org.uk"))
					$this->beginHeader = str_replace("<substitute-meta-special>",'<meta name="google-site-verification" content="jEoBpHaqvy5MD6UJQZjM5uuVTs_YTfzwF_h0OefxRFs" />', $this->beginHeader);
				$this->beginHeader = str_replace("<substitute-meta-special>", "", $this->beginHeader);
			}	


			// Check if we have a contentBlock page anywhere in the file, and if so determine if its a homepage
			// If so, set @HOMEPAGE ($this->homePage) variable
			if ((is_array($value)) && (array_key_exists("db", $value)))
			{
				$arrDb = $value['db'];
				if (array_key_exists("filter", $arrDb))
				{
					if ((isset($_GET['page'])) && (trim($_GET['page']) != ""))
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
					{
						// No page asked - we will serve up the home page
						$this->homePage = 1;
					}
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

	private function isExcludedsite()
	{
		// Exclusion list - sites ignored for devicewidth
		if ( ((stristr($_SERVER['HTTP_HOST'], "1staid4u.co.uk")))
			|| ((stristr($_SERVER['HTTP_HOST'], "6tyshadesofbeauty.co.uk")))
//			|| ((stristr($_SERVER['HTTP_HOST'], "absoluteclassics.co.uk")))
			|| ((stristr($_SERVER['HTTP_HOST'], "beirc.co.uk")))
			|| ((stristr($_SERVER['HTTP_HOST'], "brycewalkervending.com")))
			|| ((stristr($_SERVER['HTTP_HOST'], "craigieknowesgolfandteeroom.co.uk")))
			|| ((stristr($_SERVER['HTTP_HOST'], "dgbloodbikes.org.uk")))
			|| ((stristr($_SERVER['HTTP_HOST'], "dglink.co.uk")))
			|| ((stristr($_SERVER['HTTP_HOST'], "dgnews-sport.co.uk")))
			|| ((stristr($_SERVER['HTTP_HOST'], "dumfriesfurniture.com")))
			|| ((stristr($_SERVER['HTTP_HOST'], "elegantoriginals.co.uk")))
			|| ((stristr($_SERVER['HTTP_HOST'], "fadguide.com")))
			|| ((stristr($_SERVER['HTTP_HOST'], "fresherthantheudders.co.uk")))
			|| ((stristr($_SERVER['HTTP_HOST'], "holidayletservice.co.uk")))
			|| ((stristr($_SERVER['HTTP_HOST'], "horses.wireflydesign.com")))
			|| ((stristr($_SERVER['HTTP_HOST'], "jacquiesbeauty.co.uk")))
			|| ((stristr($_SERVER['HTTP_HOST'], "jardineroofingltd.co.uk")))
//			|| ((stristr($_SERVER['HTTP_HOST'], "joseawright.com")))
			|| ((stristr($_SERVER['HTTP_HOST'], "knowledgebase.wireflydesign.com")))
			|| ((stristr($_SERVER['HTTP_HOST'], "mossheadpreschool.co.uk")))
			|| ((stristr($_SERVER['HTTP_HOST'], "opendoorsart.com")))
			|| ((stristr($_SERVER['HTTP_HOST'], "outlooksolutions.com.au")))
			|| ((stristr($_SERVER['HTTP_HOST'], "roselandcarehome.co.uk")))
			|| ((stristr($_SERVER['HTTP_HOST'], "rotarypeaceproject.com")))
			|| ((stristr($_SERVER['HTTP_HOST'], "senwickhouse.co.uk")))
			|| ((stristr($_SERVER['HTTP_HOST'], "southwest-arb.co.uk")))
			|| ((stristr($_SERVER['HTTP_HOST'], "styleyourvenue.co.uk")))
			|| ((stristr($_SERVER['HTTP_HOST'], "susiejamieson.com")))
			|| ((stristr($_SERVER['HTTP_HOST'], "the-art-room.co.uk")))
			|| ((stristr($_SERVER['HTTP_HOST'], "timtaylor-painter-decorator-tiler.co.uk")))
			|| ((stristr($_SERVER['HTTP_HOST'], "trade.weetarget.co.uk")))
			|| ((stristr($_SERVER['HTTP_HOST'], "weetarget.co.uk")))
			//|| ((stristr($_SERVER['HTTP_HOST'], "wireflydesign.com")))
			|| ((stristr($_SERVER['HTTP_HOST'], "zoelifecoaching.co.uk"))) )
				return(1);
			return(0);
	}

	// Check we have the device width. If not, echo the html/js to get it and caller quits

    private function gotDeviceWidth()
    {
		if ($this->isExcludedSite()) {
//echo $_SERVER['HTTP_HOST'] . ":" . "ISEXCL"; die;
			return(0);
		}

		$cookie_name = "deviceWidth";
		if (isset($_COOKIE[$cookie_name])) {
			$this->deviceWidth = $_COOKIE[$cookie_name];
			array_push($this->headerArray, '<meta name="viewport" content="width=device-width" />');
			return(0);
		}
    	$deviceWidthHtml = <<<END_OF_GETDEVICEWIDTH
			<html>
			<head>
			<script type="text/javascript">
			var w = Math.max(document.documentElement.clientWidth, window.innerWidth || 0)
			document.cookie = "deviceWidth=" + w;
			var url = window.location.href;
//alert('w='+w);
			window.location.href = url;
			</script>
			</head>
			<body>
			</body>
			</html>
END_OF_GETDEVICEWIDTH;
		echo $deviceWidthHtml;
		$this->isSettingDeviceWidth = 1;
		return (-1);
	}

	public function outputData()
	{
		if ($this->isSettingDeviceWidth == 1)	// ie we've already sent the minimal html to set cookie and then retry
			return;

		$this->emit($this->beginHeader);

/*	@@NB1 These 8 lines commented out and moved to the bottom of this function so header.css gets applied LAST
		if (file_exists($this->jellyRootPath . 'header.css'))
			$this->emit(file_get_contents($this->jellyRootPath . 'header.css'));
		else
		{
			// Backward compatibility - it used to be called header.html
			if (file_exists($this->jellyRootPath . 'header.html'))
				$this->emit(file_get_contents($this->jellyRootPath . 'header.html'));
		}
*/

		// snake oil here
		if (stristr($_SERVER['HTTP_HOST'], "wireflydesign.com")) {
			array_push($this->headerArray, '<script type="text/javascript" src="http://www.bae5tracker.com/js/61103.js" ></script>');
			array_push($this->headerArray, '<noscript><img src="http://www.bae5tracker.com/61103.png" style="display:none;" /></noscript>');
		}

		foreach ($this->headerArray as $hdr)
			$this->emit($hdr);

		$this->emit("\n<!-- CSS start -->\n<style type='text/css'>\n");
		foreach ($this->cssGlobalArray as $css)
			$this->emit($css);
		foreach ($this->cssDivArray as $css)
			$this->emit($css);
		$this->emit("</style>\n<!-- CSS end -->\n");
		$this->emit($this->endHeader);

		// JS to reset the devicewidth cookie
		$deviceChangeWidthHtml = <<<END_OF_CHANGEDEVICEWIDTH
            <script type="text/javascript">
				$(window).on('resize orientationChange', function(event) {
				// Android screws up so we check first
				cookieWidth = getCookie('deviceWidth');
				var deviceWidth = Math.max(document.documentElement.clientWidth, window.innerWidth || 0);
				if (deviceWidth == cookieWidth)
					return;
				// Changed
				setTimeout(function(){
					var w = Math.max(document.documentElement.clientWidth, window.innerWidth || 0)
					document.cookie = "deviceWidth=" + w;
					var url = window.location.href;
					//alert('refreshing width='+w);
					window.location.href = url;
				}, 50);
			});

function getCookie(name) {
  var value = "; " + document.cookie;
  var parts = value.split("; " + name + "=");
  if (parts.length == 2) return parts.pop().split(";").shift();
}

            </script>
END_OF_CHANGEDEVICEWIDTH;
		if (!($this->isExcludedSite()))
			$this->emit($deviceChangeWidthHtml);

		foreach ($this->bodyArray as $body)
			$this->emit($body);

		foreach ($this->scriptArray as $script)
			$this->emit($script);


/* @@NB1 These 8 lines were moved (see same commented out above, so header.css is applied LAST */
		if (file_exists($this->jellyRootPath . 'header.css'))
			$this->emit(file_get_contents($this->jellyRootPath . 'header.css'));
		else
		{
			// Backward compatibility - it used to be called header.html
			if (file_exists($this->jellyRootPath . 'header.html'))
				$this->emit(file_get_contents($this->jellyRootPath . 'header.html'));
		}


		$this->emit($this->stdFooter);
	}

	/**
	 * Process (usually display) a blob
	 */
	private function blobProcess($jellyArray, $blobName, $array, $float, $indentLevel = 0)
	{
		// Search array for repeating fields - we'll generate an instance of this blob for each
		$hasRepeatingField = false;
		// Skip over 'condition' blobs that fail their condition

		foreach ($array as $k=>$v) {
			if ("condition" == substr($k, 0, 9)) {
				if ((strstr($array[$k], "@HOMEPAGE")) && !(strstr($array[$k], "@PAGE"))) {
					// condition = @HOMEPAGE=0
					// condition = @HOMEPAGE=1
					// condition = @HOMEPAGE!=0
					// condition = @HOMEPAGE!=1
					if (strstr($array[$k], "!=1")) {
						if ($this->homePage == "1")
							return;
					}
					else if (strstr($array[$k], "!=0")) {
						if ($this->homePage == "0")
							return;
					}
					else if (strstr($array[$k], "=1")) {
						if ($this->homePage == "0")
							return;
					}
					else if (strstr($array[$k], "=0")) {
						if ($this->homePage == "1")
							return;	
					}
				}
				else if (strstr($array[$k], "@PAGE")) {
					// condition = @PAGE=@HOMEPAGE
					// condition = @PAGE!=@HOMEPAGE
					// condition = @PAGE=classical-music-series
					// condition = @PAGE!=classical-music-series
					if (strstr($array[$k], "=")) {
						$not = 0;
						if (strstr($array[$k], "!"))
							$not = 1;
						$first = strstr($array[$k], "=");
						$second = strstr($first, "=");
						$pageForConditionArr = explode(',', ltrim($second, "="));
						$pageForConditionArr = array_map('trim', $pageForConditionArr);
						$pageLoading = "";
						if ((isset($_GET['page'])) && (trim($_GET['page']) != "")) {
							$pageLoading = $_GET['page'];
							if ($this->homePage == 1)
								$pageLoading = "@HOMEPAGE";
						}
						else
							$pageLoading = "@HOMEPAGE";
	//die('blobname='.$blobName. ' not='.$not.' loading='.$pageLoading.' condition='.$pageForCondition);
						if ($not == 0) {
							if (!(in_array($pageLoading, $pageForConditionArr)))
								return;
						}
						else {
							if (in_array($pageLoading, $pageForConditionArr))
								return;
						}
					}
				}
				else if (strstr($array[$k], "@DEVICEWIDTH")) {	// =1000, >600, <958, >=320, <=800
        			$chrs = array('=', '>', '<');
					foreach ($chrs as $chr) {
						//echo $chr;	
						$exp = explode($chr, $array[$k]);
						if (count($exp) == 2) {
							if ($chr == "=" && $this->deviceWidth != trim($exp[1]))
								return;
							if ($chr == ">" && $this->deviceWidth <= trim($exp[1]))
								return;
							if ($chr == "<" && $this->deviceWidth >= trim($exp[1]))
								return;
						}
					}
				}
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
//Yii::log("REPEATING EVAL = " . $query , CLogger::LEVEL_WARNING, 'system.test.kim');
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

		$this->jamTemplateArr = array();	// clear this for each jelly block (is this the right place?


// @@TODO: remove this hardcoding
/*****************************************************************/
if ((isset($_GET['page'])) && (trim($_GET['page']) != ""))
{
 if ($_GET['page'] != 'Jacquies-Beauty-Dumfries-Salon')
 {
  if ((substr($blobName,0,4) == 'jtabs') || (substr($blobName,0,13) == 'tabscontainer'))
   return;
  }
}
/*****************************************************************/
		$this->logMsg($blobName . "\n", $indentLevel);

		// Is this entire blob clickable?
		if (array_key_exists("click", $array))
		{
			$click = "click=true\n";
			if (strpos($array['click'], "?") === false)
				$sep = "?";
			else
				$sep = "&";
			if (strstr($array['click'], "javascript:"))
			{
				$sep = "";
				$click = "";
			}
			$this->genInlineHtml("<a href=" . $this->dbExpand(trim($array['click'])) . $sep . $click . ">", $indentLevel);
//Yii::log(".................... jellyclick .................. genfromclick=" . $array['click'] , CLogger::LEVEL_WARNING, 'system.test.kim');
		}
		if (array_key_exists("clicknew", $array))
		{
			if (strpos($array['clicknew'], "?") === false)
				$sep = "?";
			else
				$sep = "&";
			$this->genInlineHtml("<a href=" . $this->dbExpand(trim($array['clicknew'])) . $sep . "click=true target='_blank'>\n", $indentLevel);
		}

// original 4 lines follow
//		if (array_key_exists("click", $array))
//			$this->genInlineHtml("<a href=" . $this->dbExpand(trim($array['click'])) . "&click=true>\n", $indentLevel);
//		if (array_key_exists("clicknew", $array))
//			$this->genInlineHtml("<a href=" . $this->dbExpand(trim($array['clicknew'])) . "&click=true target='_blank'>\n", $indentLevel);


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
		if (array_key_exists("clicknew", $array))
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
		$jamHeight = "0";
		switch ($word)
		{
			case "jamtemplate":
			foreach ($value as $jamTemplateName => $jamTemplateVal)
			{
				$this->jamTemplateArr[$jamTemplateName] = $jamTemplateVal;
//echo "XXXXXXXXXXXXXXXXXXXXXXX " . $this->jamTemplateArr[$jamTemplateName] . "   ";
			}
			case "jam":    // NB: DUPS ALLOWED
			foreach ($value as $jamType => $jamArg)
			{
				switch ($jamType)
				{
					case "iframe-height":
						$jamHeight = $jamArg;	
						break;
					case "embed":
					case "iframe":
						// Get uid. From where depends whether site or plugin
            			$sid = "";
						if(!isset($_SESSION))
							session_start();
						if (isset($_SESSION['admin_user_sid']))
							$sid = $_SESSION['admin_user_sid'];
						else if (isset(Yii::app()->params['sid']))
							$sid = Yii::app()->params['sid'];
						else if (isset($_GET['sid']))
							$sid = $_GET['sid'];
						if ($sid == "") {
							$this->genInlineHtml("jam component requires a sid");
							break;
						}
						$settingEmail = "Email is not set in backend settings";
						$criteria = new CDbCriteria;
						$criteria->addCondition("id = 1");
						$setting = JellySetting::model()->find($criteria);
						if ($setting)
							$settingEmail = $setting->email;
						else
							die($settingEmail);
						// Create url and call curl
						$yiiSite = str_replace("/index.php", "", Yii::app()->createAbsoluteUrl(Yii::app()->request->getPathInfo()));
						//$jamUrl = $yiiSite . "/jamcgi/jam?jamtemplate=" . $jamArg . "&jelly.sid=" . $sid . "&jelly.email=" . $settingEmail;
						$argChar = "?";
						if (strstr($jamArg, "?") == true)
							$argChar = "&";
						$jamUrl = $yiiSite . $jamArg . $argChar . "jelly.sid=" . $sid . "&jelly.email=" . $settingEmail;
						//$jamUrl = $yiiSite . $jamArg . $argChar . "jelly.email=" . $settingEmail .  "jelly.sid=" . $sid;
						// Add in any possible templates
						foreach ($this->jamTemplateArr as $n => $v)
						{
							$jamUrl .= "&jamtemplate." . $n . "=" . $v;
						}
						if ($jamType == "embed") {
							$shell_exec = "php " . Yii::app()->basePath . "/../jam/jelly2jam.php" . " '" . $jamUrl . "'";
//echo "YYYYYYYYYYYYYYYYYYYYYYY " . $shell_exec . "<br>";
							$curlContent = shell_exec ($shell_exec);
							$this->genInlineHtml($curlContent);
						} else {
							// Check if iframe has a size
							$idNo = mt_rand();
							$iframe = "<iframe id='frm-" . $idNo . "' onload='scroll(0,0);' width='100%' height='" .$jamHeight . "' scrolling='no' style='overflow-x:hidden; overflow-y:auto;' src='"  .$jamUrl . "' ></iframe>";
							$this->genInlineHtml($iframe);
						}
						break;
				}

/*
while (list($var,$value) = each ($_SERVER))
	echo "$var => $value <br />";
echo '<pre>';
echo "<hr>SESSION<br>";
session_start();
var_dump($_SESSION);
echo "<hr>GET<br>";
var_dump($_GET);
echo '</pre>';
*/


			}
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

// @@ TODO Fix this hardocoding for removing the big google map from the event page . Needs to be a conditional.
if (strstr($blobName, "googlemap"))
{
	if (isset($_GET['programid']))
	{
		if ($_GET['programid'] == 12)	// Absolute Classics
			$cssValue = "0px";
	}
	if ((Yii::app()->session['map']) && (Yii::app()->session['map'] == "no"))
	{
			$cssValue = "0px";			// map=no selected
	}
}

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
				$zindex = "";
				$tip = "";
				$align = "";
				$fx = "";
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
						case ("z-index"):
							$zindex = ' style="position:relative; z-index:' . $val . '";';
							break;
						case ("align"):
							if (($val == 'center') || ($val == 'centre'))
								$align = " style='display:block;margin-left:auto;margin-right:auto' ";
							if ($val == 'left')
								$align = " style='display:block;margin-right:auto' ";
							if ($val == 'right')
								$align = " style='display:block;margin-left:auto' ";
							break;
						case "fx":
							$fx = " class='" . $val . "' ";
							break;
						default:
					}
				}
				if ($alt == "")
					$this->genInlineHtml('<div class="' . $val . '-container" style="border:0;padding:0;margin:0"><img ' . $fx . $zindex . $align . ' title="' . $tip . '" src="' . $this->dbExpand($url) . '" width="' . $width . '" height="' . $height . '"></div>');
				else
					$this->genInlineHtml('<img ' . $zindex . $align . ' title="' . $tip . '" src="' . $this->dbExpand($url) . '" onerror="this.onerror=null;this.src=\'' . $this->dbExpand($alt) . '\'" width="' . $width . '" height="' . $height . '">');
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
						case "wallpaper-image-tile":
							$this->genGlobalCSS("html { background: url(" . Yii::app()->baseUrl  . $cssValue . ") repeat center center fixed;
										/*-webkit-background-size: cover;
										-moz-background-size: cover;
										-o-background-size: cover;
										background-size: cover;			*/
										\n}\n"); 
							break;
						case "shadow":
							if ((!$cssValue) || ($cssValue == "default"))
								$cssValue = "0 0 20px black";
							$this->genDivCSS("-moz-box-shadow: " . $cssValue . "; -webkit-box-shadow: " . $cssValue . "; box-shadow: " . $cssValue . ";");
							break;
						case "hover-color":
//@@TODO: impliment this
							//$this->genDivCSS("hover { background-color:#FF0000;}\n");
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
				{
					$condition = $this->dbExpand(trim($flt));
					$condition = str_replace('""', '"', $condition);
					$condition = str_replace("''", "'", $condition);
					$cri->addCondition($condition);
				}
                // Add order
                foreach ($orderArr as $ord)
				$cri->order = $this->dbExpand(trim($ord));
                // Do the query
                $q = "return " . $query . ";";
//Yii::log("EVAL = " . $query , CLogger::LEVEL_WARNING, 'system.test.kim');
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
	// arg 2 says whether output is generated inline or returned via arg 3

	private function addonHandler($value, $htmlRequired = 0, &$htmlOutput = null)
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
				if ($htmlRequired == 0)
					$this->genInlineHtml($code[0]);		// Local html generated by the addon
				else
				{
					// We are going to return the code generated by the addon to the caller, not emit it
					// Get the HTML
					$htmlOutput = $code[0];
					// Get the JS, replacing the Onready() with inline code
					$htmlOutput .= "<script>";
					$htmlOutput .= $code[1];
					//$htmlOutput .= "init_addon_js();";
					$htmlOutput .= "</script>";
				}
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

	private function checkLocalSubstitutions(&$content)
	{
		if (isset($_GET['sid']))
		{
			$userId = -1;
			$criteria = new CDbCriteria;

			// Strip any possible quotes (?!)
			$sid = $_GET['sid'];
			$sid = str_replace("'", "", $_GET['sid']);
			$sid = str_replace('"', "", $_GET['sid']);

			$criteria->addCondition("sid = '" . $sid . "'");
			$user = User::model()->find($criteria);
			if ($user)
			{
				$userId = $user->id;
			}
			$content = str_replace("<substitute-user>", $userId, $content);
		}
	}

	private function genDivCSS($content)
	{
		// Translate any angle-brackets in the jelly file
		$this->checkLocalSubstitutions($content);

		array_push($this->cssDivArray, $content);
	}

	private function genGlobalCSS($content)
	{
		// Translate any angle-brackets in the jelly file
		$this->checkLocalSubstitutions($content);

		array_push($this->cssGlobalArray, $content);
	}

	private function genInlineHtml($content, $indentLevel=0)
	{
		// @@TODO FIX BUG - product plugin on going to product page after having backspaced from product page - doubles up quoting the ""sid""
		if (strstr($content, "&sid=\"\""))
		{
			//$content = str_replace("&sid=\"\"", "&sid=\"", $content);
			//$content = str_replace("\"\"&", "\"&", $content);
			//$content = str_replace("index.php", "http://plugin.wireflydesign.com/product/index.php", $content);
		}

		// Translate any @CLIPBOARD's
		if (strstr($content, "@CLIPBOARD"))
			$content = str_replace("@CLIPBOARD", $this->clipBoard, $content);

		// Translate any angle-brackets in the jelly file
		$this->checkLocalSubstitutions($content);


        // Trap any passed sids and flesh them out (this is actually a blog bug, passing sid from index.jel -> article.jel fucks up. Any yet the same for the product plugin index.jel -> product.jel works just fine (?!)
        if (isset($_GET['sid']))
        {
            $sid = $_GET['sid'];
            if (strstr($content, '$_GET[\'sid\']'))
                $content = str_replace('$_GET[\'sid\']', $sid, $content);
        }


		// Translate any curly wurlys
		$moreCurlyWurlys = 1;
		while ($moreCurlyWurlys)
		{
			$moreCurlyWurlys = 0;
			$addonHtml = "";
			$p1 = strstr($content, "{{");
			$p2 = strstr(substr($p1, 2), "}}", true);
			$pOrig = "{{" . $p2 . "}}";
			$vals = explode(" ", $p2);
			$type = $vals[0];

	 		if (stristr($vals[0], "download"))
			{
				// Eg: {{download}}  (hybrid)
				// -------------------------
				$moreCurlyWurlys = 1;
				$optionMode = "all";
				$optionValue = "";
				if (count($vals) > 2)
				{
						$tmp = explode(' ', $p2, 3);	// Split on first space
						$optionMode = $tmp[1];			// 'file' or 'collection'
						$optionValue = $tmp[2];			// 'abc.pdf' or 'my file collection'
				}
				$addon = array(
					"download" => array(
						"jellybasic" => array(
             				"mode" => $optionMode,
							"value" => $optionValue,
						)
					)
				);
				$this->addonHandler($addon, 1, $addonHtml);
				$content = str_replace($pOrig, $addonHtml, $content);
				//$content = str_replace($pOrig, "", $content);
			}

			if (stristr($vals[0], "gallery-hoverzoom"))
			{
				// Eg: {{gallery-hoverzoom <33>}}  (hybrid)
				// ----------------------------------------
				//@@ TODO: TOFIX: BUG: Uncommenting next line causes memory exhaustion
				//$moreCurlyWurlys = 1;
				$galleries = "";
				$thumbnails = "0";
				if (count($vals) > 1)
					$galleries = $vals[1];
				$addon = array(
					"gallery" => array(
						"hoverzoom" => array(
             				"gallery" => $galleries,
						)
					)
				);
				$this->addonHandler($addon, 1, $addonHtml);
				$content = str_replace($pOrig, $addonHtml, $content);
				//$content = str_replace($pOrig, "", $content);
			}

			if (stristr($vals[0], "gallery-lightbox"))
			{
				// Eg: {{gallery-lightbox <33>}}  (hybrid) - only used in embedded news articles
				// ---------------------------------------
				//@@ TODO: TOFIX: BUG: Uncommenting next line causes memory exhaustion
				//$moreCurlyWurlys = 1;
				$galleries = "";
				$thumbnails = "0";
				if (count($vals) > 1)
					$galleries = $vals[1];
				$addon = array(
					"gallery" => array(
						"lightbox" => array(
             				"gallery" => $galleries,
						)
					)
				);
				$this->addonHandler($addon, 1, $addonHtml);
				$content = str_replace($pOrig, $addonHtml, $content);
				//$content = str_replace($pOrig, "", $content);
			}

			if (stristr($vals[0], "gallery"))		// This is the default gallery, ie everywhere except in embedded news articles
			{
				// Eg: {{gallery <33 SomeTitle> <"thumbs">}}  (hybrid)
				// ---------------------------------------------------
				//@@ TODO: TOFIX: BUG: Uncommenting next line causes memory exhaustion
				//$moreCurlyWurlys = 1;
				$galleries = "";
				$thumbnails = "0";
				if (count($vals) > 1)
					$galleries = $vals[1];
				if (count($vals) > 2)
				{
					if (trim($vals[2]) == "thumbnails")
					$thumbnails = "1";
				}
				if (count($vals) > 3)
				{
					if (trim($vals[3]) == "thumbnails")
					$thumbnails = "1";
				}
				$addon = array(
					"gallery" => array(
						"fancybox" => array(
             				"gallery" => $galleries,
             				"source" => "db",
             				"thumbnails" => $thumbnails,
						)
					)
				);
				$this->addonHandler($addon, 1, $addonHtml);
				$content = str_replace($pOrig, $addonHtml, $content);
				//$content = str_replace($pOrig, "", $content);
			}

			// Fadguide specific. See addon custom fadguidecode and the member page template for details
			if (stristr($vals[0], "fadguide-member"))
			{
				// Eg: {{cat-member}}  (hybrid)
				// ---------------------------------
				$moreCurlyWurlys = 1;
				$addon = array(
					"custom" => array(
						"fadguidecode" => array(
							"run"      => "showMember",
						)
					)
				);
				$this->addonHandler($addon, 1, $addonHtml);
				$content = str_replace($pOrig, $addonHtml, $content);
				//$content = str_replace($pOrig, "", $content);
			}

			// Fadguide specific
			if (stristr($vals[0], "fadguide-category"))
			{
				// Eg: {{category 1 Eating Out}}  (hybrid)
				// ---------------------------------------
				$moreCurlyWurlys = 1;
				$addon = array(
					"custom" => array(
						"fadguidecode" => array(
             				"category" => $vals[1],
							"run"      => "listMembers",
						)
					)
				);
				$this->addonHandler($addon, 1, $addonHtml);
				$content = str_replace($pOrig, $addonHtml, $content);
				//$content = str_replace($pOrig, "", $content);
			}

			if (stristr($vals[0], "ticket"))
			{
				// Eg: {{ticket 110 Jos Test event}}
				// ---------------------------------
				$moreCurlyWurlys = 1;
				$value = $vals[1];
				$iframe = '<iframe onload="scroll(0,0);" width="100%" height="900" scrolling="no" style="overflow-x:hidden; overflow-y:auto;" src="https://plugin.wireflydesign.com/ticket/index.php/ticket/book/' . $value . '?sid=' . Yii::app()->params['sid'] . '&amp;ref=none"></iframe>';
				$content = str_replace($pOrig, $iframe, $content);
			}

			if (stristr($vals[0], "department"))
			{
				// Eg: {{department 27 Guinot}}
				// ----------------------------
				$moreCurlyWurlys = 1;
				$value = "";
				if (count($vals) > 1)
					$value = $vals[1];

                $deeplink = "";
				if ((isset($_GET['page'])) && (trim($_GET['page']) != ""))
                    $deeplink .= "&page=" . $_GET['page'];
                if ((isset($_GET['product'])) && (trim($_GET['product']) != ""))
                    $deeplink .= "&product=" . $_GET['product'];

				$iframe = '<iframe onload="scroll(0,0);" width="100%" height="900" scrolling="no" style="overflow-x:hidden; overflow-y:auto;" src="http://plugin.wireflydesign.com/product/?sid=' . Yii::app()->params['sid'] . '&amp;department=' . $value . $deeplink . '"></iframe>';
				$content = str_replace($pOrig, $iframe, $content);
			}

			if (stristr($vals[0], "shop"))
			{
				// Eg: {{shop}}
				// ------------
				$moreCurlyWurlys = 1;
				$value = "";
				if (count($vals) > 1)
					$value = $vals[1];

				$deeplink = "";
				if ((isset($_GET['page'])) && (trim($_GET['page']) != ""))
					$deeplink .= "&page=" . $_GET['page'];
				if ((isset($_GET['product'])) && (trim($_GET['product']) != ""))
				{
					if ((isset($_GET['product'])) && (trim($_GET['product']) != ""))
						$deeplink .= "&product=" . $_GET['product'];
					if ((isset($_GET['department'])) && (trim($_GET['department']) != ""))
						$deeplink .= "&department=" . $_GET['department'];
					$iframe = '<iframe onload="scroll(0,0);" width="100%" height="900" scrolling="no" style="overflow-x:hidden; overflow-y:auto;" src="http://plugin.wireflydesign.com/product/?sid=' . Yii::app()->params['sid'] . $deeplink . '"></iframe>';
				}
				else if ((isset($_GET['department'])) && (trim($_GET['department']) != ""))
				{
					if ((isset($_GET['department'])) && (trim($_GET['department']) != ""))
						$deeplink .= "&department=" . $_GET['department'];
					$iframe = '<iframe onload="scroll(0,0);" width="100%" height="900" scrolling="no" style="overflow-x:hidden; overflow-y:auto;" src="http://plugin.wireflydesign.com/product/?sid=' . Yii::app()->params['sid'] . $deeplink . '"></iframe>';
				}
				else
				{
					$iframe = '<iframe onload="scroll(0,0);" width="100%" height="900" scrolling="no" style="overflow-x:hidden; overflow-y:auto;" src="http://plugin.wireflydesign.com/product/?sid=' . Yii::app()->params['sid'] . '&amp;shop=' . 'shop' . $deeplink . '"></iframe>';
				}

				//$iframe = '<iframe onload="scroll(0,0);" width="100%" height="900" scrolling="no" style="overflow-x:hidden; overflow-y:auto;" src="http://plugin.wireflydesign.com/product/?sid=' . Yii::app()->params['sid'] . '&amp;shop=' . 'shop' . '"></iframe>';

				$content = str_replace($pOrig, $iframe, $content);
			}

			if (stristr($vals[0], "preset"))
			{
				// Eg: {{preset}}
				// --------------
				$moreCurlyWurlys = 1;
				$iframe = '<iframe onload="scroll(0,0);" width="100%" height="900" scrolling="no" style="overflow-x:hidden; overflow-y:auto;" src="https://plugin.wireflydesign.com/product/?layout=preset&sid=' . Yii::app()->params['sid'] . '&amp;preset=true"></iframe>';
				$content = str_replace($pOrig, $iframe, $content);
			}

			if (stristr($vals[0], "checkout"))
			{
				// Eg: {{checkout}}
				// --------------
				$moreCurlyWurlys = 1;
				$util = new Util;;
				// Check required parameters are set up for gateway access
				if ((trim(Yii::app()->params['checkoutEmail']) == "")
				||  (trim(Yii::app()->params['checkoutName']) == "")
				||  ((trim(Yii::app()->params['checkoutGatewayUser']) == "") && (trim(Yii::app()->params['checkoutPaypalUser']) == ""))
				)
					die("Checkout needs gateway access to be set up in the configuration file");
				$click = '';
                if ((isset($_GET['click'])) && (trim($_GET['click']) != ""))
                    $click .= "&click=" . $_GET['click'];
				if (!(isset(Yii::app()->params['checkoutPaypalEmail'])))
					Yii::app()->params['checkoutPaypalEmail'] = '';

				$iframe = '<iframe onload="scroll(0,0);" width="100%" height="900" scrolling="no" style="overflow-x:hidden; overflow-y:auto;" src="https://plugin.wireflydesign.com/product/?layout=checkout&sid=' . Yii::app()->params['sid'] . '&ge=' . Yii::app()->params['checkoutEmail'] . '&gn=' . Yii::app()->params['checkoutName'] . '&gu=' . urlencode($util->encrypt(Yii::app()->params['checkoutGatewayUser'])) . '&gp=' . urlencode($util->encrypt(Yii::app()->params['checkoutGatewayPassword'])) . $click . '&pp=' . urlencode($util->encrypt(Yii::app()->params['checkoutPaypalEmail'])) . '&checkoutButton=true' . '"></iframe>';
				$content = str_replace($pOrig, $iframe, $content);
			}

			if ((stristr($vals[0], "news")) || (stristr($vals[0], "blog")))
			{
				// Eg: {{news traditional}} {{news pinterest}}
				// -----------------
				$moreCurlyWurlys = 1;

				$newsType = 'traditional';
				$sidebar = 'left';
				$pushRecentDown = '';
				$pushCategoriesDown = '';
				if (stristr($vals[1], "="))	// nvp-style args
				{
					foreach ($vals as $nvps)
					{
						if (!stristr($nvps, "=")) continue;	// first arg obviously wont have
						$nvp = explode("=", $nvps);
//echo $nvp[0] . "-" . $nvp[1] . "<br>";
						if (trim($nvp[0]) == "newstype") $newsType = trim($nvp[1]);
						if (trim($nvp[0]) == "sidebar") $sidebar = trim($nvp[1]);
						if (trim($nvp[0]) == "pushrecentdown") $pushRecentDown = trim($nvp[1]);
						if (trim($nvp[0]) == "pushcategoriesdown") $pushCategoriesDown = trim($nvp[1]);
					}
				}

                $deeplink = "";
				if ((isset($_GET['page'])) && (trim($_GET['page']) != ""))
                    $deeplink .= "&page=" . $_GET['page'];
                if (isset($_GET['cat']))
                    $deeplink .= "&cat=" . $_GET['cat'];
                if (isset($_GET['art']))
                    $deeplink .= "&art=" . $_GET['art'];

// @@NB This plugin gets passed the parent url - galleries may be embedded in its pages
// ------------------------------------------------------------------------------------
				$page = "";
				if (isset(Yii::app()->session['page']))
					$page = Yii::app()->session['page'];
                $iframe = '<iframe onload="scroll(0,0);" width="100%" height="900" scrolling="no" style="overflow-x:hidden; overflow-y:auto;" src="http://plugin.wireflydesign.com/news/?sid=' . Yii::app()->params['sid'] . '&parenturl=' . Yii::app()->getBaseUrl(true)  . '&page=' . $page . '&sidebar=' . $sidebar . '&newstype=' . $newsType . '&category=0' . '&pushrecentdown=' . $pushRecentDown . '&pushcategoriesdown=' . $pushCategoriesDown . $deeplink . '"></iframe>';
				$content = str_replace($pOrig, $iframe, $content);
			}

			if (stristr($vals[0], "Xblog750"))
			{
				// Eg: {{blog750}}
				// ------------
				$moreCurlyWurlys = 1;
				$iframe = '<iframe onload="scroll(0,0);" width="100%" height="900" scrolling="no" style="overflow-x:hidden; overflow-y:auto;" src="https://plugin.wireflydesign.com/blog/?layout=index750&sid=' . Yii::app()->params['sid'] . '&blogwidth=750&category=0"></iframe>';
				$content = str_replace($pOrig, $iframe, $content);
			}

			if (stristr($vals[0], "Xblog"))
			{
				// Eg: {{blog}}
				// ------------
				$moreCurlyWurlys = 1;
				$iframe = '<iframe onload="scroll(0,0);" width="100%" height="900" scrolling="no" style="overflow-x:hidden; overflow-y:auto;" src="https://plugin.wireflydesign.com/blog/?sid=' . Yii::app()->params['sid'] . '&blogwidth=auto&category=0"></iframe>';
				$content = str_replace($pOrig, $iframe, $content);
			}

		}
		// push out the resulting html
		array_push($this->bodyArray, $content);
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
        echo "[Error $errno on line $errline] $errstr<br />\n";
        break;
    }

    /* Don't execute PHP internal error handler */
    return true;
}
?>
