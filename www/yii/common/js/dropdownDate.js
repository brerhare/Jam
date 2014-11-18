String.prototype.replaceAt=function(index, characters) {
	return this.substr(0, index) + characters + this.substr(index+characters.length);
}
String.prototype.pad=function(width, character) {
	character = character || '0';
	return this.length >= width ? this : new Array(width - this.length + 1).join(character) + this;
}


/*
 * Input date as dropdown select/options. "dd mth yyyy"
 *
 * @param containerDivId		div (or span) to inject select/option tags
 * @param targetElementId		element id to read/write
 * @param templateStr			date format template eg "yyyy/mm/dd" or "dd-mm-yyyy 00:00", where dd, mm and yyyy are used and all non-whitespace replaced
 */

function getDate(containerDivId, targetElementId, templateStr)
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

	// Set defaults to today
	var today=new Date();
	dd = today.getDate();
	mm = (today.getMonth() + 1);
	yyyy = today.getFullYear();

	// Override default with input value
	target = document.getElementById(targetElementId);
	ddChk = parseInt(target.value.slice(ddPos, (ddPos+2)));
	mmChk = parseInt(target.value.slice(mmPos, (mmPos+2)));
	yyyyChk = parseInt(target.value.slice(yyyyPos, (yyyyPos+4)));
	if ((ddChk >= 1) && (ddChk <= 31))
		dd = ddChk;
	if ((mmChk >= 1) && (mmChk <= 12))
		mm = mmChk;
	if ((yyyyChk >= 1900) && (yyyyChk <= 2100))
		yyyy = yyyyChk;

	// Setup day
	selectHTML="<select onChange='getDateSet(\"dd\", this.value, \"" + targetElementId + "\", \"" + templateStr + "\")'>";
	for (var i=0; i<31; i++)
	{
		selected = "";
		if ((i+1) == dd)
			selected = "selected";
		selectHTML+= "<option " + selected + " value='"+ (i+1) +"'>"+ (i+1) +"</option>";
	}
	selectHTML += "</select>";

	// Setup month
	selectHTML += "<select onChange='getDateSet(\"mm\", this.value, \"" + targetElementId + "\", \"" + templateStr + "\")'>";
	for (var i=0; i<12; i++)
	{
		selected = "";
		if (i == (mm-1))
			selected = "selected";
		selectHTML+= "<option " + selected + " value='"+ (i+1) +"'>"+ monthText[i] +"</option>";
	}
	selectHTML += "</select>";

	// Setup year
	selectHTML += "<select onChange='getDateSet(\"yyyy\", this.value, \"" + targetElementId + "\", \"" + templateStr + "\")'>";
	startYear = today.getFullYear();
	for (var i=startYear; i<(startYear+10); i++)
	{
		selected = "";
		if (i == yyyy)
			selected = "selected";
		selectHTML+= "<option " + selected + " value='"+ i +"'>"+ i +"</option>";
	}
	selectHTML += "</select>";

    document.getElementById(containerDivId).innerHTML = selectHTML;

	// Apply the defaults to the data
	getDateSet("dd", dd.toString(), targetElementId, templateStr);
	getDateSet("mm", mm.toString(), targetElementId, templateStr);
	getDateSet("yyyy", yyyy.toString(), targetElementId, templateStr);
}

// Respond to onChange of any of the <select>s
function getDateSet(type, value, targetElementId, templateStr)
{
	// Replace the date element
	searchPos = templateStr.indexOf(type);
	if ((type == "dd") || (type = "mm"))
		newVal = value.pad(2);
	else
		newVal = value;
	target = document.getElementById(targetElementId);
	target.value = target.value.replaceAt(searchPos, newVal);

	// Literal characters
	while (target.value.length < templateStr.length)
		target.value = target.value + ' ';
	for (i=0; i<templateStr.length; i++)
	{
		if ((templateStr.substr(i, 2) == "dd") || (templateStr.substr(i, 2) == "mm"))
			i += 2;
		else if (templateStr.substr(i, 4) == "yyyy")
			i += 4;
		if (templateStr[i] != " ")
			target.value = target.value.replaceAt(i, templateStr[i]);
	}
}

