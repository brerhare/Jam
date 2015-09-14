This relies on the hidden _dbname embedded in the calling html page to know which db to use
------------------------------------------------------------------------------------------
{{@action filterAutocomplete}}
	{{@custom html autocomplete _filterfield _filtervalue}}
{{@end}}

