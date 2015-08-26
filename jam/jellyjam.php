<?php
	$jamUrl = "http://www.outlooksolutions.com.au/jamcgi/jam?template=/jam/template/contactForm.tpl&jelly_setting.email=kim@wireflydesign.com";
	$options = array(
		CURLOPT_RETURNTRANSFER => true,   // return web page
		CURLOPT_HEADER         => false,  // don't return headers
		CURLOPT_FOLLOWLOCATION => true,   // follow redirects
		CURLOPT_MAXREDIRS      => 10,     // stop after 10 redirects
		CURLOPT_ENCODING       => "",     // handle compressed
		CURLOPT_USERAGENT      => "test", // name of client
		CURLOPT_AUTOREFERER    => true,   // set referrer on redirect
		CURLOPT_CONNECTTIMEOUT => 120,    // time-out on connect
		CURLOPT_TIMEOUT        => 120,    // time-out on response
	);
	$curl = curl_init($jamUrl);
	curl_setopt_array($curl, $options);
	$content = curl_exec ($curl);
	echo $content;
file_put_contents("/tmp/jellyjam", $content);
	curl_close ($curl);
?>

