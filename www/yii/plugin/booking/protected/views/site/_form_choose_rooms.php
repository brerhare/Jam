<?php // Control variables
$maxRooms = 3; // How many rooms can be booked
$showDays = 14; // How many days to show on calendar grid
//-------------------
echo "<script>var maxRooms=" . $maxRooms . ";</script>";
echo "<script>var showDays=" . $showDays . ";</script>";
//-------------------
?>
	
<style>
    table td, table th {
        padding: 0px;
    }
	.cweekday {
		text-align:center;
		background-color: #3d5266;
		color:#cce6ff;
        border-left:1px solid white;
        border-right:1px solid white;
        font-family:Helvetica, Arial, sans-serif;
        font-weight:100;
        font-size:13px;
	}
	.cweekend {
		background-color: #1F2933;
	}
	.cline {
		text-align:center;
		font-weight:100;
		font-size:12px;
		height:30px;
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
                        <option value="4">4</option>
                        <option value="5">5</option>
                    </select>
                </td>
                <td>
                    <select name="numChildren_<?php echo ($i+1)?>" id="numChildren_<?php echo ($i+1)?>" style="width: 50px" onchange="showTopPickLines()">
                        <option value="0" selected="selected">0</option>
                        <option value="1">1</option>
                        <option value="2">2</option>
                        <option value="3">3</option>
                        <option value="4">4</option>
                        <option value="5">5</option>
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

<div class="row">
	<div class="span8">
		<div style="display:block;background-color: #fbedac; padding:10px">Choose a room</div>
	</div>
</div>

<script>

function ajaxGetRoomData() {
<?php // @@EG Ajax (see sitecontroller.php for server side
	echo CHtml::ajax(array(
		'url'=>$this->createUrl('site/ajaxTest'),
		'data'=>array(
			'numRooms'=>'js:$(\'#numRooms\').val()',
			'room1Adults'=>'js:$(\'#numAdults_1\').val()',
			'room2Adults'=>'js:$(\'#numAdults_2\').val()',
			'room3Adults'=>'js:$(\'#numAdults_3\').val()',
			'room1Children'=>'js:$(\'#numChildren_1\').val()',
			'room2Children'=>'js:$(\'#numChildren_2\').val()',
			'room3Children'=>'js:$(\'#numChildren_3\').val()',
			'date'=>date('Y-m-d', $timestamp),
		),
		'type'=>'POST',
		'dataType'=>'json',
 		'success' => 'function(val){ajaxShowRoomData(val);}',
	));
?>
}

function ajaxShowRoomData(val)
{
	//alert(val.room_1.title);
	//alert(val.room2.price.p101);

	// Wipe existing contents
	var old_tbody = document.getElementById('roomTbody');
	var new_tbody = document.createElement('tbody');
	new_tbody.id = "roomTbody";
	old_tbody.parentNode.replaceChild(new_tbody, old_tbody);

    var table = document.getElementById ("roomTbody");
    for (i = 0; i < $('#numRooms').val(); i++)		// @@EG JQuery: easy way to get the selected <select><option> 
    {
    	var row = table.insertRow(i);
    	var cell = row.insertCell(0);
    	cell.innerHTML = eval('val.room_' + (i+1) + '.title'); 
    	for (j = 0; j < showDays; j++)
    	{
    		var cell = row.insertCell(j+1);
    		cell.className = 'cline';
    		cell.innerHTML = '75'; 
    	}
	}
	alert(length(val));


	/*				$roomData = array(
						'room1'=>array(
							'title'=>'R1',
							'price'=>array(
								'p1'=>'11',
								'p2'=>'22',
								'p3'=>'33'
							 ),
						),
						'room2'=>array(
							'title'=>'R2',
							'price'=>array(
								'p101'=>'1100',
								'p102'=>'2200',
								'p103'=>'3003'
							 ),
						),
					);*/
					
    //alert(val.name2browser);
    //    alert(val.status);
}

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
   	ajaxGetRoomData();
}

$(document).ready(function() {
   ajaxGetRoomData();
 });
 
</script>
