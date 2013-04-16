<?php // Control variables
$maxRooms = 3; // How many rooms can be booked
$showDays = 14; // How many days to show on calendar grid
//-------------------
echo "<script>var maxRooms=" . $maxRooms . ";</script>";
echo "<script>var showDays=" . $showDays . ";</script>";
//-------------------
?>

<script>
// Store the basic details for every room at startup
<?php
$criteria = new CDbCriteria;
$criteria->addCondition("uid = " . Yii::app()->session['uid']);
$rooms = Room::model()->findAll($criteria);
echo "var roomData = [];"; // Array containing all the room objects
echo "var priceAvail = [];"; // Array containing prices for each room
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
	echo "roomData.push(room);";
}
?>
</script>

<style>
    table td, table th {
        padding: 0px;
    }
	.cweekday {
		text-align:center;
		background-color: #3d5266;
				background-color: #1F2933;
		color:#cce6ff;
        border-left:1px solid white;
        border-right:1px solid white;
        font-family:Helvetica, Arial, sans-serif;
        font-weight:100;
        font-size:13px;
	}
	.cweekend {
		background-color: #1F2933;
		background-color: #3d5266;
	}
	.cline {
		text-align:center;
		font-weight:100;
		font-size:12px;
		height:30px;
	}
	.roomnoline {
		display:block;
		background-color: #fbedac;
		padding:10px;
	}
</style>

<div class="row">
	<div class="span1"></div>
	<div class='well span6'>
        <table id="topPick">
        <tbody>
            <tr>
                <th>Rooms</th>
                <th></th>
                <th>Adults</th>
                <th>Children</th>
	            <th></th>
            </tr>
            <?php for ($i = 0; $i < $maxRooms; $i++): ?>
            <tr id="roomLine_<?php echo ($i+1);?>" <?php if ($i>0) echo " style='display:none' ";?>   >
                <td>
                <?php if ($i == 0): ?>
                    <select name="numRooms" id="numRooms" style="width: 50px" onchange="showTopPickLines()">
                        <option value="1" selected="selected">1</option>
                        <option value="2">2</option>
                        <option value="3">3</option>
                    </select>
                <?php endif;?>
                </td>
                <td>Room <?php echo ($i + 1); ?> </td>
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
                <td>
                <?php if ($i == 0): ?>
                    <?php //echo CHtml::button("Search rooms",array('onclick'=>'js:searchRooms();')); ?>
                <?php endif; ?>
                </td>
	        </tr>
	        <?php endfor; ?>
        </tbody>
	    </table>
	</div>
</div>


<div class="row">
	<div class="span8">
		<table id="roomTable">
			<thead>
			<tr>
				<th style="width:30%;"></th>
				<?php
				$str = '+1 week';
				$str = '+2 week';
				$str = 'today';
				$timestamp = strtotime($str);
				for ($i = 0; $i < $showDays; $i++)
				{
					$day = date('D', $timestamp + ($i * (60 * 60 * 24)));
					$dayClass = ' cweekday ';
					if (($day == 'Sat') || ($day == 'Sun'))
						$dayClass .= ' cweekend ';
					echo "<th class='" . $dayClass . "' style='width:5%'>";
					echo $day;
					echo '</th>';
				}
				?>
			</tr>
			<tr>
				<th style="width:30%"></th>
				<?php
				for ($i = 0; $i < $showDays; $i++)
				{
					echo "<th class='" . $dayClass . "' style='width:5%'>";
					echo date('j', $timestamp + ($i * (60 * 60 * 24)));
					echo '</th>';
				}
				?>
            </tr>
            <tr>
                <th style="width:30%"></th>
				<?php
				for ($i = 0; $i < $showDays; $i++)
				{
					echo "<th class='" . $dayClass . "' style='width:5%'>";
					echo date('M', $timestamp + ($i * (60 * 60 * 24)));
					echo '</th>';
				}
				?>
            </tr>
			</thead>

			<tbody id="roomTbody">
			</tbody>
		</table>
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

$(document).ready(function() {
	ajaxGetRoomPriceAvail();
	showRooms();
 });

// Ajax call to get room availability for the 14 day period displayed
// Called: startup + whenever the user changes the calendar dates
function ajaxGetRoomPriceAvail()
{
	<?php
	$criteria = new CDbCriteria;
	$criteria->addCondition("uid = " . Yii::app()->session['uid']);
	$rooms = Room::model()->findAll($criteria);
	foreach ($rooms as $room)
	{
		// @@EG Ajax (see sitecontroller.php for server side
		echo CHtml::ajax(array(
			'url'=>$this->createUrl('site/ajaxGetRoomPriceAvail'),
			'data'=>array(
				'roomId'=>$room->id,
				'date'=>$timestamp
			),
			'type'=>'POST',
			'dataType'=>'json',
 			'success' => 'function(val){alert(val[0]);ajaxShowRoomPriceAvail(val);}',
		));
	}
	?>
}

function ajaxShowRoomPriceAvail(val) {
	a = val.length;
	alert(a);
	
	//alert(val[0]);
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
	{40	
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
						var row = table.insertRow(line++);
						var cell = row.insertCell(0);
    					cell.innerHTML = 'Select room ' + (i+1);
						cell.className = 'roomnoline';
					}
				}

	    		var row = table.insertRow(line++);
    			var cell = row.insertCell(0);
    			cell.innerHTML = rData.title;
    			for (k = 0; k < showDays; k++)
    			{
		    		var cell = row.insertCell(k+1);
    				cell.className = 'cline';
    				if ((numAdults == 1) && (numChildren == 0))
    					cell.innerHTML = rData.prices[0] == 0 ? rData.prices[2] : rData.prices[0];
	    			else if ((numAdults == 2) && (numChildren == 0))
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
    			}
    		}
		}
	}
}
 
</script>
