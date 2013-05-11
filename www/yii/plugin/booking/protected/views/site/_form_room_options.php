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
		<h3 style="color:#46679c">Step 2 - Choose your options</h3>
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

			$adult = ' adult';
			if (Yii::app()->session['numAdults_' . ($roomIx+1)] > 1)
				$adult = 'adults';
			if (Yii::app()->session['numChildren_' . ($roomIx+1)] == 0)
				$child = "";
			else if (Yii::app()->session['numChildren_' . ($roomIx+1)] == 1)
				$child = " and 1 child" ;
			else
				$child = " and " . Yii::app()->session['numChildren_' . ($roomIx+1)] . " children" ;
			$headCount = Yii::app()->session['numAdults_' . ($roomIx+1)] . $adult . $child;
			?>

			<table id="topPick" border=1>
		        <Xthead>
		            <tr>
		                <th style="width:80%; padding:5px;"><?php echo $headCount;?></th>
		                <th style="width:20%"></th>
		            </tr>
		        </Xthead>
		        <tbody>
					<?php
					$criteria = new CDbCriteria;
					$criteria->addCondition("uid = " . Yii::app()->session['uid']);
					$occupancyTypes=OccupancyType::model()->findAll($criteria);
					foreach ($occupancyTypes as $occupancyType):
						echo "<tr>";
						echo " <td>";
						echo '  <input type="radio" id="' . 'room_' . ($roomIx+1) . '_' . $room->id . '" name="room_' . ($roomIx+1) . '" value="' . $occupancyType->id . '" onClick=roomRadio(' .  ($roomIx+1) . "," . $room->id  . ')>   <span style="font-weight:normal">' . $occupancyType->description . '</span><br>';
						echo " </td>";
						echo " <td>";
						echo " </td>";
						echo "</tr>";
					endforeach;
					?>
		        </tbody>
		    </table>
		</div>

<?php endfor;?>

	</div>
<div class="span1"></div>
</div>


<script>
function nextButtonClick() {
	classes = document.getElementById("nextButton").className;
	if (classes.indexOf('disabled') !== -1)
		return false;
	else
		return true;
}
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
