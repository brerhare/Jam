<?php

/**
*
*
*
*/

class crawler
{
	//Defaults
	private $defaultWidth = "100%";
	private $defaultFontSize = "14px";
	
	Public function init ($options, $jellyRootUrl)
	{
		//var_dump( $options );
		
		// Generate the content into the HTML, replacing any <substituteN> tags
		
		$content = "";
		foreach ($options as $opt => $val)
		{
			switch ($opt)
			{
			
				case "width":
					$this->defaultWidth = $val;
					break;
				case "font-size":
					$this->defaultFontSize = $val;
					break;
			}
		}
	
	
//Apply all values to the HTML and/or JS
	//HTML
	
	if (strstr($this->apiHTML, "<substitute-width>"))
		$this->apiHTML = str_replace("<substitute-width>", "width:" . $this->defaultWidth . ";", $this->apiHTML);
	if (strstr($this->apiHTML, "<substitute-font-size>"))
		$this->apiHTML = str_replace("<substitute-font-size>", "font-size:" . $this->defaultFontSize . ";", $this->apiHTML);
		
	if (strstr($this->apiHTML, "<substitute-path>"))
		$this->apiHTML = str_replace("<substitute-path>", $jellyRootUrl , $this->apiHTML);
		
	
	$retArr = array();
	$retArr[0] = $this->apiHTML;
	$retArr[1] = $this->apiJs;
	return $retArr;

	}

private $apiHTML = <<<END_OF_API_HTML
<div id="jelly-crawler-container">
            <!--Crawler Ticker-->
            <link rel="stylesheet" href="<substitute-path>/crawler.css" type="text/css">
            <script src="<substitute-path>/crawler.js"></script> 
            <script src="<substitute-path>/jquery.carouFredSel.js"></script> 

<style>
body * {
	<substitute-font-size>
}
</style>

<br>HELLO<br>
            <div class="crawlerticker">
				<!-- <substitute-data> -->


<div id="wrapper">
    <h3>News Tickers</h3>
    <div class="first">
        <dl id="ticker-1">
            <dt>News ticker</dt>
                <dd>A news ticker (sometimes referred to as a &quot;crawler&quot;) resides in the lower third of the television screen space on television news networks dedicated to presenting headlines or minor pieces of news.</dd>

            <dt>Scoreboard style</dt>
                <dd>It may also refer to a long, thin scoreboard-style display seen around the front of some offices or public buildings.</dd>

            <dt>WWW</dt>
                <dd>Since the growth in usage of the World Wide Web, news tickers have largely syndicated news posts from the websites of the broadcasting services which produce the broadcasts.</dd>
        </dl>
    </div>
    <div>
        <dl id="ticker-2">
            <dt>Back and forth</dt>
                <dd>By simply setting the option <code>circular</code> to <code>false</code>, this news ticker will go back and forth.</dd>

            <dt>PauseOnHover</dt>
                <dd>Because the option <code>pauseOnHover</code> is set to <code>&quot;immediate&quot;</code>, these news tickers will immediately stop scrolling if you move your mouse over them.</dd>

            <dt>Any kind of HTML</dt>
                <dd>These news tickers may contain any kind of HTML, such as <a href="#">hyperlinks</a>, <em>formatted</em> <strong>text</strong> or even images.</dd>
        </dl>
    </div>
</div>


            </div>
</div>

END_OF_API_HTML;

private $apiJs = <<<END_OF_API_JS

	// Any custom js and/or startup functions

END_OF_API_JS;

}
?>
