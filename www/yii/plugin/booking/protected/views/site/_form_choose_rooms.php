<?php
$baseUrl = Yii::app()->baseUrl;
$cs = Yii::app()->getClientScript();
$cs->registerCoreScript('jquery');
$cs->registerScriptFile($baseUrl.'/js/jquery-ui.min.js');
?>

<?php // Control variables
$maxRooms = 3; // How many rooms can be booked
$showDays = 14; // How many days to show on calendar grid
//-------------------
echo "<script>var maxRooms=" . $maxRooms . ";</script>";
echo "<script>var showDays=" . $showDays . ";</script>";
//-------------------
?>

<style>
/* Turn off annoying datepicker tooltips */
.ui-tooltip {width:0px; height:0px}
</style>

<script>

// Set the starting display date to today (epoch format)
var timeStamp = Math.floor(new Date().getTime() / 1000);

function changeDate(days) {
	timeStamp += (days * 86400);
	ajaxGetRoomPriceAvail();
}
function setDate(){
	var v = document.getElementById("datepicker");
	dd = v.value.substr(0, 2);
	mm = v.value.substr(3, 2);
	yyyy = v.value.substr(6, 4);
	timeStamp = new Date(yyyy, mm-1, dd).getTime() / 1000;
	ajaxGetRoomPriceAvail();
	// Also set the arrival/departure dates
	dt = new Date(yyyy, mm-1, dd);
	$('#arrivedate').datepicker().datepicker('setDate', dt);
	onArrivalDateChange();
}

function onArrivalDateChange()
{
	var v = document.getElementById("arrivedate");
	dd = v.value.substr(0, 2);
	mm = v.value.substr(3, 2);
	yyyy = v.value.substr(6, 4);
	dt = new Date(yyyy, mm-1, dd);
	dt2 = new Date(yyyy, mm-1, dt.getDate()+1);
	timeStamp = new Date(yyyy, mm-1, dd).getTime() / 1000;
	$('#datepicker').datepicker().datepicker('setDate', dt);
	$('#departdate').datepicker().datepicker('setDate', dt2);
	$("#departdate").datepicker( "option", "minDate", dt2 );
	ajaxGetRoomPriceAvail();
}
// Store the basic details for every room at startup
<?php
$criteria = new CDbCriteria;
$criteria->addCondition("uid = " . Yii::app()->session['uid']);
$rooms = Room::model()->findAll($criteria);
echo "var roomData = [];"; // Array containing all the room objects
foreach ($rooms as $room)
{
	echo "room = new Object();";
	echo "room.id = '" . $room->id . "';";
	echo "room.title = '" . $room->title . "';";
	echo "room.description = '" . $room->description . "';";
	echo "room.qty = '" . $room->qty . "';";
	echo "room.max_adult = '" . $room->max_adult . "';";
	echo "room.max_child = '" . $room->max_child . "';";
	echo "room.max_total = '" . $room->max_total . "';";

	// Get the cheapest price for this room
	$s = 0; $d = 0; $a = 0; $c = 0; $cap = 0;
	$criteria = new CDbCriteria;
	$criteria->addCondition("uid = " . Yii::app()->session['uid']);
	$criteria->addCondition("room_id = " . $room->id);
	$roomHasOccupancyTypes = RoomHasOccupancyType::model()->findAll($criteria);
	foreach ($roomHasOccupancyTypes as $roomHasOccupancyType)
	{
		$chk_s = (float) $roomHasOccupancyType->single_rate;
		$chk_d = (float) $roomHasOccupancyType->double_rate;
		$chk_a = (float) $roomHasOccupancyType->adult_rate;
		$chk_c = (float) $roomHasOccupancyType->child_rate;
		$chk_cap = (float) $roomHasOccupancyType->cap_rate;
		if (($chk_s > 0) && (($chk_s < $s) || ($s == 0)))
			$s = $chk_s;
		if (($chk_d > 0) && (($chk_d < $d) || ($d == 0)))
			$d = $chk_d;
		if (($chk_a > 0) && (($chk_a < $a) || ($a == 0)))
			$a = $chk_a;
		if (($chk_c > 0) && (($chk_c < $c) || ($c == 0)))
			$c = $chk_c;
		if (($chk_cap > 0) && (($chk_cap < $cap) || ($cap == 0)))
			$cap = $chk_cap;
	}
	echo "room.prices = new Array();";
	echo "room.prices[0]=" . $s . ";";
	echo "room.prices[1]=" . $d . ";";
	echo "room.prices[2]=" . $a . ";";
	echo "room.prices[3]=" . $c . ";";
	echo "room.prices[4]=" . $cap . ";";
	echo "room.avail = new Array();";
	echo "roomData.push(room);";
}
?>
</script>

<style>

.well {
	padding:10px 20px 0px 20px;
}
.boxy {
	background-color: #e3e3e3;
	padding:0px 10px 0px 10px;
	width:550px;*/

}
.krow, .kspan8 {padding: 10px;}

table#topPick {
	padding:5px;
}
 
table td, table th {
	padding: 0px;
}
.cweekday, .cweekend {
	text-align:center;
	background-color: #434a50;
	color:#ffffff;
	border-left:1px solid white;
	border-right:1px solid white;
	font-family:Helvetica, Arial, sans-serif;
	font-weight:100;
	font-size:13px;
	width:5%;
}
.cweekend {
	background-color: #2a2f34;
	width:5%;
}
.cline {
	background-color: #ECF8FA;
	background-color: #46679C;
	text-align:center;
	color:#ffffff;
	font-weight:light;
	font-size:12px;
	height:30px;
}
td:first-child+td.cline {
	border-left:1px solid white;
}
.roomnocell1 {
	display:block;
	background-color: #fbedac;
	background-color: #A9BAD6;
	padding:2px 10px;
}
.roomnocell2 {

	background-color: #fbedac;
	background-color: #A9BAD6;
}
.roomline {
	color:#46679c;
	font-weight:bold; 
}
</style>

<style>
/* Pretty buttons. Source: http://designmodo.com/create-css3-buttons/#ixzz2Sbk1NSMl */
.button {
    display: inline-block;
    position: relative;
    margin: 1px;
    padding: 0 10px;
    text-align: center;
    text-decoration: none;
    font: bold 12px/25px Arial, sans-serif;
    text-shadow: 1px 1px 1px rgba(255,255,255, .22);
    -webkit-border-radius: 30px;
    -moz-border-radius: 30px;
    border-radius: 30px;
    -webkit-box-shadow: 1px 1px 1px rgba(0,0,0, .29), inset 1px 1px 1px rgba(255,255,255, .44);
    -moz-box-shadow: 1px 1px 1px rgba(0,0,0, .29), inset 1px 1px 1px rgba(255,255,255, .44);
    box-shadow: 1px 1px 1px rgba(0,0,0, .29), inset 1px 1px 1px rgba(255,255,255, .44);
    -webkit-transition: all 0.15s ease;
    -moz-transition: all 0.15s ease;
    -o-transition: all 0.15s ease;
    -ms-transition: all 0.15s ease;
    transition: all 0.15s ease;
}
.button:hover {
    -webkit-box-shadow: 1px 1px 1px rgba(0,0,0,.29), inset 0px 0px 2px rgba(0,0,0, .5);
    -moz-box-shadow: 1px 1px 1px rgba(0,0,0,.29), inset 0px 0px 2px rgba(0,0,0, .5);
    box-shadow: 1px 1px 1px rgba(0,0,0,.29), inset 0px 0px 2px rgba(0,0,0, .5);
}
.green {
    color: #3e5706;
    background: #a5cd4e; /* Old browsers */
    background: -moz-linear-gradient(top,  #a5cd4e 0%, #6b8f1a 100%); /* FF3.6+ */
    background: -webkit-gradient(linear, left top, left bottom, color-stop(0%,#a5cd4e), color-stop(100%,#6b8f1a)); /* Chrome,Safari4+ */
    background: -webkit-linear-gradient(top,  #a5cd4e 0%,#6b8f1a 100%); /* Chrome10+,Safari5.1+ */
    background: -o-linear-gradient(top,  #a5cd4e 0%,#6b8f1a 100%); /* Opera 11.10+ */
    background: -ms-linear-gradient(top,  #a5cd4e 0%,#6b8f1a 100%); /* IE10+ */
    background: linear-gradient(top,  #a5cd4e 0%,#6b8f1a 100%); /* W3C */
}
.blue {
    color: #19667d;
    background: #70c9e3; /* Old browsers */
    background: -moz-linear-gradient(top,  #70c9e3 0%, #39a0be 100%); /* FF3.6+ */
    background: -webkit-gradient(linear, left top, left bottom, color-stop(0%,#70c9e3), color-stop(100%,#39a0be)); /* Chrome,Safari4+ */
    background: -webkit-linear-gradient(top,  #70c9e3 0%,#39a0be 100%); /* Chrome10+,Safari5.1+ */
    background: -o-linear-gradient(top,  #70c9e3 0%,#39a0be 100%); /* Opera 11.10+ */
    background: -ms-linear-gradient(top,  #70c9e3 0%,#39a0be 100%); /* IE10+ */
    background: linear-gradient(top,  #70c9e3 0%,#39a0be 100%); /* W3C */
}
.gray {
    color: #515151;
    background: #d3d3d3; /* Old browsers */
    background: -moz-linear-gradient(top,  #d3d3d3 0%, #8a8a8a 100%); /* FF3.6+ */
    background: -webkit-gradient(linear, left top, left bottom, color-stop(0%,#d3d3d3), color-stop(100%,#8a8a8a)); /* Chrome,Safari4+ */
    background: -webkit-linear-gradient(top,  #d3d3d3 0%,#8a8a8a 100%); /* Chrome10+,Safari5.1+ */
    background: -o-linear-gradient(top,  #d3d3d3 0%,#8a8a8a 100%); /* Opera 11.10+ */
    background: -ms-linear-gradient(top,  #d3d3d3 0%,#8a8a8a 100%); /* IE10+ */
    background: linear-gradient(top,  #d3d3d3 0%,#8a8a8a 100%); /* W3C */
}
.row {margin:0px;padding:0px}
</style>

<div class="row">
	<div class="span3">
		<h3 style="color:#46679c">Step 1 - room details</h3>
	</div>
</div>


<div class="row">
<div class="span1"></div>
	<div class='well span6'>
		<table style="width:650px">
			<tr>
				<td style="width:130px">Arrival date
					<input type="text" id="arrivedate" size="30" style="width:70px" onChange="onArrivalDateChange()"/>
				</td>
				<td style="width:150px">Departure date
					<input type="text" id="departdate" size="30" style="width:70px"/>
				</td>
                <td style="width:150px; padding-right:15px">Number of rooms
                    <select name="numRooms" id="numRooms" style="width: 50px" onchange="showTopPickLines()">
                        <option value="1" selected="selected">1</option>
                        <option value="2">2</option>
                        <option value="3">3</option>
                    </select>
                </td>
			</tr>
		</table>

		<div class="boxy">
			<table id="topPick">
		        <tbody>
		            <tr>
		                <th>Room</th>
		                <th>Adults</th>
		                <th>Children</th>
		            </tr>
		            <?php for ($i = 0; $i < $maxRooms; $i++): ?>
		            <tr id="roomLine_<?php echo ($i+1);?>" <?php if ($i>0) echo " style='display:none' ";?>   >
		                <td><?php echo ($i + 1); ?> </td>
		                <td>
		                    <select name="numAdults_<?php echo ($i+1)?>" id="numAdults_<?php echo ($i+1)?>" style="width: 50px" onchange="showTopPickLines()">
		                        <option value="1" selected="selected">1</option>
		                        <option value="2">2</option>
		                        <option value="3">3</option>
		                    </select>
		                </td>
		                <td>
		                    <select name="numChildren_<?php echo ($i+1)?>" id="numChildren_<?php echo ($i+1)?>" style="width: 50px" onchange="showTopPickLines()">
		                        <option value="0" selected="selected">0</option>
		                        <option value="1">1</option>
		                        <option value="2">2</option>
		                        <option value="3">3</option>
		                    </select>
		                </td>
			        </tr>
			        <?php endfor; ?>
		        </tbody>
		    </table>
		</div>
	</div>
<div class="span1"></div>
</div>

<div class="well span8">
<div class="row">
	<div class="span8" style="margin-left:0px">
		<table style="margin-bottom:0px;">
			<tr>
				<td width="32%"></td>
				<td width="20%">
					<a href="javascript:changeDate(-7);" class="button gray"><< week</a>
					<a href="javascript:changeDate(-1)" class="button gray">< day</a>
				</td>
				<td width="28%" style="text-align:center">View date 
					<input type="text" id="datepicker" size="30" style="width:70px" onChange="setDate()"/>
				</td>
				<td width="20%" style="text-align:right; padding-right:10px;">
					<a href="javascript:changeDate(1)" class="button gray">day ></a>
					<a href="javascript:changeDate(7)" class="button gray">week >></a>
				</td>
			</tr>
		</table>
	</div>
</div>

<div class="row">
	<div class="span8" style="margin-left:-0px">
		<table id="roomTable">
			<thead id="roomThead"></thead>
			<tbody id="roomTbody"></tbody>
		</table>
	</div>
</div>
</div>
<script>

// Show adult/child lines for however many rooms
function showTopPickLines(){
    var e = document.getElementById("numRooms");
    var rooms = e.options[e.selectedIndex].value;
    for (var i = 0; i < maxRooms; i++)
    {
    	if (i < rooms)
    		$("#roomLine_"+(i+1)).show();
    	else
    		$("#roomLine_"+(i+1)).hide();
   	}
	showRooms();
}

// Ajax call to room availability for the 14 day period displayed - all rooms at once
// Called: startup + whenever the user changes the calendar dates
function ajaxGetRoomPriceAvail()
{
	<?php
	$criteria = new CDbCriteria;
	$criteria->addCondition("uid = " . Yii::app()->session['uid']);
	$rooms = Room::model()->findAll($criteria);
	$roomList = array();
	foreach ($rooms as $room)
		array_push($roomList, $room->id);

	// @@EG Ajax (see sitecontroller.php for server side
	echo CHtml::ajax(array(
		'url'=>$this->createUrl('site/ajaxGetRoomPriceAvail'),
		'data'=>array(
			'roomList'=>$roomList,
			'date'=>'js:timeStamp'
		),
		'type'=>'POST',
		'dataType'=>'json',
 		'success' => 'function(val){ajaxShowRoomPriceAvail(val);}',
	));

	?>
}

function ajaxShowRoomPriceAvail(val) {
/*				echo CJSON::encode(array(
					'numRooms' => '2',
					'room_1' => array(
						'roomId' => 23,
						'dates' => array(1,1,1,1,1,1,1,1,1,1,1,1,1,1),
					}
					'room_2' => array(
						'roomId' => 17,
						'dates' => array(0,0,0,0,0,0,0,1,1,0,0,0,0,0)
					)
				)); */

	for (var i = 0; i < val.numRooms; i++)
	{
		var vRoomId = eval("val.room_" + i + ".roomId");
		var vDates = eval("val.room_" + i + ".dates");
		for (var j = 0; j < roomData.length; j++)
		{
			var rData = roomData[j];
			if (rData.id == vRoomId)
			{
				for (var k = 0; k < 14; k++)
					rData.avail[k] = vDates[k];
				break;
			}
		}
	}
	showDates();
	showRooms();
}

function showDates() {
	// Clear existing display
	var old_thead = document.getElementById('roomThead');
	var new_thead = document.createElement('thead');
	new_thead.id = "roomThead";
	old_thead.parentNode.replaceChild(new_thead, old_thead);
	
	var table = document.getElementById ("roomThead");

	var rowWeekDay = table.insertRow(0);
	var rowMonthDay = table.insertRow(1);
	var rowMonth = table.insertRow(2);

	var cell = rowWeekDay.insertCell(0);
	cell = rowMonthDay.insertCell(0);
	cell = rowMonth.insertCell(0);

	var d = new Date(0); // The 0 sets the date to the epoch
	d.setUTCSeconds(timeStamp);

	for (var i = 0; i < 14; i++)
	{
		// Day of week
		cell = rowWeekDay.insertCell(i+1);
		var wday = d.toString().substr(0,3);
		var className = 'cweekday';
		if ((wday == 'Sat') || (wday == 'Sun'))
			className = 'cweekend';
		cell.innerHTML = wday;
		cell.className = className;
		cell.style = 'width:5%';

		// Day of month
		cell = rowMonthDay.insertCell(i+1);
		cell.innerHTML = d.toString().substr(8,2);
		cell.className = className;

		// Month
		cell = rowMonth.insertCell(i+1);
		cell.innerHTML = d.toString().substr(4,3);
		cell.className = className;

		d.setDate(d.getDate()+1);
	}
}

function roomSelect() {
}

// Show all rooms suiting the top-box selections, whether available or not (greyed)
// Uses local data only
// Called: startup + change of room count, or adult/child per room
function showRooms() {
	// Clear existing display
	var old_tbody = document.getElementById('roomTbody');
	var new_tbody = document.createElement('tbody');
	new_tbody.id = "roomTbody";
	old_tbody.parentNode.replaceChild(new_tbody, old_tbody);
	
	var table = document.getElementById ("roomTbody");
	var line = 0;

	// For the required number of rooms...
	for (var i = 0; i < $('#numRooms').val(); i++)		// @@EG: Example of PHP yii referencing JS an html element by name 
	{
		// Set to display blocks if theyre booking multiple rooms
		var displayBlock = 1;

		// Show every available room, that suits the requirements
		for (var j = 0; j < roomData.length; j++)
		{
			var rData = roomData[j];

			// Check adults/children for this list (i)
			var ok = 1;
			var numAdults = parseInt($('#numAdults_' + (i+1)).val());
			var numChildren = parseInt($('#numChildren_' + (i+1)).val());  
			if ((numAdults > rData.max_adult) || (numChildren > rData.max_child) || ((numAdults+numChildren) > rData.max_total))
				ok = 0;
			if (ok == 1)
			{
				// Checks pass - include in the list
				
				if (displayBlock == 1)
				{
					displayBlock = 0;
					if ($('#numRooms').val() > 1)
					{
						// Room choice header line
						var row = table.insertRow(line++);
						var cell = row.insertCell(0);
    					cell.innerHTML = 'Your choices for room ' + (i+1);
						cell.className = 'roomnocell1';
						cell = row.insertCell(1);
						cell.className = 'roomnocell2';
						cell.colSpan = 14;
						cell.innerHTML = '';
					}
				}

	    		var row = table.insertRow(line++);
    			var cell = row.insertCell(0);
    			var tbl = "<table style='margin-bottom:0px'><tr><td class='roomline'>" + rData.title + "</td><td style='text-align:right;padding-right:10px'><div style='top:5px; left:-50px'>";
    			tbl += "<a href='javascript:roomSelect();' class='button blue'>Book</a>";
    			tbl += "</div></td></tr></table>";
    			cell.innerHTML = tbl;
    			for (k = 0; k < showDays; k++)
    			{
		    		var cell = row.insertCell(k+1);
    				cell.className = 'cline';
    				if ((numAdults == 1) && (numChildren == 0))
    					cell.innerHTML = rData.prices[0] == 0 ? rData.prices[2] : rData.prices[0];
	    			else if (((numAdults == 2) && (numChildren == 0)) || ((numAdults == 1) && (numChildren == 1)))
	    			{
	    				cell.innerHTML = rData.prices[1] == 0 ? (rData.prices[2] * 2) : rData.prices[1];
	    			}
	    			else
	    			{
	    				price = 0;
	    				adults = numAdults;
	    				children = numChildren;
	    				if ((adults > 1) && (rData.prices[1] > 0))	// start with double price if >2 adults, and add extra to that
	    				{
	    					price = rData.prices[1];	// double
	    					adults -= 2;
	    				}
	    				price += (adults * rData.prices[2]);	// +adult
	    				price += (children * rData.prices[3]);	// +children
	    				cell.innerHTML = price;
	    			}
	    			if (rData.avail[k] == 0) cell.innerHTML = 'Sold';
    			}
    		}
		}
	}
}

$(document).ready(function() {

	ajaxGetRoomPriceAvail();

	// 1
	$(function() {
		$( "#datepicker" ).datepicker({
	    });
	});
  
	$(function() {
		$("#datepicker").datepicker( "option", "dateFormat", "dd/mm/yy" );
		$("#datepicker").datepicker( "option", "minDate", 0 );

	});
	$('#datepicker').datepicker().datepicker('setDate',new Date());

	// 2
	$(function() {
		$( "#arrivedater" ).datepicker({
	    });
	});
	$(function() {
		$("#arrivedate").datepicker( "option", "dateFormat", "dd/mm/yy" );
		$("#arrivedate").datepicker( "option", "minDate", 0 );

	});
	$('#arrivedate').datepicker().datepicker('setDate',new Date());

	// 3
	$(function() {
		$( "#departdate" ).datepicker({
	    });
	});
	$(function() {
		$("#departdate").datepicker( "option", "dateFormat", "dd/mm/yy" );
		var v = document.getElementById("arrivedate");
		dd = v.value.substr(0, 2);
		mm = v.value.substr(3, 2);
		yyyy = v.value.substr(6, 4);
		dt = new Date(yyyy, mm-1, dd);
		dt2 = new Date(yyyy, mm-1, dt.getDate()+1);
		$('#departdate').datepicker().datepicker('setDate', dt2);
		$("#departdate").datepicker( "option", "minDate", dt2 );
	});

 });
</script>
