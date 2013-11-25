<?php

/**
 * API for Google OS map
 *
 * Notes
 * -----
 * None
 */

class google_os
{
	//Defaults
	private $defaultId = "myMap";
	private $defaultWidth = "620px";
	private $defaultHeight = "200px";
	private $defaultZoom = "8";
	private $defaultMapType = "roadmap";	// "roadmap", "terrain", "satellite", "hybrid"
	private $defaultInputMode = "os";	// "os", "latlong", "postcode"

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
				case "inputmode":
					$tmp = str_replace("<substitute-inputmode>", strtoupper($val), $this->apiJs);
					$this->apiJs = $tmp;
					$inputMode = $val;
					break;
				case "maptype":
					$tmp = str_replace("<substitute-maptype>", strtoupper($val), $this->apiJs);
					$this->apiJs = $tmp;
					break;
				case "zoom":
					$tmp = str_replace("<substitute-zoom>", $val, $this->apiJs);
					$this->apiJs = $tmp;
					break;
				case "id":
					$tmp = str_replace("<substitute-id>", $val, $this->apiHtml);
					$this->apiHtml = $tmp;
					$tmp = str_replace("<substitute-id>", $val, $this->apiJs);
					$this->apiJs = $tmp;
					break;
				case "width":
					$tmp = str_replace("<substitute-width>", $val, $this->apiHtml);
					$this->apiHtml = $tmp;
					break;
				case "height":
					$tmp = str_replace("<substitute-height>", $val, $this->apiHtml);
					$this->apiHtml = $tmp;
					break;
				case "center": // @@TODO this works for 'os' only
					$onReady .= '$(document).ready(function (){';
					if ($inputMode == "os")
						$onReady .= " centerByOs('" . $val . "');";
					else if ($inputMode == "latlong")
					{
						$ll = explode(',', $val);
						$onReady .= " centerByLatLong('" . $ll[0] . "','" . $ll[1] . "');";
					}
					$onReady .= '});';
					break;
				case "mark": // @TODO this works for 'os' only
					$onReady .= '$(document).ready(function (){';
					if ($inputMode == "os")
						$onReady .= " markerByOs('" . $val . "');";
					else if ($inputMode == "latlong")
					{
						$ll = explode(',', $val);
						$onReady .= " markerByLatLong('" . $ll[0] . "','" . $ll[1] . "');";
					}
					$onReady .= '});';
					break;
				default:
					// Not all array items are action items
			}
		}

		// Apply all defaults that werent overridden

		// HTML
		if (strstr($this->apiHtml, "<substitute-width>"))
		{
			$tmp = str_replace("<substitute-width>", $this->defaultWidth, $this->apiHtml);
			$this->apiHtml = $tmp;
		}

		if (strstr($this->apiHtml, "<substitute-id>"))
		{
			$tmp = str_replace("<substitute-id>", $this->defaultId, $this->apiHtml);
			$this->apiHtml = $tmp;
		}
		if (strstr($this->apiJs, "<substitute-id>"))
		{
			$tmp = str_replace("<substitute-id>", $this->defaultId, $this->apiJs);
			$this->apiJs = $tmp;
		}

		if (strstr($this->apiHtml, "<substitute-height>"))
		{
			$tmp = str_replace("<substitute-height>", $this->defaultHeight, $this->apiHtml);
			$this->apiHtml = $tmp;
		}

		// JS
		if (strstr($this->apiJs, "<substitute-inputmode>"))
		{
			$tmp = str_replace("<substitute-inputmode>", strtoupper($this->defaultInputMode), $this->apiJs);
			$this->apiJs = $tmp;
		}
		if (strstr($this->apiJs, "<substitute-maptype>"))
		{
			$tmp = str_replace("<substitute-maptype>", strtoupper($this->defaultMapType), $this->apiJs);
			$this->apiJs = $tmp;
		}
		if (strstr($this->apiJs, "<substitute-zoom>"))
		{
			$tmp = str_replace("<substitute-zoom>", $this->defaultZoom, $this->apiJs);
			$this->apiJs = $tmp;
		}

		if ($onReady != "")
		{
			$this->apiJs .= $onReady;
			Yii::log("GOOGLE MAPS: OnReady= [" . $onReady . "]", CLogger::LEVEL_WARNING, 'system.test.kim');
		}

		// Substitute paths for includes
		$tmp = str_replace("<substitute-path>", $jellyRootUrl, $this->apiHtml);
		$this->apiHtml = $tmp;

		$html = $this->apiHtml;
		$js   = $this->apiJs;

		$retArr = array();
		$retArr[0] = $html;
		$retArr[1] = $js;

		return $retArr;
	}

//---------------------------------------------------------------------------------------------------------

	private $apiHtml = <<<END_OF_API_HTML

	<!-- <div id="jelly-google-os-container"> -->
	<div id="<substitute-Xid>">

		<script type="text/javascript" src="//maps.google.com/maps/api/js?sensor=false"></script>
		<script type="text/javascript" src="<substitute-path>/latlong-gridref.js"></script>
<!--
		<label for="osgridref">OS Grid Reference:</label>
		<input id="osgridref" type="text" style="width:200px;" />
		<input id="lookup" type="button" value="Lookup" />
-->
		<div id="<substitute-id>-map" style="width:<substitute-width>;height:<substitute-height>" border="1px solid black"></div>
	</div>

END_OF_API_HTML;

	private $apiJs = <<<END_OF_API_JS

		var map_<substitute-id> = null;
		var inputMode = "<substitute-inputmode>";
		//var infowindow = new google.maps.InfoWindow();

		$(document).ready(function ()
		{

		});

		centerByOs = function(osgridref)
		{
			var latlong = OSGridToLatLong(osgridref);
			var center = new google.maps.LatLng(latlong.lat, latlong.lng);
    		map_<substitute-id>.panTo(center);
		}

		markerByOs = function(osgridref, iconpath, hovertip, content)
		{
			if (osgridref.length > 0)
			{
				if (osgridref.indexOf(',') != -1)
				{
					var eastnorth = osgridref.split(',');
					osgridref = gridrefNumToLet(eastnorth[0], eastnorth[1], 10);
				}				
				var latlong = OSGridToLatLong(osgridref);
				loadMap(latlong.lat, latlong.lng);
				setupMarker(latlong.lat, latlong.lng, iconpath, hovertip, content);
			}
		}

		markerByOs2 = function(osgridref, postcode)
		{
			if (osgridref.length > 0)
			{
				if (osgridref.indexOf(',') != -1)
				{
					var eastnorth = osgridref.split(',');
					osgridref = gridrefNumToLet(eastnorth[0], eastnorth[1], 10);
				}				
				var latlong = OSGridToLatLong(osgridref);
				loadMap(latlong.lat, latlong.lng);
				setupMarker2(latlong.lat, latlong.lng, postcode);
			}
		}

		centerByLatLong = function(lat, long)
		{
			var center = new google.maps.LatLng(lat, long);
    		map_<substitute-id>.panTo(center);
		}

		markerByLatLong = function(lat, long)
		{
			setupMarker(parseFloat(lat), parseFloat(long));
		}

		// Loads the map
	    loadMap = function (latitude, longitude)
		{
			//alert('load');
			if (map_<substitute-id>)	// Already loaded
				return;
			var latlng = new google.maps.LatLng(latitude, longitude);
			var myOptions = {
				zoom: <substitute-zoom>,
				mapTypeId: google.maps.MapTypeId.<substitute-maptype>,
				center: latlng,
			};
			map_<substitute-id> = new google.maps.Map(document.getElementById("<substitute-id>-map"), myOptions);
		}

		// Sets up a marker and info window on the map at the latitude and longitude specified
		setupMarker = function(latitude, longitude, iconpath, hovertip, content)
		{
			var usehovertip = "";
			var usecontent = "";
			if (hovertip != undefined)
				usehovertip = urldecode(hovertip);
			if (content != undefined)
				usecontent = urldecode(content);

			// Generate the position from the given latitude and longitude values
			var pos = new google.maps.LatLng(latitude, longitude);

			// Define the marker on the map in the specified location
			var myIcon = '';
			var url = '';
			if (iconpath != undefined)
				myIcon = new google.maps.MarkerImage(iconpath, undefined, undefined, undefined, new google.maps.Size(20, 20));
			var marker = new google.maps.Marker({
				position: pos,
				map: map_<substitute-id>,
				icon: myIcon,
				title: usehovertip
			});

			// Add a listener to this marker to display the information window on click
			google.maps.event.addListener(marker, 'click', function () {
				var infowindow = new google.maps.InfoWindow({
					content: usecontent,
				});
				infowindow.open(map_<substitute-id>, marker);
			});

			/* This is an attempt to have a single open infowindow. Needs 'var infowindow = new google.maps.InfoWindow()' defined as global (not in the function) */
			/******************
			function attachListener(marker,content){
			      google.maps.event.addListener(marker, "click", function () {

			      // marker.getPosition() already returns a google.maps.LatLng() object
			      map_<substitute-id>.setCenter(marker.getPosition());
			      infowindow.close();
			      infowindow.setContent(usecontent);
			      infowindow.open(map_<substitute-id>,this);
			      });
			    }    
			*******************/

		}

		// Sets up a marker and info window on the map at the latitude and longitude specified
		setupMarker2 = function(latitude, longitude, postcode)
		{
			var usepostcode = "";
			if (postcode != undefined)
				usepostcode = urldecode(postcode);

			// Generate the position from the given latitude and longitude values
			var pos = new google.maps.LatLng(latitude, longitude);
			// Define the marker on the map in the specified location
			var myIcon = '';
			var url = '';
			var marker = new google.maps.Marker({
				position: pos,
				map: map_<substitute-id>,
				icon: myIcon,
url: 'http://maps.google.com/?daddr='+postcode,
				title: 'Get directions to '+usepostcode
			});
			// Add a listener to this marker to display the information window on click
			google.maps.event.addListener(marker, 'click', function () {
window.location.href = marker.url;
			});
		}

		// Utility to decode PHP-encoded strings (messes up arguments to functions)
		function urldecode(url) {
			return decodeURIComponent(url.replace(/\+/g, ' '));
		}

END_OF_API_JS;

}
?>
