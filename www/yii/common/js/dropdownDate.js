String.prototype.replaceAt=function(index, characters) {
	return this.substr(0, index) + characters + this.substr(index+characters.length);
}
String.prototype.pad=function(width, character) {
	character = character || '0';
	return this.length >= width ? this : new Array(width - this.length + 1).join(character) + this;
}

// NB 0-based month number
function daysInMonth(month) {
	var monthStart = new Date(year, month, 1);
	var monthEnd = new Date(year, month + 1, 1);
	return (monthEnd - monthStart) / (1000 * 60 * 60 * 24);
}

/*
 * Input date as dropdown select/options. "dd mth yyyy"
 *
 * @param containerDivId		div (or span) to inject select/option tags
 * @param targetElementId		element id to read/write
 * @param templateStr			date format template eg "yyyy-mm-dd" or "dd-mm-yyyy hr:mn"
 */

function dropdownDate(containerDivId, targetElementId, templateStr)
{
	var monthText=['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sept','Oct','Nov','Dec'];

	// validate format string
	ddPos = templateStr.indexOf("dd");
	mmPos = templateStr.indexOf("mm");
	yyyyPos = templateStr.indexOf("yyyy");
	if ((ddPos == -1) || (mmPos == -1) || (yyyyPos == -1))
	{
		alert("Invalid format string");
		return;
	}
	hrPos = templateStr.indexOf("hr");
	mnPos = templateStr.indexOf("mn");

	// Default date to today
	var today=new Date();
	dd = today.getDate();
	mm = (today.getMonth() + 1);
	yyyy = today.getFullYear();

	// Default time to zero // now
	hr = 0; // (today.getHours() + 1);
	mn = 0; // (today.getMinutes() + 1);

	// Override default date with input value
	target = document.getElementById(targetElementId);
	ddChk = parseInt(target.value.slice(ddPos, (ddPos+2)));
	mmChk = parseInt(target.value.slice(mmPos, (mmPos+2)));
	yyyyChk = parseInt(target.value.slice(yyyyPos, (yyyyPos+4)));
	if ((ddChk >= 1) && (ddChk <= 31))
		dd = ddChk;
	if ((mmChk >= 1) && (mmChk <= 12))
		mm = mmChk;
	if ((yyyyChk >= 1900) && (yyyyChk <= 2100) && (yyyyChk != 1970))
		yyyy = yyyyChk;

	// Override default time with input value
	if (hrPos != -1)
	{
		hrChk = parseInt(target.value.slice(hrPos, (hrPos+2)));
		if ((hrChk >= 0) && (hrChk <= 23))
			hr = hrChk;
	}
	if (mnPos != -1)
	{
		mnChk = parseInt(target.value.slice(mnPos, (mnPos+2)));
		if ((mnChk >= 0) && (mnChk <= 59))
			mn = mnChk;
	}

	// Setup day
	selectHTML = "<select style='width:50px' onChange='dropdownDateSet(\"dd\", this.value, \"" + targetElementId + "\", \"" + templateStr + "\")'>";
	for (var i=0; i<31; i++)
	{
		selected = "";
		if ((i+1) == dd)
			selected = "selected";
		selectHTML+= "<option " + selected + " value='"+ (i+1) +"'>"+ (i+1) +"</option>";
	}
	selectHTML += "</select>";

	// Setup month
	selectHTML += "<select onChange='dropdownDateSet(\"mm\", this.value, \"" + targetElementId + "\", \"" + templateStr + "\")'>";
	for (var i=0; i<12; i++)
	{
		selected = "";
		if (i == (mm-1))
			selected = "selected";
		selectHTML+= "<option " + selected + " value='"+ (i+1) +"'>"+ monthText[i] +"</option>";
	}
	selectHTML += "</select>";

	// Setup year
	selectHTML += "<select onChange='dropdownDateSet(\"yyyy\", this.value, \"" + targetElementId + "\", \"" + templateStr + "\")'>";
	startYear = today.getFullYear();
	for (var i=startYear; i<(startYear+10); i++)
	{
		selected = "";
		if (i == yyyy)
			selected = "selected";
		selectHTML+= "<option " + selected + " value='"+ i +"'>"+ i +"</option>";
	}
	selectHTML += "</select>";

	// Setup hour
	if (hrPos != -1)
	{
		selectHTML += "&nbsp at &nbsp<select style='width:50px' onChange='dropdownDateSet(\"hr\", this.value, \"" + targetElementId + "\", \"" + templateStr + "\")'>";
		for (var i=0; i<24; i++)
		{
			selected = "";
			if (i == hr)
				selected = "selected";
			selectHTML+= "<option " + selected + " value='"+ i +"'>"+ i +"</option>";
		}
		selectHTML += "</select>";
	}

	// Setup minutes
	if (mnPos != -1)
	{
		selectHTML += "&nbsp : &nbsp<select style='width:50px' onChange='dropdownDateSet(\"mn\", this.value, \"" + targetElementId + "\", \"" + templateStr + "\")'>";
		mnVal = 0;
		for (var i=0; i<4; i++)
		{
			selected = "";
			if (mn >= mnVal)
				selected = "selected";
			selectHTML+= "<option " + selected + " value='"+ mnVal +"'>"+ mnVal +"</option>";
			mnVal += 15;
		}
		selectHTML += "</select>";
	}

    document.getElementById(containerDivId).innerHTML = selectHTML;


	// Apply the defaults to the data

//alert('1: ' + templateStr + '=>' + document.getElementById(targetElementId).value);
	dropdownDateSet("dd", dd.toString(), targetElementId, templateStr);
	dropdownDateSet("mm", mm.toString(), targetElementId, templateStr);
	dropdownDateSet("yyyy", yyyy.toString(), targetElementId, templateStr);
	if (hr != -1)
		dropdownDateSet("hr", hr.toString(), targetElementId, templateStr);
	if (mn != -1)
		dropdownDateSet("mn", mn.toString(), targetElementId, templateStr);
//alert('2: ' + templateStr + '=>' + document.getElementById(targetElementId).value);
}

// Respond to onChange of any of the <select>s
function dropdownDateSet(type, value, targetElementId, templateStr)
{
	target = document.getElementById(targetElementId);

	if (target.value == "")
		target.value = templateStr;

	// Replace the date/time element
	searchPos = templateStr.indexOf(type);
	if ((type == "dd") || (type = "mm") || (type = "hr") || (type = "mn"))
	{
		newVal = value.pad(2);
		incr = 2;
	}
	else
	{
		newVal = value;
		incr = 4;
	}
	target = document.getElementById(targetElementId);
	target.value = target.value.replaceAt(searchPos, newVal);

/*
	// Literal characters
	while (target.value.length < templateStr.length)
		target.value = target.value + ' ';
	for (i=0; i<templateStr.length; i++)
	{
		if (templateStr.substr(i, 2) == type)
			i += incr;
		if (templateStr[i] != " ")
			target.value = target.value.replaceAt(i, templateStr[i]);
	}
*/
}

