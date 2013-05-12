<div class="form">
	<?php $form=$this->beginWidget('bootstrap.widgets.TbActiveForm',array(
		'id'=>'room-form',
		'enableAjaxValidation'=>false,
		'type'=>'horizontal',
	)); ?>

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

<!-- POST variables -->
<input type="hidden" id="bTotal" name="bTotal" value="9"/>

<!-- Accessible value -->
<input type="hidden" id="nights" name="nights" value="<?php echo Yii::app()->session['nights']?>"/>

<script>
// Some globals
var total = 0;	// The booking total price
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

.roomline {
color: #46679c;
font-weight: bold;
}
.roomsubline {
color: #46679c;
}

</style>

<div class="row">
	<div class="span2" style="vertical-align:middle; text-align:right">
		<?php
		$this->widget('bootstrap.widgets.TbButton',array(
			'label' => 'Back',
			'buttonType'=>'submit',
			'type' => 'primary',
			'size' => 'large',
			'htmlOptions' => array(
				//'class' => 'disabled',
				'id'=> 'backButton',
				'name' => 'backButton',
				//'onclick'=>'js:return backButtonClick()',
			),
		));?>
	</div>
	<div class="span4" style="vertical-align:middle; text-align:center">
		<h3 style="color:#46679c">Step 2 - Choose room options</h3>
	</div>
	<div class="span2" style="vertical-align:middle; text-align:left">
		<?php
		$this->widget('bootstrap.widgets.TbButton',array(
			'label' => 'Next',
			'buttonType'=>'submit',
			'type' => 'primary',
			'size' => 'large',
			'htmlOptions' => array(
				//'class' => 'disabled',
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
				<td style="width:130px">
					Arriving <?php echo Yii::app()->session['arrivedate']; ?> and Departing <?php echo Yii::app()->session['departdate']; ?>
				</td>
			</tr>
		</table>

		<?php for ($roomIx = 0; $roomIx < Yii::app()->session['numRooms']; $roomIx++):?>

		<div class="boxy">

			<?php
			// Show the room title and description
			$criteria = new CDbCriteria;
			$criteria->addCondition("id = " . Yii::app()->session['room_' . ($roomIx+1) . '_selection']);
			$criteria->addCondition("uid = " . Yii::app()->session['uid']);
			$room=Room::model()->find($criteria);
			echo "<div class='roomline'>" . $room->title . "</div>";
			echo "<div class='roomsubline'>" . $room->description . "</div>";

			$adults = Yii::app()->session['numAdults_' . ($roomIx+1)];
			$children = Yii::app()->session['numChildren_' . ($roomIx+1)];

			$adultStr = ' adult';
			if ($adults > 1) $adultStr = ' adults';
			if ($children == 0) $childStr = "";
			else if ($children == 1) $childStr = " and 1 child" ;
			else $childStr = " and " . $children . " children" ;
			$headCount = $adults . $adultStr . $childStr;
			?>

			<table id="topPick" border=1>
		        <Xthead>
		            <tr>
		                <th style="width:80%; padding:5px;"><?php echo $headCount;?></th>
		                <th style="width:20%; text-align:right;">Price</th>
		            </tr>
		        </Xthead>
		        <tbody>
					<?php

					// Room portion of the order

					$occupancyTypeIx = 0;
					$criteria = new CDbCriteria;
					$criteria->addCondition("uid = " . Yii::app()->session['uid']);
					$criteria->addCondition("room_id = " . $room->id);
					$roomHasOccupancyTypes=RoomHasOccupancyType::model()->findAll($criteria);
					foreach ($roomHasOccupancyTypes as $roomHasOccupancyType):
						$criteria = new CDbCriteria;
						$criteria->addCondition("uid = " . Yii::app()->session['uid']);
						$criteria->addCondition("id = " . $roomHasOccupancyType->occupancy_type_id);
						$occupancyType=OccupancyType::model()->find($criteria);
						echo "<tr>";
						echo " <td>";

						// Calculate price for this occupancy type based on numAdults and numChildren
		    			$price = 0;
		    			$adultCount = $adults;
		    			$childrenCount = $children;
    					if (($adultCount == 1) && ($childrenCount == 0))
    						$price = $roomHasOccupancyType->single_rate == 0 ? $roomHasOccupancyType->adult_rate : $roomHasOccupancyType->single_rate;
	    				else if ((($adultCount == 2) && ($childrenCount == 0)) || (($adultCount == 1) && ($childrenCount == 1)))
	    					$price = $roomHasOccupancyType->double_rate == 0 ? ($roomHasOccupancyType->adult_rate * 2) : $roomHasOccupancyType->double_rate;
		    			else
	    				{
	    					if (($adultCount > 1) && ($roomHasOccupancyType->double_rate > 0))	// start with double price if >2 adults, and add extra to that
	    					{
	    						$price = $roomHasOccupancyType->double_rate;	// double
		    					$adultCount -= 2;
		    				}
	    					$price += ($adultCount * $roomHasOccupancyType->adult_rate);	// +adult
	    					$price += ($childrenCount * $roomHasOccupancyType->child_rate);	// +children
	    				}
						$occupancyType->is_default ? $checked = " checked " : $checked = "";
						echo '  <input type="radio" id="' . 'room_' . ($roomIx+1) . '_' . $room->id . '" name="room_' . ($roomIx+1) . '_' . $room->id . '" value="' . $price . '"' . $checked . ' onClick=roomRadio(' .  ($roomIx+1) . "," . $room->id  . "," . $price . "," . ($occupancyTypeIx+1) . ')>   <span style="font-weight:normal">' . $occupancyType->description . '</span> (£' . $price . ')<br>';
						echo " </td>";
						echo " <td id='roomprice_" . ($roomIx+1) . '_' . ($occupancyTypeIx+1) . "' style='text-align:right'>";
						if ($occupancyType->is_default)
							echo sprintf("%.2f", $price);
						echo "</td>";
						echo "</tr>";
						$occupancyTypeIx++;
					endforeach;	

					// Extras portion of the order

					$extraTypeIx = 0;
					$criteria = new CDbCriteria;
					$criteria->addCondition("uid = " . Yii::app()->session['uid']);
					$criteria->addCondition("room_id = " . $room->id);
					$roomHasExtras=RoomHasExtra::model()->findAll($criteria);
					$roomExtraCount = 0;
					foreach ($roomHasExtras as $roomHasExtra):
						$criteria = new CDbCriteria;
						$criteria->addCondition("uid = " . Yii::app()->session['uid']);
						$criteria->addCondition("id = " . $roomHasExtra->extra_id);
						$extra=Extra::model()->find($criteria);
						if ($roomExtraCount == 0)
						{
							echo "<tr>";
							echo "<td>";
							echo "<hr>";
							echo "<b>Extra items are available for this room</b><br>";
							echo "</td></tr>";
						}
						echo "<tr>";
						echo "<td>";
							$extraVal = $extra->once_rate;
							$extraStr = " (" . $extra->once_rate . ")";
							if ($extra->daily_rate != "0.00")
							{
								$extraVal = $extra->daily_rate . "pd";
								$extraStr = " (" . $extra->daily_rate . " per day)";
							}
							echo "<input type='checkbox' onClick='extraCheckbox(" . $room->id . "," . $extra->id . ")' name='extra_roomid_" . $room->id . "_extraid_" . $extra->id . "' id='extra_roomid_" . $room->id . "_extraid_" . $extra->id . "' value='" . $extraVal . "'>";
							echo " " . $extra->description;
							echo $extraStr;
							//echo $extra->daily_rate;
							//echo $extra->once_rate;
						echo "</td>";
						echo "<td name='extra_roomid_" . $room->id . "_extraid_" . $extra->id . "_value' id='extra_roomid_" . $room->id . "_extraid_" . $extra->id . "_value' style='text-align:right;'>";
						echo "</td>";
						echo "</tr>";
						$roomExtraCount++;
					endforeach;

					echo "<script> var occupancyTypeMaxIx = " . $occupancyTypeIx . ";</script>";

					?>
		        </tbody>
		    </table>
		</div>

		<?php endfor;?>

		<?php echo "<script> var roomMaxIx = " . $roomIx . ";</script>"; ?>

		<div class="boxy">
			<table>
		        <tr>
		            <td style="width:80%; padding:5px; text-align:right"><b>Total</b></td>
		            <td id="bookingTotal" name="bookingTotal" style="width:20%; text-align:right;"></td>
		         </tr>
			</table>
		</div>

	</div>
<div class="span1"></div>
</div>

<script>
function roomRadio(roomNo, roomId, price, occupancyTypeIx) {
	for (var i = 0; i < occupancyTypeMaxIx; i++)
		document.getElementById('roomprice_' + roomNo + '_' + (i+1)).innerHTML = '';
	document.getElementById('roomprice_' + roomNo + '_' + occupancyTypeIx).innerHTML = price.toFixed(2);
	calcTotal();
}

function extraCheckbox(room_id, extra_id) {
	var item = document.getElementById('extra_roomid_' + room_id + '_extraid_' + extra_id);
	var itemValue = document.getElementById('extra_roomid_' + room_id + '_extraid_' + extra_id + '_value');
	if (item.checked)
		itemValue.innerHTML = calcCheckbox(item.value).toFixed(2);
	else
		itemValue.innerHTML = '';
	calcTotal();
}

function calcCheckbox(string)
{
	val = parseFloat(string);
	if (string.substr(-2) == 'pd')
		val = (string.substr(0, (string.length-2)) * parseFloat(document.getElementById('nights').value));
	return val;
}

function calcTotal()
{
	total = 0;
	// Add up the room prices
	for (var room = 0; room < roomMaxIx; room++)
	{
		for (var occ = 0; occ < occupancyTypeMaxIx; occ++)
		{
			val = document.getElementById('roomprice_' + (room+1) + '_' + (occ+1)).innerHTML;
			if (val != '')
				total += (parseFloat(val));
		}
	}
	// Add up the extras
	inputs = document.getElementsByTagName('input');
	for (index = 0; index < inputs.length; ++index)
	{
		var fld = inputs[index].id; 
		if (fld.substr(0,6)=='extra_')
		{
			if (document.getElementById(fld).checked)
			{
				total += calcCheckbox(document.getElementById(fld).value);
			}
    	}
	}
	document.getElementById('bookingTotal').innerHTML = "<b>£ " + total.toFixed(2) + "</b>";
	document.getElementById('bTotal').value = total.toFixed(2);
}

function nextButtonClick() {
	classes = document.getElementById("nextButton").className;
	if (classes.indexOf('disabled') !== -1)
		return false;
	else
		return true;
}

$(document).ready(function() {
	calcTotal();
})

</script>

<!-------------------------------------------------------------------->
<?php
echo "<p>";
				foreach (Yii::app()->session as $field => $value)
				{
					echo $field . ":" . $value . "<br>";
					//Yii::app()->session[$field] = $value;
					//Yii::log("GIVING INDEX2 VALUES FOR " . Yii::app()->session[$field] . " = " . $value , CLogger::LEVEL_WARNING, 'system.test.kim');
				}
?>
<!-------------------------------------------------------------------->


<?php $this->endWidget(); ?>
</div><!-- form -->
