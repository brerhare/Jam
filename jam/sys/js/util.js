/*
 * @param templateName		either just a 'template.tpl' or a '/full/url/to/temaplate.tpl'
 */
function runTemplate(templateName) {
	var newLocation = templateName;
	if (templateName.indexOf('/') == -1) {
		currLocation = location.href;
		var value = currLocation.substring(currLocation.lastIndexOf('/') + 1);
		var newLocation = currLocation.replace(value, templateName);
	}
	window.location.href = newLocation;
}


/*
 * 
 */
function backButton() {
	window.history.back();
}

/*
 * @param forms		can have several space-separated forms. Optional
 * @param output	HTML element to fill with the returned content
 */
function runAction(action, forms, output) {
	if (typeof forms === 'undefined') { forms = ''; }
	if (typeof output === 'undefined') { output = ''; }
	var form = forms.split(" ");
	var postData = '';
	for (i = 0; i < form.length; i++) {
//alert(form[i]);
		var obj = $('form[name="' + form[i] + '"]');
		if (postData != '')
			postData += '&';
		postData += obj.serialize();
//alert('assembling data. So far we have : ' + postData);
	}
	var formURL = obj.attr("action");
	var formURL = $(this).attr("action");
	if ((typeof formURL == 'undefined') || (formURL == ''))
		formURL = window.location.href;
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

// Place at end of html to run code after dom loaded but not waiting for images to finish loading
(function() {
   // your page initialization code here
   // the DOM will be available here

})()

