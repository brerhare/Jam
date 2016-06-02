<!--
/* IMPORTANT
	@include this file at the end
*/
-->

<style>
.exportButton{
    color:#ffffff;
    background-color:#009dd8;
    background-image: linear-gradient(to bottom,#00b4f5,#008dc5);
    border-color: rgba(0,0,0,.2);
    border-bottom-color: rgba(0,0,0,.4);
    text-shadow: 0 -1px 0 rgba(0,0,0,.2);
    padding:7px 15px;
    max-width: 300px;
    text-align:center;
    border-radius: 3px;
}
.exportButton a{ color:#fff;}
</style>

<script>

// Print
// -----

/* EXAMPLE:
	<button class="uk-button uk-button-medium uk-button-primary" onClick="printData('printTable')"> Print </button>
*/

function printData(elementName)
{
    var divToPrint=document.getElementById(elementName);
    newWin= window.open("");
    newWin.document.write(divToPrint.outerHTML);
    newWin.print();
    newWin.close();
}

// Export
// -----

/* EXAMPLE:
	<span class='exportButton'>
    	<a href="#" class="export" table='{"id":"printTable"}'>Export spreadsheet</a>
	</span>
*/

function exportTableToCSV($table, filename) {
    var $rows = $table.find('tr:has(td),tr:has(th)'),
    // Temporary delimiter characters unlikely to be typed by keyboard. This is to avoid accidentally splitting the actual contents
    tmpColDelim = String.fromCharCode(11), // vertical tab character
    tmpRowDelim = String.fromCharCode(0), // null character
    // actual delimiter characters for CSV format
    colDelim = '","',
    rowDelim = '"\r\n"',
    // Grab text from table into CSV formatted string
    csv = '"' + $rows.map(function (i, row) {
        var $row = $(row),
        $cols = $row.find('td:visible,th');
        return $cols.map(function (j, col) {
            var $col = $(col),
            text = $col.text();
            return text.replace(/"/g, '""'); // escape double quotes
        }).get().join(tmpColDelim);
    }).get().join(tmpRowDelim).split(tmpRowDelim).join(rowDelim).split(tmpColDelim).join(colDelim) + '"',
    // Data URI
    csvData = 'data:application/csv;charset=utf-8,' + encodeURIComponent(csv);
    $(this).attr({
        'download': filename,
        'href': csvData,
        'target': '_blank'
    });
}

// This must be a hyperlink
$(".export").on('click', function (event) {
    // CSV
    var data = JSON.parse($(this).attr("table"));
    var tn = '#'+data.id;
    exportTableToCSV.apply(this, [$(tn), 'export.csv']);
    // IF CSV, don't do event.preventDefault() or return false
    // We actually need this to be a typical hyperlink
});

// Search
// ------

/* EXAMPLE:
	<table class="searchTable>
*/

var $rows = $('.searchTable tr:has(td)');
$('#searchInput').keyup(function() {
    var val = '^(?=.*\\b' + $.trim($(this).val()).split(/\s+/).join('\\b)(?=.*\\b') + ').*$',
        reg = RegExp(val, 'i'),
        text;
    $rows.show().filter(function() {
        text = $(this).text().replace(/\s+/g, ' ');
        return !reg.test(text);
    }).hide();
});


</script>
