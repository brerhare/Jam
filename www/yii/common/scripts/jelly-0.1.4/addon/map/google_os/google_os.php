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
		// Check for map suppression (from the event plugin)
/*
		if ((isset($_GET['map'])) && ($_GET['map'] == "no"))
		{
			$retArr = array();
			$retArr[0] = "";
			$retArr[1] = "";
			return $retArr;
		}
*/

		// Generate the content into the html, replacing any <substituteN> tags
		$onReady = "";
		$inputMode = "";
		$mapId = "";
		foreach ($options as $opt => $val)
		{
			switch ($opt)
			{
				case "inputmode":
					$this->apiJs = str_replace("<substitute-inputmode>", strtoupper($val), $this->apiJs);
					$inputMode = $val;
					break;
				case "maptype":
					$this->apiJs = str_replace("<substitute-maptype>", strtoupper($val), $this->apiJs);
					break;
				case "zoom":
					$this->apiJs = str_replace("<substitute-zoom>", $val, $this->apiJs);
					break;
				case "id":
					$this->apiHtml = str_replace("<substitute-id>", $val, $this->apiHtml);
					$this->apiJs = str_replace("<substitute-id>", $val, $this->apiJs);
					break;
				case "width":
					$this->apiHtml = str_replace("<substitute-width>", $val, $this->apiHtml);
					break;
				case "height":
					$this->apiHtml = str_replace("<substitute-height>", $val, $this->apiHtml);
					break;
				case "center": // @@TODO this works for 'os' only
					$onReady .= '$(document).ready(function (){';
					if ($inputMode == "os")
						$onReady .= " centerByOs('" . $val . "');";

/* Commented because leaflet also uses this and its hardcoded to google maps */
					else if ($inputMode == "latlong")
					{
						$ll = explode(',', $val);
						//$onReady .= " centerByLatLong('" . $ll[0] . "','" . $ll[1] . "');";
						$onReady .= " markerByLatLong('" . $ll[0] . "','" . $ll[1] . "');";
					}
/**/
					else if ($inputMode == "postcode")
					{
						$vc = "NX832613";
						$onReady .= " centerByOs('" . $vc . "');";
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

<link rel="stylesheet" href="http://cdn.leafletjs.com/leaflet-0.7.3/leaflet.css" />
<script src="http://cdn.leafletjs.com/leaflet-0.7.3/leaflet.js"></script>

<!--
		<label for="osgridref">OS Grid Reference:</label>
		<input id="osgridref" type="text" style="width:200px;" />
		<input id="lookup" type="button" value="Lookup" />
-->
		<div id="<substitute-id>-map" style="width:<substitute-width>;height:<substitute-height>" border="1px solid black"></div>
	</div>

END_OF_API_HTML;

	private $apiJs = <<<END_OF_API_JS

		var mapId = "<substitute-id>";
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


/**************************************************************************************/
/* Replaced next 2 lines with 3rd to force google map to use leaflet for wild seasons */
/**************************************************************************************/
//				loadMap(latlong.lat, latlong.lng);
//				setupMarker(latlong.lat, latlong.lng, iconpath, hovertip, content);
markerByLatLongBigMap(latlong.lat, latlong.lng, hovertip);


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


/**************************************************************************************/
/* Replaced next 2 lines with 3rd to force google map to use leaflet for wild seasons */
/**************************************************************************************/
//				loadMap(latlong.lat, latlong.lng);
//				setupMarker2(latlong.lat, latlong.lng, postcode);
markerByLatLong(latlong.lat, latlong.lng, postcode);

			}
		}

// LEAFLET leaflet	Main map
		var map = null;	// This global is set a few lines down

		markerByLatLongBigMap = function(lat, long, hovertip)	// Big map
		{
//alert(urldecode(hovertip));
			if (map != null)
			{
				if ((isNaN(lat)) || (isNaN(long)))
					return;
				L.marker([lat, long]).addTo(map).bindPopup(urldecode(hovertip));
				return;
			}
			var id = mapId + "-map";
			map = L.map(id).setView([lat, long], 13);
			L.tileLayer('https://{s}.tiles.mapbox.com/v3/{id}/{z}/{x}/{y}.png', {
				maxZoom: 18,
				attribution: '',
				/*id: 'examples.map-i86knfo3'*/
				id: 'tekaweni.k8ngolij'
			}).addTo(map);
// This is timing point 1			//map.panTo(new L.LatLng(55.1213702,-3.3806166,12));						// center
// This is timing point 1			//map.setZoom(8);															// all of D&G
			var marker = L.marker([lat, long]).addTo(map);
		}

// LEAFLET leaflet	Little map for each event
		markerByLatLong = function(lat, long, postcode)	// Small map
		{
			var id = mapId + "-map";
			var map = L.map(id).setView([lat, long], 13);
			L.tileLayer('https://{s}.tiles.mapbox.com/v3/{id}/{z}/{x}/{y}.png', {
				maxZoom: 18,
				attribution: '',
				/*id: 'examples.map-i86knfo3'*/
				id: 'tekaweni.k8ngolij'
			}).addTo(map);
			var marker = L.marker([lat, long]).addTo(map);
		}

// LEAFLET leaflet	Make map encompass all markers
		markerBounds = function(points) {
			// Convert the passed array of os grid refs to array of lat-longs
			points2 = new Array();
			for (var i = 0; i < points.length; i++) {
				if (points[i].length > 0) {
					if (points[i].indexOf(',') != -1) {
						var eastnorth = points[i].split(',');
						points[i] = gridrefNumToLet(eastnorth[0], eastnorth[1], 10);
					}				
					var latlong = OSGridToLatLong(points[i]);
					if (isNaN(latlong.lat))
						continue;
					points2.push(latlong);
// This is timing point 2					//if (i == 2) alert(latlong.lat + ":" + latlong.lng);
				}
			}
			var bounds = new L.LatLngBounds(points2);
			map.fitBounds(bounds);
		}

		centerByLatLong = function(lat, long)
		{
			var center = new google.maps.LatLng(lat, long);
    		map_<substitute-id>.panTo(center);
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
window.open(marker.url,'_blank');
			});
		}

		// Utility to decode PHP-encoded strings (messes up arguments to functions)
		function urldecode(url) {
			return decodeURIComponent(url.replace(/\+/g, ' '));
		}

END_OF_API_JS;

}
?>
