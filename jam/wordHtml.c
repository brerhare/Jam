#include <stdio.h>
#include <strings.h>
#include <string.h>
#include <string>
#include <iostream>
#include <fstream>
#include <vector>
#include <cstdlib>
#include <time.h>

#include </usr/include/mysql/mysql.h>

#include "wordHtml.h"
#include "common.h"
#include "stringUtil.h"
#include "database.h"
#include "log.h"

// User function hooks for all input types
char *COMMON_FN = "onChange='fn(this, event);' onkeyup='fn(this, event);'";

#define MAX_BREAKPOINTS 10000	
// Breakpoint vars collected along the way that will now be used to generate stuff									// quite a few
int breakpointAutocompleteId[MAX_BREAKPOINTS];

char *makeJamKeyValue(char *tableName, char *fieldName, char *rawValue);

#define INP 0
#define INPUT 1
#define GRIDINP 2

//-----------------------------------------------------------------
// HTML <tag> generation from {{curly}}

int wordHtmlContainer(int ix, char *defaultTableName) {
	char *tmp = (char *) calloc(1, 64096);
	char *center = NULL;
	char *noMargin = NULL;
	char *css = NULL;

	// Start or End
	if (isVar("sys.control.end")) {
		JAMBUILDER jb;
		jb.stream = STREAMOUTPUT_STD;
		jb.templateStr = NULL;
		int ret = jamBuilder("/jam/run/sys/jamBuilder/html/container.jam", "containerEndHtml", &jb);
		emitStd(jam[ix]->trailer);
		return ret;
	} else if (!isVar("sys.control.start")) {
		logMsg(LOGERROR, "Html container must have 'start' or 'end'");
		return(-1);
	}

	// Center
	if ((isVar("sys.control.center")) || (isVar("sys.control.centre")))
		center = strdup("uk-container-center");
	else
		center = strdup("");

	// NoMargin
	if ((isVar("sys.control.nomargin")) || (isVar("sys.control.nomargin")))
		noMargin = strdup("uk-margin-remove");
	else
		noMargin = strdup("");

	// Css
	if (isVar("sys.control.css"))
		css = strdup(getVarAsString("sys.control.css"));
	else
		css = strdup("");

	JAMBUILDER jb;
	jb.stream = STREAMOUTPUT_STD;
	char *templateStr = (char *) calloc(1, 64096);
	sprintf(templateStr, "{{@template CONTAINER_CENTER %s}}	\
						  {{@template CONTAINER_MARGIN %s}} \
						  {{@template CONTAINER_CSS %s}}",
							center,
							noMargin,
							css
							);

	jb.templateStr = templateStr;
	jamBuilder("/jam/run/sys/jamBuilder/html/container.jam", "containerStartHtml", &jb);

	free(tmp);
	free(center);
	free(noMargin);
	free(css);
	emitStd(jam[ix]->trailer);
}

int wordHtmlForm(int ix, char *defaultTableName) {
	char *tmp = (char *) calloc(1, 64096);
	char *name = NULL;
	char *css = NULL;

	// Start or End
	if (isVar("sys.control.end")) {
		JAMBUILDER jb;
		jb.stream = STREAMOUTPUT_STD;
		jb.templateStr = NULL;
		int ret = jamBuilder("/jam/run/sys/jamBuilder/html/form.jam", "formEndHtml", &jb);
		emitStd(jam[ix]->trailer);
		return ret;
	} else if (!isVar("sys.control.start")) {
		logMsg(LOGERROR, "Html form must have 'start' or 'end'");
		return(-1);
	}


	// Name
	if (isVar("sys.control.name"))
		name = strdup("sys.control.name");
	else {
		logMsg(LOGERROR, "Html form must have 'name'");
		return(-1);
	}

	// Css
	if (isVar("sys.control.css"))
		css = strdup(getVarAsString("sys.control.css"));
	else
		css = strdup("");

	JAMBUILDER jb;
	jb.stream = STREAMOUTPUT_STD;
	char *templateStr = (char *) calloc(1, 64096);
	sprintf(templateStr, "{{@template FORM_NAME %s}} \
						  {{@template FORM_CSS %s}}",
							name,
							css
							);

	jb.templateStr = templateStr;
	jamBuilder("/jam/run/sys/jamBuilder/html/form.jam", "formStartHtml", &jb);

	free(tmp);
	free(name);
	free(css);
	emitStd(jam[ix]->trailer);
}

int wordHtmlGridrow(int ix, char *defaultTableName) {
	char *tmp = (char *) calloc(1, 64096);
	char *gridCols = NULL;
	char *css = NULL;

	// Start or End
	if (isVar("sys.control.end")) {
		JAMBUILDER jb;
		jb.stream = STREAMOUTPUT_STD;
		jb.templateStr = NULL;
		int ret = jamBuilder("/jam/run/sys/jamBuilder/html/gridrow.jam", "gridrowEndHtml", &jb);
		emitStd(jam[ix]->trailer);
		return ret;
	} else if (!isVar("sys.control.start")) {
		logMsg(LOGERROR, "Html gridrow must have 'start' or 'end'");
		return(-1);
	}

	// Gridcols
	if (isVar("sys.control.gridcols")) {
		gridCols = (char *) calloc(1, 64096);
		sprintf(gridCols, "uk-grid-width-1-%d", atoi(getVarAsString("sys.control.gridcols")));
	}
	else
		gridCols = strdup("");

	// Css
	if (isVar("sys.control.css"))
		css = strdup(getVarAsString("sys.control.css"));
	else
		css = strdup("");

	JAMBUILDER jb;
	jb.stream = STREAMOUTPUT_STD;
	char *templateStr = (char *) calloc(1, 64096);
	sprintf(templateStr, "{{@template GRIDROW_GRIDCOLS %s}}	\
						  {{@template GRIDROW_CSS %s}}",
							gridCols,
							css
							);

	jb.templateStr = templateStr;
	jamBuilder("/jam/run/sys/jamBuilder/html/gridrow.jam", "gridrowStartHtml", &jb);

	free(tmp);
	free(gridCols);
	free(css);
	emitStd(jam[ix]->trailer);
}

int wordHtmlGridcol(int ix, char *defaultTableName) {
	char *tmp = (char *) calloc(1, 64096);
	char *width = NULL;
	char *css = NULL;

	// Start or End
	if (isVar("sys.control.end")) {
		JAMBUILDER jb;
		jb.stream = STREAMOUTPUT_STD;
		jb.templateStr = NULL;
		int ret = jamBuilder("/jam/run/sys/jamBuilder/html/gridcol.jam", "gridcolEndHtml", &jb);
		emitStd(jam[ix]->trailer);
		return ret;
	} else if (!isVar("sys.control.start")) {
		logMsg(LOGERROR, "Html gridcol must have 'start' or 'end'");
		return(-1);
	}

	// Width
	if (isVar("sys.control.width")) {
		width = (char *) calloc(1, 64096);
		sprintf(width, "uk-width-%s", getVarAsString("sys.control.width"));
	}
	else
		width = strdup("");

	// Css
	if (isVar("sys.control.css"))
		css = strdup(getVarAsString("sys.control.css"));
	else
		css = strdup("");

	JAMBUILDER jb;
	jb.stream = STREAMOUTPUT_STD;
	char *templateStr = (char *) calloc(1, 64096);
	sprintf(templateStr, "{{@template GRIDCOL_WIDTH %s}}	\
						  {{@template GRIDCOL_CSS %s}}",
							width,
							css
							);

	jb.templateStr = templateStr;
	jamBuilder("/jam/run/sys/jamBuilder/html/gridcol.jam", "gridcolStartHtml", &jb);

	free(tmp);
	free(width);
	free(css);
	emitStd(jam[ix]->trailer);
}

int wordHtmlDropdown(int ix, char *defaultTableName) {
	char *tmp = (char *) calloc(1, 64096);
	char *targetTable = NULL;
	char *targetField = NULL;
	char *targetRawValue = NULL;
	char *pickTable = NULL;
	char *pickField = NULL;
	char *size = NULL;
	char *disabled = NULL;
	char *label = NULL;
	char *group = (char *) calloc(1, 64096);
	char *jamKey = NULL;

	// [Table].field
	char *p = getVarAsString("sys.control.field");
	if (!p) {
		logMsg(LOGERROR, "Html input target cant be null");
		return(-1);
	}
	targetRawValue = strdup(getVarAsString("sys.control.field"));
	if (strchr(p, '.')) {		// has a named table
		targetTable = getWordAlloc(getVarAsString("sys.control.field"), 1, ".");
		targetField = getWordAlloc(getVarAsString("sys.control.field"), 2, ".");
	} else {					// its just the field name
		if (defaultTableName)
			targetTable = strdup(defaultTableName);
		else
			targetTable = strdup("");
		targetField = getWordAlloc(getVarAsString("sys.control.field"), 1, ".");
	}

	//  Pick [table].field
	p = getVarAsString("sys.control.pickfield");
	if ((p) && (strchr(p, '.'))) {		// has a named table
		pickTable = getWordAlloc(getVarAsString("sys.control.pickfield"), 1, ".");
		pickField = getWordAlloc(getVarAsString("sys.control.pickfield"), 2, ".");
	} else {					// its just the field name
		if (defaultTableName)
			pickTable = strdup(defaultTableName);
		else
			pickTable = strdup("");
		pickField = getWordAlloc(getVarAsString("sys.control.pickfield"), 1, ".");
	}

	// Label
	if (isVar("sys.control.label"))
		label = strdup(getVarAsString("sys.control.label"));
	else
		label = strdup("");

	// Size
	if (isVar("sys.control.size"))
		size = strdup(getVarAsString("sys.control.size"));
	else
		size = strdup("");

	// Group(s)
	sprintf(group, "ROW_%d ", cmdSeqnum);
	if (isVar("sys.control.group"))
		strcat(group,  getVarAsString("sys.control.group"));

	// Disabled
	if (isVar("sys.control.disabled"))
		disabled = strdup(" disabled ");
	else
		disabled = strdup("");

	// Set jamKey. This is whatever table/field values are required to update the data
	jamKey = makeJamKeyValue(targetTable, targetField, targetRawValue);

	JAMBUILDER jb;
	jb.stream = STREAMOUTPUT_STD;
	char *templateStr = (char *) calloc(1, 64096);
	sprintf(templateStr, "{{@template DROPDOWN_TARGET_TABLE %s}}	\
						  {{@template DROPDOWN_TARGET_FIELD %s}}	\
						  {{@template DROPDOWN_PICK_TABLE %s}}	\
						  {{@template DROPDOWN_PICK_FIELD %s}}	\
						  {{@template DROPDOWN_LABEL %s}}	\
						  {{@template DROPDOWN_SIZE %s}}	\
						  {{@template DROPDOWN_DISABLED %s}}	\
						  {{@template DROPDOWN_GROUP %s}}	\
						  {{@template COMMON_FN %s}}	\
						  {{@template DROPDOWN_JAMKEY %s}}",
							targetTable,
							targetField,
							pickTable,
							pickField,
							label,
							size,
							disabled,
							group,
							COMMON_FN,
							jamKey
							);

	jb.templateStr = templateStr;
	if (isVar("sys.control.label"))
		jamBuilder("/jam/run/sys/jamBuilder/html/dropdown.jam", "dropdownLabelHtml", &jb);
	else
		jamBuilder("/jam/run/sys/jamBuilder/html/dropdown.jam", "dropdownHtml", &jb);

	free(tmp);
	free(targetTable);
	free(targetField);
	free(targetRawValue);
	free(pickTable);
	free(pickField);
	free(size);
	free(disabled);
	free(group);
	if (label) free(label);
	free(jamKey);
	free(templateStr);
	emitStd(jam[ix]->trailer);
}

int wordHtmlFilter(int ix, char *defaultTableName) {
	char *tmp = (char *) calloc(1, 64096);
	char *targetTable = NULL;
	char *targetField = NULL;
	char *targetRawValue = NULL;
	char *pickTable = NULL;
	char *pickField = NULL;
	char *size = NULL;
	char *disabled = NULL;
	char *label = NULL;
	char *group = (char *) calloc(1, 64096);
	char *jamKey = NULL;

	// [Table].field
	char *p = getVarAsString("sys.control.field");
	if (!p) {
		logMsg(LOGERROR, "Html input target cant be null");
		return(-1);
	}
	targetRawValue = strdup(getVarAsString("sys.control.field"));
	if (strchr(p, '.')) {		// has a named table
		targetTable = getWordAlloc(getVarAsString("sys.control.field"), 1, ".");
		targetField = getWordAlloc(getVarAsString("sys.control.field"), 2, ".");
	} else {					// its just the field name
		if (defaultTableName)
			targetTable = strdup(defaultTableName);
		else
			targetTable = strdup("");
		targetField = getWordAlloc(getVarAsString("sys.control.field"), 1, ".");
	}

	//  Pick [table].field
	p = getVarAsString("sys.control.pickfield");
	if ((p) && (strchr(p, '.'))) {		// has a named table
		pickTable = getWordAlloc(getVarAsString("sys.control.pickfield"), 1, ".");
		pickField = getWordAlloc(getVarAsString("sys.control.pickfield"), 2, ".");
	} else {					// its just the field name
		if (defaultTableName)
			pickTable = strdup(defaultTableName);
		else
			pickTable = strdup("");
		pickField = getWordAlloc(getVarAsString("sys.control.pickfield"), 1, ".");
	}

	// Label
	if (isVar("sys.control.label"))
		label = strdup(getVarAsString("sys.control.label"));
	else
		label = strdup("");

	// Size
	if (isVar("sys.control.size"))
		size = strdup(getVarAsString("sys.control.size"));
	else
		size = strdup("");

	// Group(s)
	sprintf(group, "ROW_%d ", cmdSeqnum);
	if (isVar("sys.control.group"))
		strcat(group,  getVarAsString("sys.control.group"));

	// Disabled
	if (isVar("sys.control.disabled"))
		disabled = strdup(" disabled ");
	else
		disabled = strdup("");

	// Set jamKey. This is whatever table/field values are required to update the data
	jamKey = makeJamKeyValue(targetTable, targetField, targetRawValue);

	JAMBUILDER jb;
	jb.stream = STREAMOUTPUT_STD;
	char *templateStr = (char *) calloc(1, 64096);
	sprintf(templateStr, "{{@template FILTER_TARGET_TABLE %s}}	\
						  {{@template FILTER_TARGET_FIELD %s}}	\
						  {{@template FILTER_PICK_TABLE %s}}	\
						  {{@template FILTER_PICK_FIELD %s}}	\
						  {{@template FILTER_LABEL %s}}	\
						  {{@template FILTER_SIZE %s}}	\
						  {{@template FILTER_DISABLED %s}}	\
						  {{@template FILTER_GROUP %s}}	\
						  {{@template COMMON_FN %s}}	\
						  {{@template FILTER_JAMKEY %s}}",
							targetTable,
							targetField,
							pickTable,
							pickField,
							label,
							size,
							disabled,
							group,
							COMMON_FN,
							jamKey
							);

	jb.templateStr = templateStr;
	if (isVar("sys.control.label"))
		jamBuilder("/jam/run/sys/jamBuilder/html/filter.jam", "filterLabelHtml", &jb);
	else
		jamBuilder("/jam/run/sys/jamBuilder/html/filter.jam", "filterHtml", &jb);

	jb.stream = STREAMOUTPUT_JS;
	jb.templateStr = templateStr;
	jamBuilder("/jam/run/sys/jamBuilder/html/filter.jam", "filterJs", &jb);

	free(tmp);
	free(targetTable);
	free(targetField);
	free(targetRawValue);
	free(pickTable);
	free(pickField);
	free(size);
	free(disabled);
	free(group);
	free(label);
	free(jamKey);
	free(templateStr);
	emitStd(jam[ix]->trailer);
}

// Input 'text' or 'date' or 'time'
int wordHtmlInput(int ix, char *defaultTableName) {
	char *tmp = (char *) calloc(1, 64096);
	char *type = NULL;
	char *table = NULL;
	char *field = NULL;
	char *tableFieldRawValue = NULL;
	char *label = NULL;
	char *placeholder = NULL;
	char *tip = NULL;
	char *defaultValue = NULL;
	char *size = NULL;
	char *disabled = NULL;
	char *hidden = NULL;
	char *value = NULL;
	char *value2 = NULL;
	char *group = (char *) calloc(1, 64096);
	char *jamKey = NULL;

	// Hidden
	if (isVar("sys.control.hidden"))
		type = strdup("hidden");
	else {
		// Type
		type = getWordAlloc(jam[ix]->args, 1, " \t\n");
		if (type == NULL ) {
			logMsg(LOGERROR, "Html input type cant be null");
			return(-1);	
		}
		if ((strcmp(type, "date")) && (strcmp(type, "time")) && (strcmp(type, "password")) && (strcmp(type, "text"))) {
			logMsg(LOGERROR, "Html input type unspecified. Use 'text' or 'date' etc");
			free(type);
			return(-1);
		}
	}

	// [Table].field
	char *p = getVarAsString("sys.control.field");
	if (!p) {
		logMsg(LOGERROR, "Html input field cant be null");
		return(-1);
	}
	tableFieldRawValue = strdup(getVarAsString("sys.control.field"));
	if (strchr(p, '.')) {		// has a named table
		table = getWordAlloc(getVarAsString("sys.control.field"), 1, ".");
		field = getWordAlloc(getVarAsString("sys.control.field"), 2, ".");
	} else {					// its just the field name
		if (defaultTableName)
			table = strdup(defaultTableName);
		else
			table = strdup("");
		field = getWordAlloc(getVarAsString("sys.control.field"), 1, ".");
	}

	// Label
	if (isVar("sys.control.label"))
		label = strdup(getVarAsString("sys.control.label"));
	else
		label = strdup("");

	// Placeholder
	if (isVar("sys.control.placeholder"))
		placeholder = strdup(getVarAsString("sys.control.placeholder"));
	else
		placeholder = strdup("");

	// Hover
	if (isVar("sys.control.tip")) {
		char *hoverTip = strdup(getVarAsString("sys.control.tip"));
		sprintf(tmp, "data-uk-tooltip title='%s'", hoverTip);
		tip = strdup(tmp);
		if (hoverTip)
			free(hoverTip);
	} else
		tip = strdup("");

	// Size
	if (isVar("sys.control.size"))
		size = strdup(getVarAsString("sys.control.size"));
	else
		size = strdup("");

	// Group(s)
	sprintf(group, "ROW_%d ", cmdSeqnum);
	if (isVar("sys.control.group"))
		strcat(group,  getVarAsString("sys.control.group"));

	// Disabled
	if (isVar("sys.control.disabled"))
		disabled = strdup(" disabled ");
	else
		disabled = strdup("");

	// Set jamKey. This is whatever table/field values are required to update the data
	jamKey = makeJamKeyValue(table, field, tableFieldRawValue);

	// Default value (id = -1)
	if (isVar("sys.control.default")) {
		logMsg(LOGDEBUG, "Found a default value");
		VAR *variableDefault = findVarStrict("sys.control.default");
		if (variableDefault) {
			sprintf(tmp, "%s.%s", table, "id");
			VAR *variableId = findVarStrict(tmp);
			if ((variableId) && (!strcmp(variableId->portableValue, "-1"))) {
				defaultValue = strdup(escapeJsonChars(variableDefault->portableValue));
			}
		}
	}

		logMsg(LOGDEBUG, "Foundi a default value");
	// Value
	sprintf(tmp, "%s.%s", table, field);
	VAR *variable = findVarStrict(tmp);
	if (variable) {
		if (!defaultValue) {
logMsg(LOGDEBUG, "3");
			value = strdup(escapeJsonChars(variable->portableValue));
			value2 = strdup(escapeSingleQuote(value));
		} else {
logMsg(LOGDEBUG, "4");
			value = strdup(escapeJsonChars(defaultValue));
			value2 = strdup(escapeJsonChars(defaultValue));
		}
	} else {
		if (!strcmp(type, "date")) {
			// Default to today
			value = (char *) calloc(1, 64096);
			value2 = strdup("");
			time_t now = time(NULL);
			strftime(value, 20, "%Y-%m-%d", localtime(&now));
		}
		else {
			value = strdup("");
			value2 = strdup("");
		}
	}

logMsg(LOGDEBUG, "5");
	JAMBUILDER jb;
	jb.stream = STREAMOUTPUT_STD;
	char *templateStr = (char *) calloc(1, 64096);
	sprintf(templateStr, "{{@template INPUT_TYPE %s}}	\
						  {{@template INPUT_TABLE %s}}	\
						  {{@template INPUT_FIELD %s}}	\
						  {{@template INPUT_LABEL %s}}	\
						  {{@template INPUT_PLACEHOLDER %s}}	\
						  {{@template INPUT_HOVER %s}}	\
						  {{@template INPUT_SIZE %s}}	\
						  {{@template INPUT_VALUE %s}}	\
						  {{@template INPUT_DISABLED %s}}	\
						  {{@template INPUT_GROUP %s}}	\
						  {{@template COMMON_FN %s}}	\
						  {{@template INPUT_JAMKEY %s}}",
							type,
							table,
							field,
							label,
							placeholder,
							tip,
							size,
							value2,
							disabled,
							group,
							COMMON_FN,
							jamKey
							);

	jb.templateStr = templateStr;
	if (isVar("sys.control.label"))
		jamBuilder("/jam/run/sys/jamBuilder/html/input.jam", "inputLabelHtml", &jb);
	else
		jamBuilder("/jam/run/sys/jamBuilder/html/input.jam", "inputHtml", &jb);

	if (defaultValue)
		free(defaultValue);
	free(tmp);
	free(type);
	free(table);
	free(field);
	free(tableFieldRawValue);
	free(label);
	free(placeholder);
	free(tip);
	free(size);
	free(disabled);
	free(group);
	free(value);
	free(value2);
	free(jamKey);
	free(templateStr);
	emitStd(jam[ix]->trailer);
}

int wordHtmlCheckbox(int ix, char *defaultTableName) {
	char *tmp = (char *) calloc(1, 64096);
	char *table = NULL;
	char *field = NULL;
	char *tableFieldRawValue = NULL;
	char *label = NULL;
	char *placeholder = NULL;
	char *disabled = NULL;
	char *value = NULL;
	char *unvalue = NULL;
	char *checked = NULL;
	char *tip = NULL;
	char *group = (char *) calloc(1, 64096);
	char *jamKey = NULL;

	// [Table].field
	char *p = getVarAsString("sys.control.field");
	if (!p) {
		logMsg(LOGERROR, "Html checkbox field cant be null");
		return(-1);
	}
	tableFieldRawValue = strdup(getVarAsString("sys.control.field"));
	if (strchr(p, '.')) {		// has a named table
		table = getWordAlloc(getVarAsString("sys.control.field"), 1, ".");
		field = getWordAlloc(getVarAsString("sys.control.field"), 2, ".");
	} else {					// its just the field name
		if (defaultTableName)
			table = strdup(defaultTableName);
		else
			table = strdup("");
		field = getWordAlloc(getVarAsString("sys.control.field"), 1, ".");
	}

	// Label
	if (isVar("sys.control.label"))
		label = strdup(getVarAsString("sys.control.label"));
	else
		label = strdup("");

	// Placeholder
	if (isVar("sys.control.placeholder"))
		placeholder = strdup(getVarAsString("sys.control.placeholder"));
	else
		placeholder = strdup("");

	// Group(s)
	sprintf(group, "ROW_%d ", cmdSeqnum);
	if (isVar("sys.control.group"))
		strcat(group,  getVarAsString("sys.control.group"));

	// Disabled
	if (isVar("sys.control.disabled"))
		disabled = strdup(" disabled ");
	else
		disabled = strdup("");

	// Set jamKey. This is whatever table/field values are required to update the data
	jamKey = makeJamKeyValue(table, field, tableFieldRawValue);

	// Value
	sprintf(tmp, "%s.%s", table, field);
	VAR *variable = findVarStrict(tmp);
	if ((variable) && (atoi(variable->portableValue)) > 0) {
		value = strdup(variable->portableValue);
		sprintf(tmp, "<input type='hidden' name='%s.%s' value='0'>", table, field);
		unvalue = strdup(tmp);
	} else {
		value = strdup("1");
		unvalue = strdup("");
	}

	// Checked
	if (atoi(variable->portableValue) > 0)
		checked = strdup("checked");
	else
		checked = strdup("");

	// Hover
	if (isVar("sys.control.tip")) {
		char *hoverTip = strdup(getVarAsString("sys.control.tip"));
		sprintf(tmp, "data-uk-tooltip title='%s'", hoverTip);
		tip = strdup(tmp);
		if (hoverTip)
			free(hoverTip);
	} else
		tip = strdup("");

	JAMBUILDER jb;
	jb.stream = STREAMOUTPUT_STD;
	char *templateStr = (char *) calloc(1, 64096);
	sprintf(templateStr, "{{@template CHECKBOX_TABLE %s}}	\
						  {{@template CHECKBOX_FIELD %s}}	\
						  {{@template CHECKBOX_LABEL %s}}	\
						  {{@template CHECKBOX_PLACEHOLDER %s}}	\
						  {{@template CHECKBOX_VALUE %s}}	\
						  {{@template CHECKBOX_UNVALUE %s}}	\
						  {{@template CHECKBOX_CHECKED %s}}	\
						  {{@template CHECKBOX_HOVER %s}}	\
						  {{@template CHECKBOX_DISABLED %s}}	\
						  {{@template CHECKBOX_GROUP %s}}	\
						  {{@template COMMON_FN %s}}	\
						  {{@template CHECKBOX_JAMKEY %s}}",
							table,
							field,
							label,
							placeholder,
							value,
							unvalue,
							checked,
							tip,
							disabled,
							group,
							COMMON_FN,
							jamKey
							);

	jb.templateStr = templateStr;
	if (isVar("sys.control.label"))
		jamBuilder("/jam/run/sys/jamBuilder/html/checkbox.jam", "checkboxLabelHtml", &jb);
	else
		jamBuilder("/jam/run/sys/jamBuilder/html/checkbox.jam", "checkboxHtml", &jb);

	free(tmp);
	free(table);
	free(field);
	free(tableFieldRawValue);
	free(label);
	free(placeholder);
	free(disabled);
	free(group);
	free(value);
	free(unvalue);
	free(checked);
	free(tip);
	free(jamKey);
	free(templateStr);
	emitStd(jam[ix]->trailer);
}

int wordHtmlRadio(int ix, char *defaultTableName) {
	char *tmp = (char *) calloc(1, 64096);
	char *table = NULL;
	char *field = NULL;
	char *tableFieldRawValue = NULL;
	char *label = NULL;
	char *disabled = NULL;
	char *tip = NULL;
	char *value = NULL;
	char *options = NULL;
	char *group = (char *) calloc(1, 64096);
	char *optionStr = (char *) calloc(1, 64096);
	char *jamKey = NULL;

	// [Table].field
	char *p = getVarAsString("sys.control.field");
	if (!p) {
		logMsg(LOGERROR, "Html radio field cant be null");
		return(-1);
	}
	tableFieldRawValue = strdup(getVarAsString("sys.control.field"));
	if (strchr(p, '.')) {		// has a named table
		table = getWordAlloc(getVarAsString("sys.control.field"), 1, ".");
		field = getWordAlloc(getVarAsString("sys.control.field"), 2, ".");
	} else {					// its just the field name
		if (defaultTableName)
			table = strdup(defaultTableName);
		else
			table = strdup("");
		field = getWordAlloc(getVarAsString("sys.control.field"), 1, ".");
	}

	// Label
	if (isVar("sys.control.label"))
		label = strdup(getVarAsString("sys.control.label"));
	else
		label = strdup("");

	// Group(s)
	sprintf(group, "ROW_%d ", cmdSeqnum);
	if (isVar("sys.control.group"))
		strcat(group,  getVarAsString("sys.control.group"));

	// Disabled
	if (isVar("sys.control.disabled"))
		disabled = strdup("disabled");
	else
		disabled = strdup("");

	// Set jamKey. This is whatever table/field values are required to update the data
	jamKey = makeJamKeyValue(table, field, tableFieldRawValue);

	// Value
	sprintf(tmp, "%s.%s", table, field);
	VAR *variable = findVarStrict(tmp);
	if (variable)
		value = strdup(variable->portableValue);
	else
		value = strdup("");

	// Options. Eg options=0:Male,1:Female
	if (isVar("sys.control.options"))
		options = strdup(getVarAsString("sys.control.options"));
	else
		options = strdup("");

	// Hover
	if (isVar("sys.control.tip")) {
		char *hoverTip = strdup(getVarAsString("sys.control.tip"));
		sprintf(tmp, "data-uk-tooltip title='%s'", hoverTip);
		tip = strdup(tmp);
		if (hoverTip)
			free(hoverTip);
	} else
		tip = strdup("");

/*
{{@Xhtml radio field=young_person.gender label='Gender' options=0:Male,1:Female}}

<label class='uk-form-label' for='RADIO_JAMKEY'> RADIO_LABEL </label>
<div class='uk-form-controls uk-form-controls-text'>
    <label><input type="radio" id="RADIO_JAMKEY" name="radio">1</label>
    <label><input type="radio" name="radio">2</label>
    <label><input type="radio" name="radio">3</label>
</div>
*/
	int cnt = 1;
	char *firstId = (char *) calloc(1, 64096);
	sprintf(firstId, "id='%s'", jamKey);
	while (1) {
		char *opt = getWordAlloc(options, cnt++, ",");
		if ((!opt) || (!strlen(opt)))
			break;
		char *optA = getWordAlloc(opt, 1, ":");
		char *optB = getWordAlloc(opt, 2, ":");
		if ( (!optA) || (!strlen(optA)) )
			continue;
		if ( (!optB) || (!strlen(optB)) ) {
			free(opt);
			free(optA);
			continue;
		}
		char *checked;
		if (!strcmp(variable->portableValue, optA))
			checked = strdup("checked");
		else
			checked = strdup("");
		sprintf(tmp, "<label><input type='radio' %s name='%s.%s' value='%s' class='%s' onClick='fn(this, event)' %s %s>%s</label> \n", firstId, table, field, optA, group, checked, disabled, optB);
		*firstId = '\0';
		strcat(optionStr, tmp);
		free(checked);
		free(optA);
		free(optB);
	}
	free(firstId);

	JAMBUILDER jb;
	jb.stream = STREAMOUTPUT_STD;
	char *templateStr = (char *) calloc(1, 64096);
	sprintf(templateStr, "{{@template RADIO_TABLE %s}}	\
						  {{@template RADIO_FIELD %s}}	\
						  {{@template RADIO_LABEL %s}}	\
						  {{@template RADIO_OPTIONS %s}}	\
						  {{@template RADIO_VALUE %s}}	\
						  {{@template RADIO_DISABLED %s}}	\
						  {{@template RADIO_HOVER %s}}	\
						  {{@template RADIO_GROUP %s}}	\
						  {{@template COMMON_FN %s}}	\
						  {{@template RADIO_JAMKEY %s}}",
							table,
							field,
							label,
							optionStr,
							value,
							disabled,
							tip,
							group,
							COMMON_FN,
							jamKey
							);

	jb.templateStr = templateStr;
	if (isVar("sys.control.label"))
		jamBuilder("/jam/run/sys/jamBuilder/html/radio.jam", "radioLabelHtml", &jb);
	else
		jamBuilder("/jam/run/sys/jamBuilder/html/radio.jam", "radioHtml", &jb);

	free(tmp);
	free(table);
	free(field);
	free(tableFieldRawValue);
	free(label);
	free(disabled);
	free(tip);
	free(group);
	free(value);
	free(jamKey);
	free(templateStr);
	free(options);
	free(optionStr);
	emitStd(jam[ix]->trailer);
}

// Input Textarea
int wordHtmlTextarea(int ix, char *defaultTableName) {
	char *tmp = (char *) calloc(1, 64096);
	char *hidden = NULL;
	char *table = NULL;
	char *field = NULL;
	char *tableFieldRawValue = NULL;
	char *label = NULL;
	char *placeholder = NULL;
	char *tip = NULL;
	char *size = NULL;
	char *cols = NULL;
	char *rows = NULL;
	char *disabled = NULL;
	char *value = NULL;
	char *value2 = NULL;
	char *group = (char *) calloc(1, 64096);
	char *jamKey = NULL;

	// Hidden
	if (isVar("sys.control.hidden"))
		hidden = strdup("type='hidden'");
	else
		hidden = strdup("");

	// [Table].field
	char *p = getVarAsString("sys.control.field");
	if (!p) {
		logMsg(LOGERROR, "Html textarea field cant be null");
		return(-1);
	}
	tableFieldRawValue = strdup(getVarAsString("sys.control.field"));
	if (strchr(p, '.')) {		// has a named table
		table = getWordAlloc(getVarAsString("sys.control.field"), 1, ".");
		field = getWordAlloc(getVarAsString("sys.control.field"), 2, ".");
	} else {					// its just the field name
		if (defaultTableName)
			table = strdup(defaultTableName);
		else
			table = strdup("");
		field = getWordAlloc(getVarAsString("sys.control.field"), 1, ".");
	}

	// Label
	if (isVar("sys.control.label"))
		label = strdup(getVarAsString("sys.control.label"));
	else
		label = strdup("");

	// Placeholder
	if (isVar("sys.control.placeholder"))
		placeholder = strdup(getVarAsString("sys.control.placeholder"));
	else
		placeholder = strdup("");

	// Hover
	if (isVar("sys.control.tip")) {
		char *hoverTip = strdup(getVarAsString("sys.control.tip"));
		sprintf(tmp, "data-uk-tooltip title='%s'", hoverTip);
		tip = strdup(tmp);
		if (hoverTip)
			free(hoverTip);
	} else
		tip = strdup("");

	// Size
	if (isVar("sys.control.size")) {
		size = strdup(getVarAsString("sys.control.size"));
		if (!strchr(size, 'x')) {
			logMsg(LOGERROR, "Html textarea size must be in cols x rows format eg '60x5'");
			return(-1);
		}
		cols = getWordAlloc(size, 1, "x");	// cols
		rows = getWordAlloc(size, 2, "x");	// rows
	} else {
		size = strdup("");
		rows = strdup("");
		cols = strdup("");
	}

	// Group(s)
	sprintf(group, "ROW_%d ", cmdSeqnum);
	if (isVar("sys.control.group"))
		strcat(group,  getVarAsString("sys.control.group"));

	// Disabled
	if (isVar("sys.control.disabled"))
		disabled = strdup(" disabled ");
	else
		disabled = strdup("");

	// Set jamKey. This is whatever table/field values are required to update the data
	jamKey = makeJamKeyValue(table, field, tableFieldRawValue);

	// Value
	sprintf(tmp, "%s.%s", table, field);
	VAR *variable = findVarStrict(tmp);
	if (variable) {
		value = strdup(variable->portableValue);
		value2 = strdup(escapeSingleQuote(value));
	} else {
		value = strdup("");
		value2 = strdup("");
	}

	JAMBUILDER jb;
	jb.stream = STREAMOUTPUT_STD;
	char *templateStr = (char *) calloc(1, 64096);
	sprintf(templateStr, "{{@template TEXTAREA_HIDDEN %s}}	\
						  {{@template TEXTAREA_TABLE %s}}	\
						  {{@template TEXTAREA_FIELD %s}}	\
						  {{@template TEXTAREA_LABEL %s}}	\
						  {{@template TEXTAREA_PLACEHOLDER %s}}	\
						  {{@template TEXTAREA_HOVER %s}}	\
						  {{@template TEXTAREA_COLS %s}}	\
						  {{@template TEXTAREA_ROWS %s}}	\
						  {{@template TEXTAREA_VALUE %s}}	\
						  {{@template TEXTAREA_DISABLED %s}}	\
						  {{@template TEXTAREA_GROUP %s}}	\
						  {{@template COMMON_FN %s}}	\
						  {{@template TEXTAREA_JAMKEY %s}}",
							hidden,
							table,
							field,
							label,
							placeholder,
							tip,
							cols,
							rows,
							value2,
							disabled,
							group,
							COMMON_FN,
							jamKey
							);

	jb.templateStr = templateStr;
	if (isVar("sys.control.label"))
		jamBuilder("/jam/run/sys/jamBuilder/html/textarea.jam", "textareaLabelHtml", &jb);
	else
		jamBuilder("/jam/run/sys/jamBuilder/html/textarea.jam", "textareaHtml", &jb);

	free(tmp);
	free(hidden);
	free(table);
	free(field);
	free(tableFieldRawValue);
	free(label);
	free(placeholder);
	free(tip);
	free(size);
	free(rows);
	free(cols);
	free(disabled);
	free(group);
	free(value);
	free(value2);
	free(jamKey);
	free(templateStr);
	emitStd(jam[ix]->trailer);
}

// Tab
int wordHtmlTabs(int ix, char *defaultTableName) {
	char *tmp = (char *) calloc(1, 64096);
	char *args = jam[ix]->args;

	int seq = (rand() % 99999);
	char *tabStr = (char *) calloc(1, 64096);
	char *actionStr = (char *) calloc(1, 64096);

	logMsg(LOGDEBUG, "html tabs ARGS=%s", args);

	int cnt = 2;
	while (char *block = strTrim(getWordAlloc(args, cnt++, "\n"))) {
		char *tabNVP = strTrim(getWordAlloc(block, 1, " \t"));
		char *actionNVP = strTrim(getWordAlloc(block, 2, " \t"));
		if ((!tabNVP) || (!actionNVP))
			break;
		char *tab = strTrim(getWordAlloc(tabNVP, 2, "="));
		char *action = strchr(actionNVP, '=');
		if (!action) {
			logMsg(LOGERROR, "Missing '=' in html tabs action");
			return(-1);
		}
		action++; // Point to  whatever the action is. Equals signs in this arent our business
		sprintf(tmp, "<li><a href='#tab-%d'>%s</a></li> \n", seq, tab);
		strcat(tabStr, tmp);
		sprintf(tmp, "<iframe id='tab-%d' src='%s'></iframe> \n", seq, action);
		strcat(actionStr, tmp);
		free(block);
		free(tabNVP);
		free(actionNVP);
		free(tab);
//		free(action);
		seq++;
	}

	JAMBUILDER jb;
	jb.stream = STREAMOUTPUT_STD;
	char *templateStr = (char *) calloc(1, 64096);
	sprintf(templateStr, "{{@template TAB_STR %s}}	\
						  {{@template TAB_ACTION %s}}",
							tabStr,
							actionStr
							);

	jb.templateStr = templateStr;
	jamBuilder("/jam/run/sys/jamBuilder/html/tabs.jam", "tabsHtml", &jb);

	free(tmp);
	free(tabStr);
	free(actionStr);
	emitStd(jam[ix]->trailer);
}

int _wordHtmlInputInp(int ix, char *defaultTableName, int inputType) {
	char *cmd = jam[ix]->command;
	char *args = jam[ix]->args;
	char *rawData = jam[ix]->rawData;
	char *fieldName = (char *) calloc(1, 64096);
	char *fieldType = (char *) calloc(1, 64096);
	char *fieldVar = (char *) calloc(1, 64096);
	char *fieldSize = (char *) calloc(1, 64096);			// NB this is 'jam:action' for keyaction
	char *fieldVarValue = (char *) calloc(1, 64096);
	char *fieldSearchValue = (char *) calloc(1, 64096);
	char *fieldPrompt = (char *) calloc(1, 64096);		// NB this is 'size' for keyaction
	char *fieldPlaceholder = (char *) calloc(1, 64096);	
	char *disabledStr = (char *) calloc(1, 64096);
	char *tmp = (char *) calloc(1, 64096);
	int randId = rand() % 9999999;	// default to non-grid
	VAR *variable = NULL;
	VAR *variableSearch = NULL;

	if (inputType == GRIDINP)
		randId = cmdSeqnum;

	getWord(fieldType, args, 2, " \t");
	if (!fieldType) {
	   logMsg(LOGERROR, "missing input type for html input");
	   return(-1);
	}
	getWord(fieldVar, args, 3, " \t");
	if (!fieldVar) {
	   logMsg(LOGERROR, "missing input variable for html input");
	   return(-1);
	}
	getWord(fieldSize, args, 4, " \t");
	if (!fieldSize) {
	   logMsg(LOGERROR, "missing input size for html input");
	   return(-1);
	}

	if (strcasecmp(fieldType, "disabled")) {
		if ((!strchr(fieldVar, '.')) && (defaultTableName))
			sprintf(fieldVar, "%s.%s", defaultTableName, fieldVar);
	}
	variable = findVarStrict(fieldVar);
	if (variable)
		strcpy(fieldVarValue, variable->portableValue);

	if (!strcasecmp(fieldType, "filter")) {
		if ((!strchr(fieldSize, '.')) && (defaultTableName))
			sprintf(fieldSize, "%s.%s", defaultTableName, fieldSize);
		variableSearch = findVarStrict(fieldSize);
		if (variableSearch)
			strcpy(fieldSearchValue, variableSearch->portableValue);
	}

	if (!strcasecmp(fieldType, "dropdown")) {
		if ((!strchr(fieldSize, '.')) && (defaultTableName))
			sprintf(fieldSize, "%s.%s", defaultTableName, fieldSize);
		variableSearch = findVarStrict(fieldSize);
		if (variableSearch)
			strcpy(fieldSearchValue, variableSearch->portableValue);
	}

	getWord(fieldPrompt, args, 5, " \t");
	getWord(fieldPlaceholder, args, 6, " \t");

	if (inputType == INPUT) {
		emitStd("<div class='uk-form-row'>\n");
		if ((!strcasecmp(fieldType, "filter")) || (!strcasecmp(fieldType, "dropdown")))
			emitStd("	<label class='uk-form-label' for='%s'>%s</label>\n", fieldVar, fieldPlaceholder);
		else
			emitStd("	<label class='uk-form-label' for='%s'>%s</label>\n", fieldVar, fieldPrompt);
		emitStd("		<div class='uk-form-controls'>\n");
	}
	if (!strcasecmp(fieldType, "filter")) {
		emitJs(	"// Handler for autocomplete (%d) \n", randId);
		emitJs(	"function SEQ_%d_SEARCH_DIV_AJAX(release) { \n", randId);
		emitJs(	"	$.ajax({ \n");
		emitJs(	"		url : '/run/sys/run/autocomplete:filterAutocomplete', type: 'POST', data : '_filtervalue='+document.getElementById('SEQ_%d_SEARCH_VALUE').value+'&_filterfield='+document.getElementById('SEQ_%d_SEARCH_FIELDNAME').value+'&_dbname='+document.getElementById('_dbname').value, \n", randId, randId);
		emitJs(	"		success: function(data, textStatus, jqXHR) { \n");
		emitJs(	"			var dat = []; \n");
		emitJs(	"			dat = JSON.parse(data); \n");
		emitJs(	"			release(dat); \n");
		emitJs(	"		}, \n");
		emitJs(	"		error: function (jqXHR, textStatus, errorThrown) { \n");
		emitJs(	"			alert('autocomplete ajax call failed'); \n");
		emitJs(	"		}, \n");
		emitJs(	"	}); \n");
		emitJs(	"} \n");
		// Add to breakpoint
		int i;
		for (i = 0; i < MAX_BREAKPOINTS; i++) {
			if (breakpointAutocompleteId[i] < 1 ) {
				breakpointAutocompleteId[i] = randId;
				break;
			}
		}
		if (i == MAX_BREAKPOINTS) {
			logMsg(LOGFATAL, "Max breakpoints reached");
			return(-1);
		}

#ifdef ABC
filter:       fieldType  fieldVar->fieldVarValue              fieldSize->fieldSearchValue  prompt  placeholder
        1     2          3                                    4                            5       6
{{@html input filter    stock_supplier_order.stock_supplier_id stock_supplier.name medium Supplier}}
#endif
		emitStd("<input type='hidden' id='SEQ_%d_SEARCH_FIELDNAME' value='%s'> \n", randId, fieldSize);
		emitStd("<input type='hidden' id='SEQ_%d_SEARCH_RESULT' name='%s' id='%s' value='%s'> \n", randId, fieldVar, fieldVar, fieldVarValue);
		emitStd("<div id='SEQ_%d_SEARCH_DIV' class='uk-autocomplete uk-form' data-uk-autocomplete='off'> \n", randId);
		emitStd("	<input type='text' id='SEQ_%d_SEARCH_VALUE' value='%s' class='uk-form-width-%s'> \n", randId, fieldSearchValue, fieldPrompt);
// @@KIM emitStd below emitJs
		emitStd("	<script type='text/autocomplete'> \n");
		emitStd("		<ul class='uk-nav uk-nav-autocomplete uk-autocomplete-results'> \n");
		emitStd("			{{~items}} \n");
		emitStd("				<li class='clicked' data-value='{{ $item.value }}'  data-id='{{ $item.id }}'> \n");
		emitStd("					<a> {{ $item.value }} </a> \n");
		emitStd("				</li> \n");
		emitStd("			{{/items}} \n");
		emitStd("		</ul> \n");
		emitStd("	</script> \n");
		emitStd("</div> \n");
	}
	else if (!strcasecmp(fieldType, "dropdown")) {
		logMsg(LOGDEBUG, "Select: fieldVarValue=[%s] and fieldSize=[%s]", fieldVarValue, fieldSize);
		emitStd("	<select id='SEQ_%d_SELECT_VALUE' name='%s' value='%s' onchange='//alert(this.value);' class='uk-form-width-%s'> \n", randId, fieldVar, fieldVarValue, fieldPrompt);
		// Construct all the <option> tags from content
		char tmp2[64096];
		sprintf(tmp2, fieldSize);
		char *p = strchr(tmp2, '.');
		if (p)
			*p = '\0';
		sprintf(tmp, "select * from %s order by id", tmp2);
		logMsg(LOGDEBUG, "Select: about to query for all options using raw sql [%s]", tmp);
		int status = doSqlQuery(tmp);
		if (status == -1) {
			logMsg(LOGERROR, "Sql query failed - doSqlQuery() failed");
			return (-1);
		}
		logMsg(LOGDEBUG, "Getting RES for raw query");
		MYSQL_RES *res = mysql_store_result(conn);
		if (res != NULL) {	// ie the query returned row(s)
			logMsg(LOGDEBUG, "RES is non-null");
			SQL_RESULT *rp = sqlCreateResult(tmp2, res);
			strcat(tmp2, ".id");
			while (sqlGetRow2Vars(rp) != SQL_EOF) {
				VAR *idVar = findVarStrict(tmp2);
				VAR *showVar = findVarStrict(fieldSize);
				logMsg(LOGDEBUG, "Select: found option [%s]->[%s]", idVar->portableValue, showVar->portableValue);
				char selected[128];
				strcpy(selected, "");
				if (!strcmp(idVar->portableValue, fieldVarValue))
					strcpy(selected, "selected='selected'");
				emitStd("    <option value='%s' %s>%s</option> \n", idVar->portableValue, selected, showVar->portableValue);
			}
			mysql_free_result(res);
		} else logMsg(LOGDEBUG, "RES is null");

		emitStd("  </select> \n");
	}
	else if (!strcasecmp(fieldType, "keyaction")) {
		emitJs(	"// onKeyUp handler for keyaction ID %d \n"
					"function onKeyUp_%d() { \n"
					"	var valel = document.getElementById('keyaction_%d'); \n"
					"	var el = document.getElementById('keyaction'); \n"
					"	if (el == null) { \n"
					"		var input = document.createElement('input'); \n"
					"		input.setAttribute('type', 'hidden'); \n"
					"		input.setAttribute('name', 'keyaction'); \n"
					"		input.setAttribute('id', 'keyaction'); \n"
					"		input.setAttribute('value', valel.value); \n"
					"		document.body.appendChild(input); \n"
					"	} else { \n"
					"		el.setAttribute('value', valel.value); \n"
					"	} \n"
					"	runAction('%s', 'keyaction %s', '%s'); \n"
					"} \n"
					, randId, randId, randId, fieldVar /*actually jam:action*/, fieldSize /*actually input*/, fieldPrompt /*actually outputResult*/);
		if ((inputType == INPUT) || (inputType == INP))
			emitStd("		<input type='text' name=keyaction_%d id='keyaction_%d' value='' onkeyup='onKeyUp_%d()' class='uk-form-width-%s'>\n", randId, randId, randId, fieldPlaceholder);
		else
			emitStd("		<input type='text' id='keyaction_%d' value='' onkeyup='onKeyUp_%d()' class='uk-form-width-%s'>\n", randId, randId, fieldPlaceholder);
	} else {
		if (!strcasecmp(fieldType, "disabled")) {
			strcpy(disabledStr, " disabled ");
			strcpy(fieldType, "text");
		}
		if ((inputType == INPUT) || (inputType == INP))
			emitStd("		<input type='%s' name='%s' id='SEQ_%d_%s' value='%s' placeholder='%s' class='uk-form-width-%s' onChange='fn(this, event);' %s>\n", fieldType, fieldVar, randId, fieldVar, fieldVarValue, fieldPlaceholder, fieldSize, disabledStr);
		else		// 'inp' only
			emitStd("		<input type='%s' name='%s' id='SEQ_%d_%s' value='%s' class='uk-form-width-%s' onChange='fn(this, event)' %s>\n", fieldType, fieldVar, randId, fieldVar, fieldVarValue, fieldSize, disabledStr);
	}
	if (inputType == INPUT) {
		emitStd("	</div>\n");
		emitStd("</div>\n");
	}
	emitStd(jam[ix]->trailer);
	free(fieldName);
	free(fieldType);
	free(fieldSize);
	free(fieldVar);
	free(fieldVarValue);
	free(fieldSearchValue);
	free(fieldPrompt);
	free(fieldPlaceholder);
	free(disabledStr);
	free(tmp);
}


int wordHtmlInp(int ix, char *defaultTableName) {
	return(_wordHtmlInputInp(ix, defaultTableName, INP));
}
int wordHtmlInputOld(int ix, char *defaultTableName) {
	return(_wordHtmlInputInp(ix, defaultTableName, INPUT));
}
int wordHtmlGridInp(int ix, char *defaultTableName) {
	return(_wordHtmlInputInp(ix, defaultTableName, GRIDINP));
}

/****************************************************************************************

//	{{@html textarea stock_supplier.notes 60x5 Notes}}
int wordHtmlTextarea(int ix, char *defaultTableName) {
	char *cmd = jam[ix]->command;
	char *args = jam[ix]->args;
	char *rawData = jam[ix]->rawData;
	char *fieldName = (char *) calloc(1, 4096);
	char *fieldSize = (char *) calloc(1, 4096);
	char *fieldSize2 = (char *) calloc(1, 4096);
	char *fieldVar = (char *) calloc(1, 4096);
	char *fieldVarValue = (char *) calloc(1, 4096);
	char *fieldPrompt = (char *) calloc(1, 4096);
	char *fieldPlaceholder = (char *) calloc(1, 4096);
	char *tmp = (char *) calloc(1, 4096);
	VAR *variable = NULL;

	getWord(fieldVar, args, 2, " \t");
	if (!fieldVar) {
	   logMsg(LOGERROR, "missing input variable for html textarea");
	   return(-1);
	}
	getWord(fieldSize, args, 3, " \t");
	if (!fieldSize) {
	   logMsg(LOGERROR, "missing input size for html textarea");
	   return(-1);
	}
	getWord(fieldSize2, fieldSize, 2, "x");	// rows
	getWord(fieldSize, fieldSize, 1, "x");	// cols

	if ((!strchr(fieldVar, '.')) && (defaultTableName))
		sprintf(fieldVar, "%s.%s", defaultTableName, fieldVar);
	variable = findVarStrict(fieldVar);
	if (variable)
		strcpy(fieldVarValue, variable->portableValue);

	getWord(fieldPrompt, args, 4, " \t");
	getWord(fieldPlaceholder, args, 5, " \t");

	emitStd("<div class='uk-form-row'>\n");
	emitStd("	<label class='uk-form-label' for='%s'>%s</label>\n", fieldVar, fieldPrompt);
	emitStd("		<div class='uk-form-controls'>\n");
	emitStd("		<textarea name='%s' id='%s' cols='%s' rows='%s' placeholder='%s'>%s</textarea>", fieldVar, fieldVar, fieldSize, fieldSize2, fieldPlaceholder, fieldVarValue);
	emitStd("	</div>\n");
	emitStd("</div>\n");

	emitStd(jam[ix]->trailer);
	free(fieldName);
	free(fieldSize);
	free(fieldSize2);
	free(fieldVar);
	free(fieldVarValue);
	free(fieldPrompt);
	free(fieldPlaceholder);
	free(tmp);
}
****************************************************************************/

/*	{{@html button Save primary small
		alert('ok')     // or any js
		runJam
		runJam supplierMaint
		runAction updateSupplier supplierForm outputResult backButton
		runAction updateSupplier supplierForm outputResult runJam supplierMaint    // where supplierMaint is the arg to runJam
}} */

int wordHtmlButton(int ix, char *defaultTableName) {
	char *cmd = jam[ix]->command;
	char *args = jam[ix]->args;
	char *rawData = jam[ix]->rawData;
	char *buttonText = (char *) calloc(1, 64096);
	char *buttonType = (char *) calloc(1, 64096);
	char *buttonSize = (char *) calloc(1, 64096);
	char *buttonJS = (char *) calloc(1, 64096);
	char *tmp = (char *) calloc(1, 64096);
	char *group = (char *) calloc(1, 64096);
	int buttonId = rand() % 9999999;			// @@TODO fix

	getWord(buttonText, args, 2, " \t");
	if (!buttonText) {
	   logMsg(LOGERROR, "missing button text");
	   return(-1);
	}
	getWord(buttonType, args, 3, " \t");
	if (!buttonType) {
	   logMsg(LOGERROR, "missing button type");
	   return(-1);
	}
	getWord(buttonSize, args, 4, " \t");
	if (!buttonSize) {
	   logMsg(LOGERROR, "missing button size");
	   return(-1);
	}

    // Group(s)
    sprintf(group, "ROW_%d ", cmdSeqnum);
    if (isVar("sys.control.group"))
        strcat(group,  getVarAsString("sys.control.group"));
	emitStd("<button type='button' onClick='buttonClick%d(this)' class='uk-button uk-button-%s uk-button-%s'>%s</button>\n", buttonId, buttonSize, buttonType, buttonText);
	emitJs("buttonClick%d = function(obj) { \n", buttonId);
	int cnt = 2;
	while (char *block = strTrim(getWordAlloc(args, cnt++, "\n"))) {
		char *command = strTrim(getWordAlloc(block, 1, " \t"));
		if (!command)
			break;
		if (!strcasecmp(command, "runJam")) {		// Parenthesize argument if any
			char *jamName = strTrim(getWordAlloc(block, 2, " \t"));
			if (jamName)
				sprintf(tmp, "%s('%s');\n", command, jamName);
			else
				sprintf(tmp, "%s();\n", command);
			emitJs(tmp);
			free(command);
			free(jamName);
		} else if (!strcasecmp(command, "runAction")) {	// Look for AJAX command, we need to wait for return there
			char *pBlock = strchr(block, ' ');
			sprintf(tmp, "%s(", command);
			emitJs(tmp);
			int ix = 1;
//emitStd("<br> \n----->%s <br>\n", pBlock);
			while(char *runActionArg = strTrim(getWordAlloc(pBlock, ix, " "))) {
				if (!runActionArg)
					break;
//emitStd("-->%s <br>\n", runActionArg);
				if (ix != 1)
					emitJs(", ");
				if (ix < 4) {
					if ((!strchr(runActionArg, '"')) && (!strchr(runActionArg, '\''))) {
						sprintf(tmp, "'%s'", runActionArg);
						emitJs(tmp);
					} else
						emitJs(runActionArg);
				}
				else
					emitJs(runActionArg);
					//strcat(buttonJS, "@@TODOCALLBACK");
				free(runActionArg);
				ix++;
			}
			emitJs(");\n");
		} else {
			sprintf(tmp, "%s\n", block);
			emitJs(tmp);
		}
//emitStd("-----> <br>\n");
		free(block);
	}
	emitJs("}\n\n");
	emitStd(jam[ix]->trailer);
	free(buttonText);
	free(buttonType);
	free(buttonSize);
	free(buttonJS);
	free(group);
	free(tmp);
}

//	{{@html select stock_supplier.name stock_supplier. medium "Choose supplier" stock_purchorder.supplier_id}}

int wordHtmlSelect(int ix, char *defaultTableName) {
	char *cmd = jam[ix]->command;
	char *args = jam[ix]->args;
	char *rawData = jam[ix]->rawData;
	char *fieldVarName = (char *) calloc(1, 64096);
	char *fieldVarValue = (char *) calloc(1, 64096);
	char *fieldSize = (char *) calloc(1, 64096);
	char *fieldPrompt = (char *) calloc(1, 64096);
	char *fieldVarSelected = (char *) calloc(1, 64096);
	char *tmp = (char *) calloc(1, 64096);
	VAR *variable = NULL;
/*
	getWord(fieldVarName, args, 2, " \t");
	if (!fieldVarName) {
	   logMsg(LOGERROR, "missing name variable for select");
	   return(-1);
	}
	getWord(fieldVarValue, args, 3, " \t");
	if (!fieldVarValue) {
	   logMsg(LOGERROR, "missing value variable for select");
	   return(-1);
	}
	getWord(fieldSize, args, 4, " \t");
	if (!fieldSize) {
	   logMsg(LOGERROR, "missing size for select");
	   return(-1);
	}
	getWord(fieldSelected, args, 5, " \t");

	if ((!strchr(fieldVar, '.')) && (defaultTableName))
		semitStd(fieldVar, "%s.%s", fieldVar, defaultTableName);
	variable = findVarStrict(fieldVar);
	if (variable)
		strcpy(fieldVarValue, variable->portableValue);

	getWord(fieldPrompt, args, 4, " \t");
	getWord(fieldPlaceholder, args, 5, " \t");

	emitStd("<div class='uk-dropdown uk-dropdown-scrollable'>\n");
	emitStd(""

	emitStd("<div class='uk-dropdown uk-dropdown-scrollable>\n");
	emitStd("	<label class='uk-form-label' for='%s'>%s</label>\n", fieldVar, fieldPrompt);
	emitStd("		<div class='uk-form-controls'>\n");
	emitStd("		<textarea name='%s' id='%s' cols='%s' rows='%s'>%s</textarea>", fieldVar, fieldVar, fieldSize, fieldSize2, fieldVarValue);
	emitStd("	</div>\n");
	emitStd("</div>\n");


<select name="{filter.select}">
	{@each stock_area}
		<option value="{id}">{name}</option>
	{@end}
</select>


	emitStd(jam[ix]->trailer);
	free(fieldVarName);
	free(fieldVarValue);
	free(fieldSize);
	free(fieldPrompt);
	free(fieldVarSelected);
	free(tmp);
*/
}

//-----------------------------------------------------------------
// Dont know where else to put this. Its the only way for Actions to create runnable JS

int wordHtmlJs(int ix, char *defaultTableName) {
	char *cmd = jam[ix]->command;
	char *args = jam[ix]->args;
	char *rawData = jam[ix]->rawData;
	char *tmp = (char *) calloc(1, 64096);
 
	char *p = args;
	while ((*p) && (*p != ' '))
		p++;
	emitJs("%s", p);
	logMsg(LOGDEBUG, "wordHtmlJs emitting js [%s]", args);

	free(tmp);
}

//-----------------------------------------------------------------
// HTML sys - 'filterAutocomplete' is called from run/sys/xxxx.jam

int wordHtmlSys(int ix, char *defaultTableName) {
	char *cmd = jam[ix]->command;
	char *args = jam[ix]->args;
	char *rawData = jam[ix]->rawData;
	char *sysName = (char *) calloc(1, 64096);
	char *tmp = (char *) calloc(1, 64096);

	getWord(sysName, args, 2, " \t");
	if (!sysName)
	   die("missing HTML sys name");

	logMsg(LOGDEBUG, "html sys %s activated", sysName);

	if (!strcasecmp(sysName, "filterAutocomplete")) {
// Autocomplete <input type=filter> cant produce json (yet) or db handle '%like%' (needs embedded curlies to work) so we have this custom operation temporarily - a @@TODO
		char *q = (char *) calloc(1, 64096);
		char *customField = (char *) calloc(1, 64096);
		char *customValue = (char *) calloc(1, 64096);
		char *tableName = (char *) calloc(1, 64096);
		char *fieldName = (char *) calloc(1, 64096);
		getWord(customField, args, 3, " \t");
		if (!customField) {
			logMsg(LOGERROR, "missing field '_filterfield'");
			return(-1);
		}
		getWord(customValue, args, 4, " \t");
		if (!customValue) {
			logMsg(LOGERROR, "missing value '_filtervalue'");
			return(-1);
		}
		VAR *variableField = findVarStrict(customField);
		if (!variableField) {
			logMsg(LOGERROR, "missing custom variable for '_filterfield'. Looking (strict) for '%s'", customField);
			return(-1);	
		}
		VAR *variableValue = findVarStrict(customValue);
		if (!variableValue) {
			logMsg(LOGERROR, "missing custom variable for '_filtervalue'. Looking (strict) for '%s'", customValue);
			return(-1);	
		}
		getWord(tableName, variableField->portableValue, 1, ".");
		getWord(fieldName, variableField->portableValue, 2, ".");
		// Kludge to handle old 'id' vs 'id' field in tables
		char idField[512];
		sprintf(idField, "id");
		char searchValue[1024];
		sprintf(searchValue, variableValue->portableValue);
		if (!strcmp(searchValue, " "))
			strcpy(searchValue, "");
		sprintf(q, "select %s, %s from %s where %s like '%%%s%%'", idField, fieldName, tableName, fieldName, searchValue);
		logMsg(LOGDEBUG, "Autocomplete custom prepared query [%s]", q);
		int status = doSqlQuery(q);
		if (status == -1) {
			logMsg(LOGERROR, "Sql query failed - doSqlQuery() failed");
			return (-1);
		}
		logMsg(LOGDEBUG, "Getting RES for raw query");
		MYSQL_RES *res = mysql_store_result(conn);
		if (res != NULL) {	// ie the query returned row(s)
			logMsg(LOGDEBUG, "RES is non-null");
			SQL_RESULT *rp = sqlCreateResult(tableName, res);
			int first = 1;
			emitStd("[");
			while (sqlGetRow2Vars(rp) != SQL_EOF) {
				VAR *v = findVarStrict(variableField->portableValue);
				sprintf(tmp, "%s.%s", tableName, idField);
				VAR *id = findVarStrict(tmp);
				if ((!v) || (!id)) {
					logMsg(LOGERROR, "internal error - either cant retrieve row jam variable or its id");
					return(-1);	
				}
				if (first)
					first = 0;
				else
					emitStd(", ");
				char *valJSON = escapeJsonChars(v->portableValue);
				char *idJSON = escapeJsonChars(id->portableValue);
				// Avoid single quotes - the formal JSON spec doesnt allow them
				emitStd("{\"value\":\"%s\", \"id\":\"%s\"}", valJSON,  idJSON);
				free(valJSON);
				free(idJSON);
			}
			emitStd("]");
		} else
			logMsg(LOGDEBUG, "RES is null");
		free(q);
		free(customField);
		free(customValue);
		free(tableName);
		free(fieldName);
	}

	emitStd(jam[ix]->trailer);
	free(sysName);
	free(tmp);
}

//-----------------------------------------------------------------
// HTML breakpoints. End of header, body, etc. Do something at these points

int wordHtmlBreakpoint(int ix, char *defaultTableName) {
	char *cmd = jam[ix]->command;
	char *args = jam[ix]->args;
	char *rawData = jam[ix]->rawData;
	char *breakpointName = (char *) calloc(1, 64096);
	char *tmp = (char *) calloc(1, 64096);

	getWord(breakpointName, args, 2, " \t");
	if (!breakpointName)
	   die("missing HTML breakpoint name");

	logMsg(LOGDEBUG, "html breakpoint %s activated", breakpointName);

	if (!strcasecmp(breakpointName, "body")) {
		char *breakpointBodyArg = (char *) calloc(1, 64096);
		getWord(breakpointBodyArg, args, 3, " \t");
		if (!breakpointBodyArg)
		   die("missing HTML breakpoint body arg");

		if (!strcasecmp(breakpointBodyArg, "end")) {	// Called from sys/html/footer.html

			// GENERATE END STUFF WEVE BEEN COLLECTING
			// ---------------------------------------
			if (breakpointAutocompleteId[0] != 0) {
				// Generate the init for uikit autocomplete
				emitJs(	"// Include autocomplete JS and CSS \n");
				emitJs(	"$.getScript('/jam/sys/extern/uikit/js/components/autocomplete.js', initAutocomplete ); \n");
				emitJs(	"var linkElem = document.createElement('link'); \n");
				emitJs(	"document.getElementsByTagName('head')[0].appendChild(linkElem); \n");
				emitJs(	"linkElem.rel = 'stylesheet'; linkElem.type = 'text/css'; \n");
				emitJs(	"linkElem.href = '/jam/sys/extern/uikit/css/components/autocomplete.css'; \n\n");

				emitJs(	"function initAutocomplete() { \n");

				emitJs(	"	// Init autocomplete for zero'th element (grid inserts) \n\n");
				emitJs(	"	autocomplete = $.UIkit.autocomplete($('#SEQ_0_SEARCH_DIV'), { 'source': SEQ_0_SEARCH_DIV_AJAX, minLength:1}); \n");
				emitJs(	"	$('#SEQ_0_SEARCH_DIV').on('selectitem.uk.autocomplete', function(event, data, ac){ \n");
				emitJs(	"		var obj = document.getElementById('SEQ_0_SEARCH_RESULT'); \n");
				emitJs(	"		obj.value = data.id; \n");
				emitJs(	"		var evt = document.createEvent('HTMLEvents'); \n");
				emitJs(	"		evt.initEvent('change', false, true); \n");
				emitJs(	"		fn(obj, evt); \n");
				emitJs("	}); \n");
	
				emitJs(	"// Init autocomplete for each element \n\n");
				for (int i = 0; breakpointAutocompleteId[i] != 0; i++) {
					emitJs(	"	autocomplete = $.UIkit.autocomplete($('#SEQ_%d_SEARCH_DIV'), { 'source': SEQ_%d_SEARCH_DIV_AJAX, minLength:1}); \n", breakpointAutocompleteId[i], breakpointAutocompleteId[i]);
					emitJs(	"	$('#SEQ_%d_SEARCH_DIV').on('selectitem.uk.autocomplete', function(event, data, ac){ \n", breakpointAutocompleteId[i]);
					emitJs(	"		var obj = document.getElementById('SEQ_%d_SEARCH_RESULT'); \n", breakpointAutocompleteId[i]);
					emitJs(	"       obj.value = data.id; \n");
					emitJs(	"		var evt = document.createEvent('HTMLEvents'); \n");
					emitJs(	"		evt.initEvent('change', false, true); \n");
					emitJs(	"		fn(obj, evt); \n");
					emitJs("	}); \n");
				}
				emitJs("} \n");
				logMsg(LOGDEBUG, "created init js for uikit autocomplete");
			}
			// Embed the db name in the html for any @action calls
			if (connDbName != NULL) {
				emitStd("<input type='hidden' id='_dbname' name='_dbname' value='%s'>", connDbName);
				logMsg(LOGDEBUG, "created hidden _dbname element");
			} else
				logMsg(LOGDEBUG, "did NOT create hidden _dbname element (no db active)");

			endJs(urlEncodeRequired);	// No encode

		}
		free(breakpointBodyArg);
	}

	emitStd(jam[ix]->trailer);
	free(breakpointName);
	free(tmp);
}

// ----------------------------------------------------------------------------------------
// Utility stuff

// 1) Create a string in the form IDnn___table___field in order to store the value being input in its table
//    Each html input field's id contains the lookup key required to store the field, eg "ID23___customer___name"
// 2) If its not a db item it might be a list @@TODO
// 3) If its neither its a var (which may or may not yet exist) in the form VAR___name
//
//if table given (either was specified or left empty and defaulted to current table)
//    does table exist in db
//    if istable
//        does field exist in table
//        if yes
//            lookup current table >id< value in *VAR
//            if exist in *VAR
//                ....done ID76___table___field
//            else
//                ....done ID0___table___field
//
//if still here it must be a var (may or may not yet exist)
//    ....done VAR___rawvalue
//
char *makeJamKeyValue(char *tableName, char *fieldName, char *rawValue) {
	char *ret = (char *) calloc(1, 64096);
	sprintf(ret, "%s.id", tableName);
	logMsg(LOGMICRO, "makeJamKeyValue() - received table=[%s], field=[%s], raw=[%s]", tableName, fieldName, rawValue);
	// Is is a table/field?
	if ( (strlen(tableName)) && (isMysqlTable(tableName)) && (isMysqlField(fieldName, tableName)) ) {
		VAR *variable = findVarStrict(ret);
		if (variable) {
			sprintf(ret, "ID%s___%s___%s", variable->portableValue, tableName, fieldName);
			logMsg(LOGDEBUG, "makeJamKeyValue() [%s] created (db). Exists as a variable", ret);
		} else {
			sprintf(ret, "ID0___%s___%s", tableName, fieldName);
			logMsg(LOGDEBUG, "makeJamKeyValue() [%s] created (db). Does not exist as a variable", ret);
		}
		return(ret);
	}
	// Is it a variable?
	sprintf(ret, "VAR___%s", rawValue);
	return(ret);
}
