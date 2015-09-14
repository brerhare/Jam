function backButton() {
	window.history.back();
}

/*
 * @param jamName		either empty (rerun this jam), just a 'something[.jam]' or a '/url/to/something[.jam]'
 */
function runJam(jamName) {
	var newLocation = jamName;				// Default is to assume a full url, ie use as supplied
	if (typeof jamName === 'undefined')
		newLocation = location.href;		// If empty grab the current url
	else if (jamName.indexOf('/') == -1) {
		// If just a 'something[.jam]' build a url using the full url base, the path to the current jamfile, and the new jamfile name
		newLocation = location.href.substring(0, location.href.lastIndexOf("/") + 1) + jamName;
	}
	window.location.href = newLocation;
}

/*
 * @param action	name of action to run.
 *					- 'actionName' only - current jam
 *					- 'jamName:actionName - different jam in same directory as current jam
 *					- '/path/to/jamName:actionName - use as is
 * @param element	element(s) to send. Space-separate multiples
					- form elements are expanded to their child elements
					- if it isnt an element then 'name=value' format is assumed and sent as given, eg 'stock_supplier._id=2'
 * @param output	HTML element that receives any returned content (innerHTML)
 * @param callback	note this cannot have arguments
 *
 * @note			we always try to send the _dbname element too for runactions
 */
function runAction(action, element, output, callback) {
//alert('startajax');
	if (typeof element === 'undefined') { elements = ''; }
	if (typeof output === 'undefined') { output = ''; }
	if (typeof callback === 'undefined') { callback = ''; }
	// Where we will send the request to
	var formURL = getURLBase();
	// Prepare the 'jam' parameter: 'somejam' or 'somejam:actionName'
	var urlSplit = basename(location.href).split("&");
	var thisJamName = urlSplit[0];
	var postData = 'x=y'; /* 'jam=';
	if (action.indexOf(':') == -1) {						// actionName only - current jam
		postData += thisJamName + ':' + action;
	} else {
		if (action.indexOf('/') == -1) {					// has ':' but no slashes - diff jam in same dir as curr jam
			var urlSplit = basename(location.href).split("&");
			postData += urlSplit[0] + '/' + action;
		}
		else												// has ':' and slashes - use as supplied
			postData += action;
	} */
	// Gather all the elements to send
	var el = element.split(" ");
	el.push("_dbname");											// always try to append this (for runactions)
	for (i = 0; i < el.length; i++) {
		if (document.forms[el[i]]) {							// is this a form element?
			var obj = $('form[name="' + el[i] + '"]');
			postData += '&' + obj.serialize();
			if (typeof obj.attr("action") != 'undefined')	// If a form (any form) has an 'action' we use it
				formURL = obj.attr("action");
		} else {											// not a form element
			var obj = document.getElementsByName(el[i]);		// .. try to get it
			if ((obj) && (obj.length > 0))					// got it
				postData += '&' + el[i] + '=' + encodeURIComponent(obj[0].value);
			else {											// not ANY kind of element, so just send as is (a=b)
				var lit = el[i].split('=');
				postData += '&' + lit[0] + '=' + lit[1];
			}
		}
//alert('assembling data. So far we have : ' + postData);
	}
alert('sending to - \nurl : ' + formURL + '\ndata : ' + postData);
	$.ajax( {
		url : formURL,
		type: "POST",
		data : postData,
		success:function(data, textStatus, jqXHR) {
//alert('back with: ' + data);
			if (output != '') {
				var target = document.getElementsByName(output);
				target[0].innerHTML = data;
			}
			if (callback != '') {
				callback();
			}
		},
		error: function(jqXHR, textStatus, errorThrown) {
			console.log(textStatus, errorThrown);
			alert('Ajax failure sending form ' + element + " \n" + errorThrown + "\n(" + textStatus + ")");
		}
	});
    //e.preventDefault(); //STOP default action
    //e.unbind(); //unbind. to stop multiple form submit.
	return false;
}

function getURLBase() {
	return location.protocol + '//' + location.host + location.pathname;
}

/*
 * What it says. myvar = getURLParameter('someparamname');
 */
function getURLParameter(name) {
  return decodeURIComponent((new RegExp('[?|&]' + name + '=' + '([^&;]+?)(&|#|;|$)').exec(location.search)||[,""])[1].replace(/\+/g, '%20'))||null
}

function basename(path) {
	return path.replace(/\\/g,'/').replace( /.*\//, '' );
}
 
function dirname(path) {
	return path.replace(/\\/g,'/').replace(/\/[^\/]*$/, '');;
}

// Place at end of html to run code after dom loaded but not waiting for images to finish loading
(function() {
   // your page initialization code here
   // the DOM will be available here
//alert('Popup from the anonymous function at the end of util.js');
})()

