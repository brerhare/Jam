// ----------------------------------------------------------------------------------------------------------
// Navigation

function backButton() {
	window.history.back();
}

function runUrl(url, newTab) {
	if ((typeof newTab === 'undefined') && (newTab != 1) && (newTab != 'yes'))
		window.open(url, '_self');
	else
		window.open(url,'_blank');
}

/*
 * @param jamName		either empty (rerun this jam), just a 'something[.jam]' or a '/url/to/something[.jam]'
 */
function runJam(jamName, newTab) {
	if (typeof newTab === 'undefined') { newTab = 0; }
	var newLocation = jamName;				// Default is to assume a full url, ie use as supplied
	if (typeof jamName === 'undefined')
		newLocation = location.href;		// If empty grab the current url
	else if (jamName.indexOf('/') == -1) {
		// If just a 'something[.jam]' build a url using the full url base, the path to the current jamfile, and the new jamfile name
		newLocation = location.href.substring(0, location.href.lastIndexOf("/") + 1) + jamName;
	}
	//window.location.href = newLocation;
	if (newTab == 1)
		window.open(newLocation, '_blank');
	else
		window.open(newLocation);
}

/*
 * @param action	name of action to run.
 *					- 'actionName' only - current jam
 *					- 'jamName:actionName - different jam in same directory as current jam
 *					- '/path/to/jamName:actionName - use as is
 * @param element	element(s) to send. Array
					- form elements are expanded to their child elements
					- if it isnt an element then 'name=value' format is assumed and sent as given, eg 'stock_supplier._id=2'
 * @param output	HTML element that receives any returned content (innerHTML)
 * @param callback	note this cannot have arguments
 *
 * @note			we always try to send the _dbname element too for runactions
 */
function runAction(action, element, output, callback) {
//alert('startajax');
	if (typeof element === 'undefined') { element = []; }
	if (typeof output === 'undefined') { output = ''; }
	if (typeof callback === 'undefined') { callback = ''; }
	// Where we will send the request to
	var runURL = dirname(getURLBase());
	// Prepare the 'jam' parameter: 'somejam' or 'somejam:actionName'
	var urlSplit = basename(location.href).split("?");
console.log('URLSPLIT='+urlSplit);
console.log('RUNJAM='+runJam);
console.log('location.href='+location.href);
console.log('basename location.href='+basename(location.href).split("?") );
	var runJam = urlSplit[0];

	if (action.indexOf(':') == -1) {						// actionName only - current jam
		runJam += ':' + action;
	} else {
		runJam = action;
	}



/* kludge @@TODO @@BUG @@FIXME */
	if (runJam == ":sendMessage")
		runJam = "/run/contactForm:sendMessage";



	// Gather all the elements to send
	var postData = 'oobDataRequested=1';
/////////////////////////	el = runActionPreProcessGrid(el);							// expand 'SEQ_' to individual names for sending grid
	element = runActionIncludeGroupElements(element);				// expand any group (class) names to all their element names
	element.push("_dbname");											// always try to append this
	element.push("_initialUrlParams");								// any url parameters this page was initially called with
	for (i = 0; i < element.length; i++) {
		if (element[i] == '')
			continue;
		if (document.forms[element[i]]) {							// is this a form element?
			var obj = $('form[name="' + element[i] + '"]');			// yes its a form element
			postData += '&' + obj.serialize();
			if (typeof obj.attr("action") != 'undefined')		// If any form at all has an 'action' we use it
				formURL = obj.attr("action");
		} else {												// no its not a form element
			var obj = document.getElementsByName(element[i]);		// try to get it
			if (!(obj))
				obj = document.getElementById(element[i]);
			if ((obj) && (obj.length > 0)) {					// got it
				postData += '&' + element[i] + '=' + encodeURIComponent(obj[0].value);
			} else {											// not ANY kind of element, so just send as is (a=b)
				postData += '&' + element[i];
			}
		}
//alert('assembling data. So far we have : ' + postData);
	}
	var sendURL = runURL + '/' + runJam;
console.log('runURL='+runURL+' runJam='+runJam+' sendURL='+sendURL);
console.log('AJAX sending to - \nurl : ' + sendURL + '\ndata : ' + postData);
	$.ajax( {
		url : sendURL,
		type: "POST",
		data : postData,
		success:function(data, textStatus, jqXHR) {
console.log('AJAX call returned raw data [' + data + '] of len ' + data.length);
			data = processOobData(decodeURIComponent(data));
//console.log('AJAX data stripped of oob [' + data + '] of len ' + data.length);
			data = processScriptData(data);
//console.log('AJAX data stripped of script tags [' + data + '] of len ' + data.length);
			if (output != '') {
				var target = document.getElementsByName(output);
				if (target[0] instanceof HTMLInputElement) {
//					alert('is inp');
					target[0].value = data;
				} else {
//					alert('isnt inp');
					if (typeof target[0] !== 'undefined')
						target[0].innerHTML = data;
				}
			}

			// Callback if one was given
			if (callback != '')
				callback();
		},
		error: function(xhr, textStatus, errorThrown) {
			console.log(xhr, textStatus, errorThrown);
			alert('Ajax failure communicating with server: ' + xhr.status + "(" + xhr.responseText + ")\n" + errorThrown + "\n(" + textStatus + ")");
		}
	});
    //e.preventDefault(); //STOP default action
    //e.unbind(); //unbind. to stop multiple form submit.
	return false;
}

function getURLBase() {		// everything but the arguments. ie up to but not including the '?'
//alert('host='+location.host+' path='+location.pathname);
	return location.protocol + '//' + location.host + location.pathname;
}

/*
 * @param requestedvar		eg myvar = getURLParameter('someparamname');
 * @return					eg someparamvalue
 * usage					someparamvalue = getURLParameter('someparamname');
 */
function getURLParameter(name) {
  return decodeURIComponent((new RegExp('[?|&]' + name + '=' + '([^&;]+?)(&|#|;|$)').exec(location.search)||[,""])[1].replace(/\+/g, '%20'))||null
}

/*
 * @param path		eg /etc/passwd
 * @return			eg passwd
 */
function basename(path) {
	return path.replace(/\\/g,'/').replace( /.*\//, '' );
}
 
/*
 * @param path		eg /etc/passwd
 * @return			eg /etc
 */
function dirname(path) {
	return path.replace(/\\/g,'/').replace(/\/[^\/]*$/, '');;
}

// ----------------------------------------------------------------------------------------------------------
// Event handlers

// Call a jam-supplied event handler
function fn(obj, event) {
	console.log('fn() checking if event ' + event.type + ' is handled');
	var localFunc = '';
	var callType = 'x';
	if (event.type == 'change')
		callType = 'onChange';
	else if (event.type == 'keyup')
		callType = 'onKeyUp';
	else if (event.type == 'click')
		callType = 'onClick';
	if (callType != '') {
		localFunc =  callType + '_' + obj.id;
		// Convert ID3___stock_customer___address_1 to onChange_stockcustomer_address_1
		if ((obj.id.substring(0, 2) == 'ID') || (obj.id.substring(0, 3) == 'VAR')) {
			var parts = obj.id.split('___');
			parts.splice(0, 1);	// lose the 'ID___' or 'VAR___'
			for (i = 0; i < parts.length; i++)
				parts[i] = parts[i].split('_').join('');
			for (i = 0; i < parts.length; i++)
				parts[i] = parts[i].split('.').join('_');	// VAR's with dots in their name need this
			localFunc = callType + '_' + parts.join('_');
		}
console.log( 'fn (' + event.type + ') looking for user supplied function "' + localFunc + '"' );
		if ((localFunc != '') && (typeof window[localFunc] === "function"))
			window[localFunc](obj);
	}
}

// ----------------------------------------------------------------------------------------------------------
// Element setters and getters

// Getter/setter for DOM elements. Id preferred to name. For now name is always name[0]
// someval = data('inputid').content();		.name() .id()
// data('inputid').content(someval);
/* var data = function(element) {	// @@NU
	// Self instantiate if necessary. http://programmers.stackexchange.com/questions/118798/avoiding-new-operator-in-javascript-the-better-way
	if (Object.getPrototypeOf(this) !== data.prototype) {
		var o = Object.create(data.prototype);
		o.constructor.apply(o, arguments);
		return o;
  	}
	this.obj = document.getElementById(element);
	if (this.obj == null) {
		this.obj = document.getElementsByName(element)[0];
		if (this.obj == null) {
			console.log('data: invalid element ' + element);
			return null;
		}
	}
}
data.prototype.content = function(val) {	// @@NU
	if (val == null)
		return (this.obj instanceof HTMLInputElement) ? this.obj.value : this.obj.innerHTML;
	else
		(this.obj instanceof HTMLInputElement) ?  this.obj.value = val : this.obj.innerHTML = val;
};
data.prototype.id = function(val) {	// @@NU
	if (val == null)
		return this.obj.id;
	else
		this.obj.id = val;
};
data.prototype.name = function(val) {	// @@NU
	if (val == null)
		return this.obj.name;
	else
		this.obj.name = val;
}; */

/* function getElementContent(object) {
	if (target[0] instanceof HTMLInputElement) {
//		alert('is inp');
		target[0].value = data;
	} else {
//		alert('isnt inp');
		target[0].innerHTML = data;
	}
} */

function getSiblingByName(callingObj, siblingName) {
	if (callingObj != null) {
		var callingClassArr = callingObj.className.split(' ');
		for (var i = 0; i < callingClassArr.length; i++) {
			if (callingClassArr[i].substring(0, 4) == 'ROW_') {
				var groupClassArr = document.getElementsByClassName(callingClassArr[i]);	// all the row elements
				for (j = 0; j < groupClassArr.length; j++) {
					if (groupClassArr[j].name.match(siblingName))
						return(groupClassArr[j]);
					//console.log('==> ' + groupClassArr[j].name + ' = ' + groupClassArr[j].value);
				}
			}
		}
	}
	alert('Error: getSiblingByName failed to find ' + siblingName);
	return null;
}

// Get an array of elements belonging to a group
function getGroupArray(groupName) {
	var groupCollection = [];
	//groupArr = document.getElementsByClassName(groupName);
	groupCollection = document.getElementsByClassName(groupName);
	var groupArr = Array.prototype.slice.call(groupCollection)
	return groupArr;
}

// Get the row 'group' name an element belongs to (its class name that is something like 'ROW_538904')
function getRowgroupName(callingObj) {
	if (callingObj != null) {
		var callingClassArr = callingObj.className.split(' ');
		for (var i = 0; i < callingClassArr.length; i++) {
			if (callingClassArr[i].substring(0, 4) == 'ROW_') {
				return(callingClassArr[i]);
			}
		}
	}
	alert('Error: getRowGroupName failed to find rowgroup for ' + callingObj.name);
	return null;
}

// Get an element by group (class) name and element name
function getByGroupAndName(groupName, elementName) {
	var groupClassArr = document.getElementsByClassName(groupName);
	for (i = 0; i < groupClassArr.length; i++) {
		if (groupClassArr[i].name.match(elementName))
			return(groupClassArr[i]);
	}
	return null;
}

// Is the object in the group?
function hasGroup(obj, groupName) {
	if (obj != null) {
		var classArr = obj.className.split(' ');
		for (var i = 0; i < classArr.length; i++) {
			if (classArr[i].match(groupName)) {
				return 1;
			}
		}
	}
	return 0;
}

// Get a non-grid element by name
function get(name) {	// @@NU
	return document.getElementsByName(name)[0];
}

// If a runAction element-to-send names a group (class) instead then replace it in the array with all it's elements
function runActionIncludeGroupElements(elArr) {
console.log('pre-expand-----------------------------------');
console.log(elArr);
	var newArr = [];
	for (i = 0; i < elArr.length; i++) {
		var groupClassArr = document.getElementsByClassName(elArr[i]);
		if (groupClassArr.length > 0) {
			for (j = 0; j < groupClassArr.length; j++) {
				newArr.push(groupClassArr[j].name);
			}
		} else {
			newArr.push(elArr[i]);
		}
	}
console.log('post-expand-----------------------------------');
console.log(newArr);
//alert('waiting');
	return newArr;
}

// If there is any SEQ_ item create element.name's for ALL sibling grid element.id to send to server, and return the modified element array to runAction
function runActionPreProcessGrid(elArr) {
	var seq = "";
	var newArr = [];
	for (i = 0; i < elArr.length; i++) {
		if (elArr[i].indexOf("SEQ_") != -1) {
			var obj = document.getElementById(elArr[i].id);
			var seqArr = elArr[i].split("_");
			seq = seqArr[0] + '_' + seqArr[1] + '_';
		} else {
			newArr.push(elArr[i]);
		}
	}
	// @@TODO Very inefficient looping thru all id's this way... consider using a class to group grid fields
	if (seq != "") {			// found at least one SEQ_ (and dropped ALL occurrences from the array)
		var allElements = document.getElementsByTagName("*");
		for (var i = 0, n = allElements.length; i < n; ++i) {
			var el = allElements[i];
			if ((el.id) && (el.id.substring(0, seq.length) == seq)) {
				var newName =  el.id.substring(seq.length);
				var gridElement = document.getElementsByName(newName);
				if (gridElement[0])
					 document.body.removeChild(gridElement[0]);
				gridElement = document.createElement("input");
				gridElement.setAttribute("type", "hidden");
				gridElement.setAttribute("name", newName);
				gridElement.setAttribute("value", el.value);
				document.body.appendChild(gridElement);
				newArr.push(newName);		// add it to the array
				console.log('Appending grid element ' + newName);
			}
		}
		console.log('Expanded array to send is ' + newArr);
	}
	return newArr;
}

// ----------------------------------+-----------------------------------------------------------------------
// Init

// Create a hidden form with input elements for any parameters embedded in the URL that this page was initially called with 
//                                                  ------------------------------
function createHiddenElementsFromUrlParams() {
	// Create the initialUrlParams container form
	var initialUrlParamsForm = document.createElement('form');
	initialUrlParamsForm.setAttribute("type", "hidden");
	initialUrlParamsForm.setAttribute("name", "_initialUrlParams");
	initialUrlParamsForm.setAttribute("id", "_initialUrlParams");
	document.body.appendChild(initialUrlParamsForm);
	// Now fill it with any params after the ? or &
	var srch = window.location.search;
	if ((srch.indexOf('?') == -1) && (srch.indexOf('&') == -1))
		return;
	parArr = srch.split("?")[1].split("&");
	for (var i = 0; i < parArr.length; i++) {
		parr = parArr[i].split("=");
//alert('name:['+parr[0]+'] has value:['+decodeURIComponent(parr[1])+']');
		var input = document.createElement("input");
		input.setAttribute("type", "hidden");
		input.setAttribute("name", parr[0]);
		input.setAttribute("id", parr[0]);
		input.setAttribute("value", decodeURIComponent(parr[1]));
		initialUrlParamsForm.appendChild(input);
	}
}

// ----------------------------------+-----------------------------------------------------------------------
// Returning from ajax runAction

// Process any OOB data embedded in the returned data
function processOobData(data) {
	console.log('----- oob data begins ---------------------------------------------------------------');
	var spl = data.split("{oobData}");
	if (spl.length > 1) {
		var oobData = spl[1];
console.log('found oob data:' + oobData + ' of length ' + oobData.length);
		var oob = [];
		oob = JSON.parse(spl[1]);
		for (i = 0; i < oob.length; i++) {
			var oobName = oob[i]['name'];
			var oobValue = oob[i]['value'];
			if (oobName == 'notifyStatus') {
				notifyStatus(oobValue);
			} else {
				var obj = document.getElementsByName(oobName);
				if (obj[0] == null) {
					var input = document.createElement("input");
					input.setAttribute("type", "hidden");
					input.setAttribute("name", oobName);
					input.setAttribute("id", oobName);
					input.setAttribute("value", oobValue);
console.log('creating ' + oobName + ' : ' + oobValue);
					document.body.appendChild(input);
				} else {
console.log('updating ' + oobName + ' : ' + oobValue);
					obj[0].value = oobValue;
				}
			}
		}
		// Strip out oob from data
		data = spl[0];
	}
	console.log('----- oob data ends -----------------------------------------------------------------');
	return data;
}

function notifyStatus(status) {
	toastr.options = {
		"preventDuplicates": true,
		"timeOut": "200",
		"closeButton": false,
		"debug": false,
		"newestOnTop": false,
		"progressBar": false,
		"positionClass": "toast-top-right",
		"onclick": null,
		"showDuration": "300",
		"hideDuration": "1000",
		"extendedTimeOut": "1000",
		"showEasing": "swing",
		"hideEasing": "linear",
		"showMethod": "fadeIn",
		"hideMethod": "fadeOut"
	}
	if (status == 'ok')
		toastr.success('success');
	else if (status == 'fail')
		toastr.error('error');
	else if (status == 'info')
		toastr.error('info');
	else if (status == 'warn')
		toastr.error('warning');
}

// Enable any <script> tags in the returned data
function processScriptData(data) {
	// Split off and apply any JS that might have come in
	scriptArr = [];
	htmlArr = [];
	curPos = 0;
	while ((startTag = data.indexOf("<script", curPos)) != -1) {
		if (startTag != curPos)
			htmlArr.push(data.substring(curPos, startTag));
		pos = data.indexOf(">", startTag);
		if (pos == -1) {
			alert('script start tag incomplete');
		}
		endTag = data.indexOf("</scri" + "pt>", pos+1);
		if (endTag == -1)
			alert('script start tag has no end tag');
		else {
			scriptArr.push(data.substring(pos+1, endTag));
		}
		curPos = endTag + 9;                                            // just after the closing ">"
		//console.log("[" + data.substring(pos+1, endTag) + "]");
	}
	htmlArr.push(data.substring(curPos));
	console.log('----- script data begins ------------------------------------------------------------');
	for (i = 0; i < scriptArr.length; i++) {
		console.log(scriptArr[i]);
		window.eval(scriptArr[i]);
	}
	console.log('----- script data ends --------------------------------------------------------------');
	console.log('----- html data begins --------------------------------------------------------------');
	console.log(htmlArr.join(''));
	data = htmlArr.join('');
	console.log('----- html data ends -----------------------------------------------------------------');
	return data;
}

function jsonEscapeChars(str) {
	return str.replace(/\\n/g, "\\n").replace(/\\"/g, '\\"').replace(/\\r/g, "\\r").replace(/\\t/g, "\\t").replace(/\\b/g, "\\b").replace(/\\f/g, "\\f").replace(/\\/g, "\\\\");
}


// --------------------------------------------------------+-----------------------------------------------------
// Printing

function print(element) {
	pre = "<style> .uk-button { display:none; } </style>";
	var content = pre + document.getElementById(element).innerHTML;
	var printContent = pre + content;
	var WinPrint = window.open('', '', 'letf=0,top=0,width=800,height=900,toolbar=0,scrollbars=0,status=0');
	WinPrint.document.write(printContent);
	WinPrint.document.close();
	WinPrint.focus();
	WinPrint.print();
	WinPrint.close();
}

// --------------------------------------------------------+-----------------------------------------------------
// End. Only put things after here for this function to do | 
// --------------------------------------------------------+

// Place at end of html to run code after dom loaded but not waiting for images to finish loading
(function() {
   // your page initialization code here
   // the DOM will be available here
//alert('Popup from the anonymous function at the end of util.js');
	createHiddenElementsFromUrlParams();
	// User autoexec
	// @@TODO This doesnt work?
	var startupFunc = 'autoexec';
	if (typeof window[startupFunc] === "function") {
//alert('firing');
		window[startupFunc]; }
})()

