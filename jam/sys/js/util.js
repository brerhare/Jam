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
 * @param forms		can have several space-separated forms. Optional
 * @param forms		can also be js:fieldname or js:literalvalue
 * @param output	HTML element to fill with the returned content
 */
function runAction(action, forms, output) {
	if (typeof forms === 'undefined') { forms = ''; }
	if (typeof output === 'undefined') { output = ''; }
	var formURL = window.location.href;
	var thisTemplateName = window.location.search.substr(1);
	var postData = 'template=' + getURLParameter('template');

// @@TODO need to work :js into the form loop so can have multiple :js's. 
// @@TODO need to cater for template:action. At present it only caters for actions within the current form

	jsIx = forms.indexOf('js:');
	if (jsIx != -1) {
		postData += '&js=' + forms.substr(jsIx+3);
//alert('js data : ' + postData);
	} else {
		var form = forms.split(" ");
		for (i = 0; i < form.length; i++) {
//alert(form[i]);
			var obj = $('form[name="' + form[i] + '"]');
			postData += '&' + obj.serialize();
//alert('assembling data. So far we have : ' + postData);
		}
		if (typeof obj.attr("action") != 'undefined')
			formURL = obj.attr("action");
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
		},
		error: function(jqXHR, textStatus, errorThrown) {
			alert('Ajax failure sending form ' + forms + '. Error: ' + textStatus);
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

