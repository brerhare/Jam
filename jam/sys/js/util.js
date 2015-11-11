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
	var runURL = dirname(getURLBase());
	// Prepare the 'jam' parameter: 'somejam' or 'somejam:actionName'
	var urlSplit = basename(location.href).split("?");
	var runJam = urlSplit[0];

	if (action.indexOf(':') == -1) {						// actionName only - current jam
		runJam += ':' + action;
	} else {
		runJam = action;
	}
	// Gather all the elements to send
	var postData = 'jamDataRequested=1';
	var el = element.split(" ");
	el = runActionPreProcessGrid(el);							// expand 'SEQ_' to individual names for sending grid
	el.push("_dbname");											// always try to append this
	el.push("_initialUrlParams");								// any url parameters this page was initially called with
	for (i = 0; i < el.length; i++) {
		if (document.forms[el[i]]) {							// is this a form element?
			var obj = $('form[name="' + el[i] + '"]');			// yes its a form element
			postData += '&' + obj.serialize();
			if (typeof obj.attr("action") != 'undefined')		// If any form at all has an 'action' we use it
				formURL = obj.attr("action");
		} else {												// no its not a form element
			var obj = document.getElementsByName(el[i]);		// try to get it
			if (!(obj))
				obj = document.getElementById(el[i]);
			if ((obj) && (obj.length > 0)) {					// got it
				postData += '&' + el[i] + '=' + encodeURIComponent(obj[0].value);
			} else {											// not ANY kind of element, so just send as is (a=b)
				var lit = el[i].split('=');
				postData += '&' + lit[0] + '=' + lit[1];
			}
		}
//alert('assembling data. So far we have : ' + postData);
	}
	var sendURL = runURL + '/' + runJam;
console.log('AJAX sending to - \nurl : ' + sendURL + '\ndata : ' + postData);
	$.ajax( {
		url : sendURL,
		type: "POST",
		data : postData,
		success:function(data, textStatus, jqXHR) {
console.log('AJAX call returned data [' + data + '] of len ' + data.length);
			data = processOobData(decodeURIComponent(data));
console.log('AJAX data stripped of oob [' + data + '] of len ' + data.length);
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
			// Apply any JS that might have come in


scriptArr = [];
otherArr = [];
curPos = 0;
while ((startTag = data.indexOf("<script", curPos)) != -1) {
    if (startTag != curPos)
        otherArr.push(data.substring(curPos, startTag));
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
console.log("[" + data.substring(pos+1, endTag) + "]");
}
otherArr.push(data.substring(curPos));
console.log("extracted script array : " + scriptArr);
console.log("extracted other  array : " + otherArr);
for (i = 0; i < scriptArr.length; i++)
	window.eval(scriptArr[i]);


/**
			var script = data.replace("<script>", "");
			script = script.replace("</script>", "");
alert(script);
			window.eval(script);
xxx('HI!');
**/


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
// Jam helpers

// Call a jam-supplied event handler
function fn(obj, event) {
	var localFunc = '';
	if (event.type == 'change') {
		localFunc = 'on' + event.type.charAt(0).toUpperCase() + event.type.slice(1) + '_' + obj.id;
		if (localFunc.indexOf("_SEQ") != -1) {	// eg convert onChange_SEQ_3_customer.name to onChange_customer.name
			var uscore = localFunc.split("_");
			uscore.splice(1, 2);
			localFunc = uscore.join("_");
		}
	}
	localFunc = localFunc.split('.').join('_dot_');
	if (localFunc != '') {
		if (typeof window[localFunc] === "function")
			window[localFunc](obj);
	}
}

// Getter/setter for DOM elements. Id preferred to name. For now name is always name[0]
// someval = data('inputid').content();		.name() .id()
// data('inputid').content(someval);
var data = function(element) {
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
data.prototype.content = function(val) {
	if (val == null)
		return (this.obj instanceof HTMLInputElement) ? this.obj.value : this.obj.innerHTML;
	else
		(this.obj instanceof HTMLInputElement) ?  this.obj.value = val : this.obj.innerHTML = val;
};
data.prototype.id = function(val) {
	if (val == null)
		return this.obj.id;
	else
		this.obj.id = val;
};
data.prototype.name = function(val) {
	if (val == null)
		return this.obj.name;
	else
		this.obj.name = val;
};

function getElementContent(object) {
	if (target[0] instanceof HTMLInputElement) {
//		alert('is inp');
		target[0].value = data;
	} else {
//		alert('isnt inp');
		target[0].innerHTML = data;
	}
}

// Get a sibling element in a grid eg we want element 'SEQ_39_table.field'
function getSibling(callingObj, siblingName) {	// eg obj and 'table.field'
	return document.getElementById(getRowPrefix(callingObj) + siblingName);
}

// Get a non-grid element by name
function get(name) {
	return document.getElementsByName(name)[0];
}

// extract the row prefix ('SEQ_99_') from an object
function getRowPrefix(obj) {
	if (obj == null) {
		alert('Error: getRowPrefix cant work with a null object');
		return(null);
	}
	if (obj.id.indexOf("SEQ_") == -1) {
		alert('Error: getRowPrefix found no SEQ_ in the passed object id');
		return(null);
	}
	var idSplit = obj.id.split('_');
	if (idSplit.length < 3) {
		alert('Error: getRowPrefix requires at least 2 underscores in the passed object id');
		return(null);
	}
	return idSplit[0] + '_' + idSplit[1] + '_';
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
/*
function getSibling(callingObj, siblingName) {	// eg obj and 'table.field'
	return document.getElementById(getRowPrefix(callingObj) + siblingName);

		if (localFunc.indexOf("_SEQ") != -1) {	// eg convert onChange_SEQ_3_customer.name to onChange_customer.name
			var uscore = localFunc.split("_");
			uscore.splice(1, 2);
			localFunc = uscore.join("_");
*/

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
// OOB (returning from actions)

function processOobData(data) {
	var spl = data.split("{oobData}");
	if (spl.length > 1) {
		console.log('----- oob data begins ---------------------------------------------------------------');
		var oobData = spl[1];
//alert('found oob data:' + oobData + ' of length ' + oobData.length);
		var oob = [];
		oob = JSON.parse(spl[1]);
		for (i = 0; i < oob.length; i++) {
			var oobName = oob[i]['name'];
			var oobValue = oob[i]['value'];
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
		// Strip out oob from data
		data = spl[0];
		console.log('----- oob data ends -----------------------------------------------------------------');
	}
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
})()

