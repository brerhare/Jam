<!--
<style>
	body {width:520px;overflow: hidden;}
	div.form .form-horizontal {width:520px;}
	#content {width:520px;}
</style>
-->

<div class="form">
	<?php $form=$this->beginWidget('bootstrap.widgets.TbActiveForm',array(
		'id'=>'room-form',
		'enableAjaxValidation'=>false,
		'type'=>'horizontal',
	)); ?>


<!-- POST variables -->
<input type="hidden" id="room_1_selection" name="room_1_selection" value="0"/>
<input type="hidden" id="room_2_selection" name="room_2_selection" value="0"/>
<input type="hidden" id="room_3_selection" name="room_3_selection" value="0"/>
<input type="hidden" id="occupancytype" name="occupancytype" value="0"/>
<input type="hidden" id="nights" name="nights" value="0"/>


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
var arrivalStamp = timeStamp;
var departureStamp = timeStamp + 86400;

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
	arrivalStamp = dt.getTime()/1000;
	departureStamp = dt2.getTime()/1000;
	ajaxGetRoomPriceAvail();
}

function onDepartureDateChange()
{
	var v = document.getElementById("departdate");
	dd = v.value.substr(0, 2);
	mm = v.value.substr(3, 2);
	yyyy = v.value.substr(6, 4);
	dt = new Date(yyyy, mm-1, dd);
	departureStamp = dt.getTime()/1000;
	var nights = Math.ceil((departureStamp - arrivalStamp) / 86400);
	document.getElementById("nights").value = nights;
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
	echo "room.bookAvail = 0;";
	echo "room.title = '" . $room->title . "';";
	echo "room.description = '" . $room->description . "';";
	echo "room.qty = '" . $room->qty . "';";
	echo "room.max_adult = '" . $room->max_adult . "';";
	echo "room.max_child = '" . $room->max_child . "';";
	echo "room.max_total = '" . $room->max_total . "';";

	// Get the default prices for this room
	$criteria = new CDbCriteria;
	$criteria->addCondition("uid = " . Yii::app()->session['uid']);
	$criteria->addCondition("room_id = " . $room->id);
	$roomHasOccupancyTypes = RoomHasOccupancyType::model()->findAll($criteria);
	$fst = 1;
	foreach ($roomHasOccupancyTypes as $roomHasOccupancyType)
	{
		$criteria = new CDbCriteria;
		$criteria->addCondition("uid = " . Yii::app()->session['uid']);
		$criteria->addCondition("id = " . $roomHasOccupancyType->occupancy_type_id);
		$occupancyType = OccupancyType::model()->find($criteria);
		if (($fst) || ($occupancyType->is_default))
		{
			$s = (float) $roomHasOccupancyType->single_rate;
			$d = (float) $roomHasOccupancyType->double_rate;
			$a = (float) $roomHasOccupancyType->adult_rate;
			$c = (float) $roomHasOccupancyType->child_rate;
			$cap = (float) $roomHasOccupancyType->cap_rate;
			$fst = 0;
		}
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

<style>
/* @@TODO Fix this to hide disabled nav buttons */
.disabled#bi#nextButton {visible:none;}
</style>

<div class="row">
	<div class="span2" style="vertical-align:middle; text-align:right">
	</div>
	<div class="span4" style="vertical-align:middle; text-align:center">
		<h3 style="color:#46679c">Step 1 - Choose your room</h3>
	</div>
	<div class="span2" style="vertical-align:middle; text-align:left">
		<?php
		$this->widget('bootstrap.widgets.TbButton',array(
			'label' => 'Next',
			'buttonType'=>'submit',
			'type' => 'primary',
			'size' => 'large',
			'htmlOptions' => array(
				'class' => 'disabled',
				'id'=> 'nextButton',
				'name' => 'nextButton',
				'onclick'=>'js:return nextButtonClick()',
			),
		));?>
	</div>
</div>

<div class="row">
<div class="span1"></div>
	<div class='well span6'>
		<table style="width:650px">
			<tr>
				<td style="width:130px">Arrival date
					<input type="text" id="arrivedate" name="arrivedate" size="30" style="width:70px" onChange="onArrivalDateChange()"/>
				</td>
				<td style="width:150px">Departure date
					<input type="text" id="departdate" name="departdate" size="30" style="width:70px" onChange="onDepartureDateChange()"/>
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
			'date'=>'js:timeStamp',
			'arrival'=>'js:arrivalStamp',
			'departure'=>'js:departureStamp',
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
						'bookAvail' => 1,
						'dates' => array(1,1,1,1,1,1,1,1,1,1,1,1,1,1),
					}
					'room_2' => array(
						'roomId' => 17,
						'bookAvail' => 0,
						'dates' => array(0,0,0,0,0,0,0,1,1,0,0,0,0,0)
					)
				)); */

	for (var i = 0; i < val.numRooms; i++)
	{
		var vRoomId = eval("val.room_" + i + ".roomId");
		var vBookAvail = eval("val.room_" + i + ".bookAvail");
		var vDates = eval("val.room_" + i + ".dates");
		for (var j = 0; j < roomData.length; j++)
		{
			var rData = roomData[j];
			if (rData.id == vRoomId)
			{
				for (var k = 0; k < 14; k++)
					rData.avail[k] = vDates[k];
				rData.bookAvail = vBookAvail;
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

function nextButtonClick() {
	classes = document.getElementById("nextButton").className;
	if (classes.indexOf('disabled') !== -1)
		return false;
	else
	{
		if (roomsAreAvailableForDates() == true)
			return true;
		else
			return false;
	}
}

// Called on 'next' button click. Check the room(s) selected are available for the chosen dates 
// @@TODO: This function is actually N/U, just returns true always
function roomsAreAvailableForDates() {
	
return true;

	ret = true;
	var e = document.getElementById("numRooms");
	var rooms = e.options[e.selectedIndex].value;
	for (var i = 0; i < rooms; i++)
	{
		var radios = document.getElementsByName('room_' + (i+1));
		for (var j = 0, length = radios.length; j < length; j++)
		{
			if (radios[j].checked)
			{
				var id = radios[j].id.split('_');
				//alert(id[2]);
			}
		}
	}
	return ret;
}

function roomRadio(roomNo, roomId)
{
	// First set the POST variable for this room
	document.getElementById("room_" + roomNo + "_selection").value = roomId;
    var e = document.getElementById("numRooms");
    var rooms = e.options[e.selectedIndex].value;
    
    // Make sure only one of each is selected
	for (var i = 0; i < rooms; i++)
	{
		searchId = 'room_' + (i+1) + '_' + roomId;
		if ((i+1) != roomNo)
		{
			if (document.getElementById(searchId).checked)
				document.getElementById(searchId).checked = false;
		}
	}

	// Check if we're all done and can activate the next button
	roomsChosen = 0;
	for (var i = 0; i < rooms; i++)
	{
		var radios = document.getElementsByName('room_' + (i+1));
		for (var j = 0, length = radios.length; j < length; j++)
		{
			if (radios[j].checked)
				roomsChosen++;
		}
	}
	if (roomsChosen == rooms)
		document.getElementById("nextButton").className = document.getElementById("nextButton").className.replace(/\bdisabled\b/,'');
}

// Show all rooms suiting the top-box selections, whether available or not (greyed)
// Uses local data only
// Called: startup + change of room count, or adult/child per room

function showRooms() {

	// Disable the next button
	document.getElementById("nextButton").className = document.getElementById("nextButton").className.replace(/\bdisabled\b/,'');
	var d = document.getElementById("nextButton");
	d.className = d.className + " disabled";

	// Clear existing display
	var old_tbody = document.getElementById('roomTbody');
	var new_tbody = document.createElement('tbody');
	new_tbody.id = "roomTbody";
	old_tbody.parentNode.replaceChild(new_tbody, old_tbody);

	var table = document.getElementById ("roomTbody");
	var line = 0;

	// For the required number of rooms...
	for (var i = 0; i < $('#numRooms').val(); i++)
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
    			if ((rData.bookAvail == 1) /*|| (1==2)*/ ) // @@TODO FIX!!!!!
    				tbl += '<input type="radio" id="' + 'room_' + (i+1) + '_' + rData.id + '" name="room_' + (i+1) + '" value="Book" onClick=roomRadio(' +  (i+1) + "," + rData.id  + ')>   <span style="font-weight:bold"> Book</span><br>';

    			tbl += "</div></td></tr></table>";
    			cell.innerHTML = tbl;
    			for (k = 0; k < showDays; k++)
    			{
		    		var cell = row.insertCell(k+1);
    				cell.className = 'cline';
    				if (k == 0)
    					cell.style.background = '#086DA4';
    				if ((numAdults == 1) && (numChildren == 0))
    					cell.innerHTML = rData.prices[0] == 0 ? rData.prices[2] : rData.prices[0];
	    			else if (((numAdults == 2) && (numChildren == 0)) || ((numAdults == 1) && (numChildren == 1)))
	    				cell.innerHTML = rData.prices[1] == 0 ? (rData.prices[2] * 2) : rData.prices[1];
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
		var nights = Math.ceil((departureStamp - arrivalStamp) / 86400);
		document.getElementById("nights").value = nights;
	});

 });
</script>


<?php $this->endWidget(); ?>
</div><!-- form -->
