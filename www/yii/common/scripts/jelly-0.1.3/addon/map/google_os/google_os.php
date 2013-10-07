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
	private $defaultWidth = "620px";
	private $defaultHeight = "200px";

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
				case "width":
					$tmp = str_replace("<substitute-width>", $val, $this->apiHtml);
					$this->apiHtml = $tmp;
					break;
				case "height":
					$tmp = str_replace("<substitute-height>", $val, $this->apiHtml);
					$this->apiHtml = $tmp;
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
		if (strstr($this->apiHtml, "<substitute-height>"))
		{
			$tmp = str_replace("<substitute-height>", $this->defaultHeight, $this->apiHtml);
			$this->apiHtml = $tmp;
		}
		// JS

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

	<div id="jelly-google-os-container">
		<script type="text/javascript" src="//maps.google.com/maps/api/js?sensor=false"></script>
		<script type="text/javascript" src="<substitute-path>/latlong-gridref.js"></script>

		<label for="osgridref">OS Grid Reference:</label>
		<input id="osgridref" type="text" style="width:200px;" />
		<input id="lookup" type="button" value="Lookup" />
		<div id="map" style="width:600px;height:600px;margin-top:10px;"></div>
	</div>

END_OF_API_HTML;

	private $apiJs = <<<END_OF_API_JS

		var map = null;
		$(document).ready(function () {
			// Set values for latitude and longitude
			var latitude = parseFloat("51.515252");
			var longitude = parseFloat("-0.189852");
			// Setup the Google map
			loadMap(latitude, longitude);
			// Add the marker
			setupMarker(latitude, longitude);
			// Setup the address lookup on the button click event
			$('#lookup').click(function() {
				var osgridref = $('#osgridref').val();
				if (osgridref.length > 0) {
					if (osgridref.indexOf(',') != -1) {
						var eastnorth = osgridref.split(',');
						osgridref = gridrefNumToLet(eastnorth[0], eastnorth[1], 10);
					}				
					var latlong = OSGridToLatLong(osgridref);
					setupMarker(latlong.lat, latlong.lng);
				}
			});
		});
		// Loads the maps
	    loadMap = function (latitude, longitude) {
			var latlng = new google.maps.LatLng(latitude, longitude);
			var myOptions = {
				zoom: 7,
				center: latlng,
				mapTypeId: google.maps.MapTypeId.ROADMAP
			};
			map = new google.maps.Map(document.getElementById("map"), myOptions);
		}
		// Setups a marker and info window on the map at the latitude and longitude specified
		setupMarker = function(latitude, longitude) {
			// Generate the position from the given latitude and longitude values
			var pos = new google.maps.LatLng(latitude, longitude);
			// Define the marker on the map in the specified location
			var marker = new google.maps.Marker({
				position: pos,
				map: map,
				title: name
			});
			// Add a listener to this marker to display the information window on click
			var info = "This is a marker for the following co-ordinates:<br />latitude: " + latitude + "<br/>longitude: " + longitude;
			google.maps.event.addListener(marker, 'click', function () {
				var infowindow = new google.maps.InfoWindow({
					content: info
				});
				infowindow.open(map, marker);
			});
		}

/*------------------------

	jQuery(document).ready(function($){
    	jkmegamenu.render($);
	})

	function recalcPos()
	{
		var width4Col = 190;
		var rectAnchor = document.getElementById("megaanchor1").getBoundingClientRect();
		var adjustLeft = (rectAnchor.left - width4Col);
		var adjustTop = (rectAnchor.bottom + 0);
		//alert(adjustTop);
		//document.getElementById("megamenu1").setAttribute("style", "left:" + (rectAnchor.left - width4Col) + "px;");
		document.getElementById("megamenu1").style.left = adjustLeft + "px";
		document.getElementById("megamenu1").style.top = adjustTop + "px";
	}
-------------------------*/

END_OF_API_JS;

}
?>
