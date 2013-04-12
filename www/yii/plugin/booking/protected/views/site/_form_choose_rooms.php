<style>
    table td, table th {
        padding: 0px;
    }
</style>

<script>
function onChangeRoomCount(){
    alert('x');
}
</script>


<div class="row">
	<div class="span1"></div>
	<div class='well span6'>
        <table>
        <tbody>
            <tr>
                <th>Rooms</th>
                <th></th>
                <th>Adults</th>
                <th>Children</th>
	            <th></th>
            </tr>
            <tr>
                <td>
                    <select name="numRooms" id="numRooms" style="width: 50px">
                        <option value="1" selected="selected">1</option>
                        <option value="2">2</option>
                        <option value="3">3</option>
                    </select>
                </td>
                <td>Room 1</td>
                <td>
                    <select name="numRooms" id="numAdults1" style="width: 50px">
                        <option value="1">1</option>
                        <option value="2" selected="selected">2</option>
                        <option value="3">3</option>
                        <option value="4">4</option>
                        <option value="5">5</option>
                    </select>
                </td>
                <td>
                    <select name="numRooms" id="numChildren1" style="width: 50px">
                        <option value="0">0</option>
                        <option value="1" selected="selected">1</option>
                        <option value="2">2</option>
                        <option value="3">3</option>
                        <option value="4">4</option>
                        <option value="5">5</option>
                    </select>
                </td>
                <td class="button">
                    <div class="button_button"><input type="submit" name="ctl05$ctl00$checkAvailability" value="Check Availability" onclick="javascript:WebForm_DoPostBackWithOptions(new WebForm_PostBackOptions(&quot;ctl05$ctl00$checkAvailability&quot;, &quot;&quot;, true, &quot;availability&quot;, &quot;&quot;, false, false))" id="ctl05_ctl00_checkAvailability" class="input"></div>
                </td>
	        </tr>
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
				$showDays = 14;
				$timestamp = strtotime($str);
				for ($i = 0; $i < $showDays; $i++)
				{
					echo '<th style="width:5%">';
					echo date('D', $timestamp + ($i * (60 * 60 * 24)));
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
					echo '<th style="width:5%">';
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
					echo '<th style="width:5%">';
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