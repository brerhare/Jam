function backButton() {
	window.history.back();
}

/*
 * @param templateName		either empty (rerun this template), just a 'template.tpl' or a '/full/url/to/temaplate.tpl'
 */
function runTemplate(templateName) {
	var newLocation = templateName;				// Default is to assume a full url, ie use as supplied
	if (typeof templateName === 'undefined')
		newLocation = location.href;			// If empty grab the current url
	else if (templateName.indexOf('/') == -1)
		// If just a 'template.tpl' build a url using the full url base, the path to the current template, and the new template name
		newLocation = getURLBase() + '?template=' + dirname(getURLParameter('template')) + '/' + templateName;
	window.location.href = newLocation;
}

/*
 * @param action	name of action to run.
 *					- 'actionName' only - current template
 *					- 'templateName:actionName - different template in same directory as current template
 *					- '/path/to/templateName:actionName - use as is
 * @param element	element(s) to send. Space-separate multiples
					- form elements are expanded to their child elements
					- if it isnt an element then 'name=value' format is assumed and sent as given, eg 'stock_supplier._id=2'
 * @param output	HTML element that receives any returned content (innerHTML)
 * @param callback	note this cannot have arguments
 */
function runAction(action, element, output, callback) {
//alert('startajax');
	if (typeof element === 'undefined') { elements = ''; }
	if (typeof output === 'undefined') { output = ''; }
	if (typeof callback === 'undefined') { callback = ''; }
	// Where we will send the request to
	var formURL = getURLBase();
	// Prepare the 'template' parameter: 'template.tpl' or 'template.tpl:actionName'
	var postData = 'template=';
	if (action.indexOf(':') == -1)							// actionName only - current template
		postData += getURLParameter('template') + ':' + action;
	else {
		if (action.indexOf('/') == -1)						// has ':' but no slashes - diff tpl in same dir as curr tpl
			postData += dirname(getURLParameter('template')) + '/' + action;
		else												// has ':' and slashes - use as supplied
			postData += action;
	}
	// Gather all the elements to send
	var el = element.split(" ");
	for (i = 0; i < el.length; i++) {
		if (document.forms[el]) {							// is this a form element?
			var obj = $('form[name="' + el[i] + '"]');
			postData += '&' + obj.serialize();
			if (typeof obj.attr("action") != 'undefined')	// If a form (any form) has an 'action' we use it
				formURL = obj.attr("action");
		} else {											// not a form element
			var obj = document.getElementsByName(el);		// .. try to get it
			if (obj.length > 0)								// got it
				postData += '&' + el + '=' + encodeURIComponent(obj[0].value);
			else {											// not ANY kind of element, so just send as is (a=b)
				var lit = el[i].split('=');
				postData += '&' + lit[0] + '=' + lit[1];
			}
		}
//alert('assembling data. So far we have : ' + postData);
	}
//alert('sending to ' + formURL + ' data : ' + postData);
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

})()

