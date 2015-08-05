function runTemplate(templateName) {
//window.location.href = '/index.html';
alert('11');
//window.location.href = '/jamcgi/jam?template=/jam/template2/supplierMaintForm.tpl';
window.location.href = 'http://www.stackoverflow.com';
window.top.location.href = 'http://stackoverflow.com';
$(location).attr('href', 'http://stackoverflow.com')
alert('fe');
return(false);
}

function _getFormVars(formName) {
    console.log($(formName).serialize());
    return $(formName).serialize();
}

//alert('pre');
//alert(_getFormVars("supplierform"));
//alert('post');


function myFunction(formName) {
	var kvpairs = [];
	var form = document.getElementById(formName);
	for (var i = 0; i < form.elements.length; i++ ) {
		var e = form.elements[i];
		kvpairs.push(encodeURIComponent(e.name) + "=" + encodeURIComponent(e.value));
	}
	var queryString = kvpairs.join("&");
	console.log(queryString);
	return false;
}

// Place at end of html to run code after dom loaded but not waiting for images to finish loading
(function() {
   // your page initialization code here
   // the DOM will be available here

//	var ajax = new Ajax();
//	ajax.post('http://localhost/jamcgi/jam', 'template=/jam/template/supplier.tpl').done(function(response, xhr) {
//	//ajax.get( 'http://localhost/jamcgi/jam?template=/jam/template/supplier.tpl' ).done(function( response, xhr ) {
//		alert(response);
//	});

})()

