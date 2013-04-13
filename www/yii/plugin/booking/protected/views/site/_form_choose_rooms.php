<?php // Control variables
$maxRooms = 3; // How many rooms can be booked
$showDays = 14; // How many days to show on calendar grid
//-------------------
echo "<script>var maxRooms=" . $maxRooms . ";</script>";
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
	}
	.cweekend {
		background-color: #1F2933;
	}

</style>

<script>
function searchRooms(){
    alert('x');
}
// Show adult/child lines for however many room
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
}
</script>

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
                    <select name="numAdults_<?php echo ($i+1)?>" id="numAdults_<?php echo ($i+1)?>" style="width: 50px">
                        <option value="1" selected="selected">1</option>
                        <option value="2">2</option>
                        <option value="3">3</option>
                        <option value="4">4</option>
                        <option value="5">5</option>
                    </select>
                </td>
                <td>
                    <select name="numChildren_<?php echo ($i+1)?>" id="numChildren_<?php echo ($i+1)?>" style="width: 50px">
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
                    <?php echo CHtml::button("Search rooms",array('onclick'=>'js:searchRooms();')); ?>
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
		<div style="display:block;background-color: #fbedac; padding:10px">Choose a room</div>
	</div>
</div>

<div class="row">
	<div class="span8">
		<table>
			<thead>
			<tr>
				<th style="width:25%;"></th>
				<?php
				$str = 'today';
				$str = '+1 week';
				$str = '+1 week';
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
				<th style="width:5%"></th>
			</tr>
			<tr>
				<th style="width:25%"></th>
				<?php
				for ($i = 0; $i < $showDays; $i++)
				{
					echo "<th class='" . $dayClass . "' style='width:5%'>";
					echo date('j', $timestamp + ($i * (60 * 60 * 24)));
					echo '</th>';
				}
				?>
                <th style="width:5%"></th>
            </tr>
            <tr>
                <th style="width:25%"></th>
				<?php
				for ($i = 0; $i < $showDays; $i++)
				{
					echo "<th class='" . $dayClass . "' style='width:5%'>";
					echo date('M', $timestamp + ($i * (60 * 60 * 24)));
					echo '</th>';
				}
				?>
                <th style="width:5%"></th>
            </tr>
			</thead>

			<tbody>
			<?php
			$criteria = new CDbCriteria;
			$criteria->addCondition("uid = " . 3);
			$rooms = Room::model()->findAll($criteria);
			foreach ($rooms as $room):
			?>
			<tr>
				<td>
					<?php echo $room->title; ?>
				</td>
                    <?php
					for ($i = 0; $i < $showDays; $i++)
                    echo '<td></td>';
					?>
                <td>
                    o
                </td>
			</tr>
			<?php endforeach; ?>
			</tbody>
		</table>
	</div>
</div>
