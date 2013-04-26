<div class="form">
	<?php $form=$this->beginWidget('bootstrap.widgets.TbActiveForm',array(
		'id'=>'ticket-form',
		'enableAjaxValidation'=>false,
		'type'=>'horizontal',
	)); ?>

	<div class="row">
		<div class="span5 well">
			<table>
				<tr>
					<td width="25%">
<?php Yii::log("TICKET FORM : image " . Yii::app()->session['uid'], CLogger::LEVEL_WARNING, 'system.test.kim'); ?>
						<?php echo CHtml::image(Yii::app()->baseUrl . '/userdata/' . Yii::app()->session['uid'] . '/' . $model->ticket_logo_path,
							'My Image Name',
							array('style'=>'height:80px;'));
						?>
					</td>
					<td width="75%">
						<b><?php echo $model->title; ?></b>
						<br>
						<i><?php echo $model->date; ?></i>
					</td>
				</tr>
			</table>
		</div>
	</div>

<!--- TICKET DIV ---------------------------------------------------------------------------------------------------->

<style>

.row {
	margin-left: 3px;
}
table td, table th {
	padding: 0px 10px 0px 10px;
}
.hide {
	display:none;
}
.show {
	display:visible;
}
</style>

<script>
var lc = 0;
var numTickets = 0;
function calcValues()
{
	var total = 0;
	numTickets = 0;
	for (var x = 0; x < lc; x++)
	{
		var linePrefix = 'line_' + x + '_';
		price = parseFloat(document.getElementById(linePrefix + 'price').innerText);

		var e = document.getElementById(linePrefix + 'select');
		var num = parseInt(e.options[e.selectedIndex].value);
		
		numTickets += num;

		lineTotal = price * num;
		document.getElementById(linePrefix + 'total').innerText = lineTotal.toFixed(2);
		document.getElementById('p'+linePrefix + 'total').value = lineTotal.toFixed(2);
		
		total += lineTotal;
	}
	document.getElementById('total').innerText = '£ ' + total.toFixed(2);
	document.getElementById('ptotal').value = total.toFixed(2);
}

$(document).ready(function() {
    calcValues();
 });
 
</script>

<div class="row">
	<table>	
		<?php
		$lc = 0;
		$criteria = new CDbCriteria;
		$criteria->addCondition("ticket_event_id = " . $model->id);
		$criteria->addCondition("uid = " . Yii::app()->session['uid']);
		$areas = Area::model()->findAll($criteria);
		foreach ($areas as $area):
		?>
			<tr style="background-color:#745882; color:#ffffff">
				<td colspan="2">
					<h5><?php echo $area->description;?></h5>
				</td>
				<td>
				Tickets
				</td>
				<td style="text-align:right">
				Price Each
				</td>
				<td style="text-align:right">
				Line Total
				</td>
			</tr>
			<?php
			$criteria = new CDbCriteria;
			$criteria->addCondition("ticket_area_id = " . $area->id);
			$criteria->addCondition("uid = " . Yii::app()->session['uid']);
			$ticketTypes = TicketType::model()->findAll($criteria);
			foreach ($ticketTypes as $ticketType):
				$linePrefix = "line_" . $lc++ . "_";
			?>
			<tr style="background-color:#EDE4F2">
				<!-- blank -->
				<td width="10%">
				<?php $tmp = $linePrefix . "id_" . $ticketType->id; ?>
				<?php echo "<div id='" . $tmp . "'><div>";?>
				<?php echo "<input type='hidden' [id='p" . $linePrefix . "id' name='p" . $linePrefix . "id' ] value='" . $ticketType->id . "' />";?>
				<?php echo "<input type='hidden' [id='p" . $linePrefix . "area' name='p" . $linePrefix . "area' ] value='" . $area->id . "' />";?>
				</td>
				<!-- ticket type -->
				<td width="40%">
					<?php echo $ticketType->description;?>
				</td>
				<!-- num -->
				<td width="10%">
					<?php
					$select=0;
					$arr = array();
					$arrNum = $ticketType->max_tickets_per_order;
					if ($arrNum == 0)
						$arrNum = 9;
					for ($x = 0; $x <= $arrNum; $x++)
						array_push($arr, $x);
					?>
					<?php echo CHtml::dropDownList($linePrefix . 'select', $select, $arr, array('style'=>'width:55px', 'onchange'=>'calcValues()'));?>
				</td>
				<!-- unit price -->
				<td width="20%" style="text-align:right">
					<?php echo "<div id='" . $linePrefix . "price'>" . $ticketType->price . "</div>";?>
					<?php echo "<input type='hidden' id='p" . $linePrefix . "price' name='p" . $linePrefix . "price' value='" . $ticketType->price. "' />";?>
				</td>
				<!-- line total -->
				<td width="20%"  style="text-align:right">
					<?php echo "<div id='" . $linePrefix . "total'></div>";?>
					<?php echo "<input type='hidden' id='p" . $linePrefix . "total' name='p" . $linePrefix . "total' value='0' />";?>
				</td>
			</tr>
			<?php endforeach;?>
		<?php endforeach;
		echo "<script>lc=$lc;</script>";
		?>
		<!-- grand total -->
		<tr>
			<td colspan="3">
			</td>
			<td  style="text-align:right; background-color:#745882; color:#ffffff">
				<h5>Total Payable</h5>
			</td>
			<td style="text-align:right; background-color:#745882; color:#ffffff">
				<?php echo "<div id='total'></div>";?>
				<?php echo "<input type='hidden' id='ptotal' name='ptotal' value='0' />";?>
			</td>
		</tr>
	</table>
</div>

<div class="span5 well">
	<table>
		<tr class="row">
			<td style="text-align:right">
				Email Address
			</td>
			<td>
				<input id="email1" name="email1" value="" class="" MaxLength="50" />
			</td>
		</tr>
		<tr class="row">
			<td style="text-align:right">
				Again
			</td>
			<td>
				<input id="email2" name="email2" value="" class="" MaxLength="50" />
			</td>
		</tr>
		<tr class="row">
			<td style="text-align:right">
				Telephone Number
			</td>
			<td>
				<input id="telephone" name="telephone" value="" class="" MaxLength="50" />
			</td>
		</tr>
	</table>
</div>

<div style="float:right">
<?php
$this->widget('zii.widgets.jui.CJuiButton', array(
	'name'=>'submit',
	'caption'=>'Continue',
	'htmlOptions' => array('class'=>'ui-button-success'),
	'onclick'=>new CJavaScriptExpression(
		'function(){

			var err = "";
			if (numTickets < 1)
				err += "No tickets selected\n";

			var email1 = document.getElementById("email1").value;
			var email2 = document.getElementById("email2").value;

			if ((email1 != email2) || (!email1) || (email1.indexOf(".") == -1) || (email1.indexOf("@") == -1))
				err += "Invalid email\n";

			if (err != "")
			{
				alert(err);
				return false;
			}
		}'
	),
));
?>
</div>
<!-- <a href=<?php echo Yii::app()->request->baseUrl . '/';?>00002.pdf>PDF HERE </a> -->


<!--
	<div class="row">
		<input id="email2" value="" class="" MaxLength="50" />
	</div>
	<div class="row">
		<input name="phone" value="" class="InputTextField" MaxLength="15" style="width:100px"/>
	</div>
-->

	<?php $this->endWidget(); ?>
</div><!-- form -->