<?php $form=$this->beginWidget('bootstrap.widgets.TbActiveForm',array(
	'id'=>'report-form',
	'type'=>'horizontal',
	'enableAjaxValidation'=>false,
	'htmlOptions'=>array('enctype'=>'multipart/form-data'),
)); ?>

<?php
$vendorName = "Missing vendor";
$criteria = new CDbCriteria;
$criteria->addCondition("uid = " . Yii::app()->session['uid']);
$vendor = Vendor::model()->find($criteria);
if ($vendor)
	$vendorName = $vendor->name;
$ticketArray = array();
$valueArray = array();
?>

<?php if (!($model)) { 
$f = $fromD;
$t = $toD;
$fChk = $f['year'] . $f['mon'] . $f['mday'];
$tChk = $t['year'] . $t['mon'] . $t['mday'];
//echo "<br>[". $fChk . "-" . $tChk . "]<br>";
?>
<!------------------------------------------ @@EG: dropdown date starts ------------------------------------------->
    <script type="text/javascript" src="/js/dropdownDate.js"></script>
    <style>
        span#startDate select {width:70px; margin-right:5px; margin-top:0px;}
        span#endDate select {width:70px; margin-right:5px; margin-top:0px;}
    </style>

	<input name="start" id="Event_start" type="hidden" value="<?php echo $f['mday'] . "-" . $f['mon'] . "-" . $f['year'];?>"/>
	<span> <b>Displaying From</b></span> <span id='startDate'></span>
	<input name="end" id="Event_end" type="hidden" value="<?php echo $t['mday'] . "-" . $t['mon'] . "-" . $t['year'];?>" />
	&nbsp&nbsp&nbsp <span><b>To</b></span> <span id='endDate'></span>

	&nbsp&nbsp&nbsp
	<?php $this->widget('bootstrap.widgets.TbButton', array(
		'buttonType'=>'submit',
		'type'=>'primary',
		'label'=>'Apply',
	)); ?>

    <script>
        dropdownDate('startDate', 'Event_start', 'dd-mm-yyyy');
        dropdownDate('endDate', 'Event_end', 'dd-mm-yyyy');
    </script>
<!-------------------------------------------- dropdown date ends ------------------------------------------------>
<?php } ?>

<?php
if ($model)
	echo "<h4>" . $model->title . "    -   " . $model->date . "</h4>";
?>
</h4>

<style>
#content { padding:0px}
.background1 { background-color:#d7e3f9; }
.background2 { background-color:#ffffff; }
table td, table th {
	padding: 0;
}
table tr {
	background-color:#f8f8f8;
	padding: 2px;
}
</style>

<div class="row">
<div class="span12">
	<table id="reportTable">
		<tr style="background-color:#c3d9ff; color:#0088cc;">
			<?php if ($model) echo "<td>Resend</td>";?>
			<?php if (!($model)) echo "<td width=220px>Event</td>";?>
			<td style="text-align:left">Date</td>
			<td><?php if ($model) echo "Order Number";?></td>
			<td>Name</td>
			<td>Phone</td>
			<td>Post Code</td>
			<td>Area</td>
			<td>Ticket</td>
			<td style="text-align:right">No</td>
			<td style="text-align:right; width:70px">£</td>
		</tr>
		<?php
		$prevOrder = "";
		$ticketTotal = 0;
		$valueTotal = 0;
		$lc = 0; 
		$criteria = new CDbCriteria;
		if ($model)
			$criteria->addCondition("event_id = " . $model->id);
		$criteria->addCondition("uid = " . Yii::app()->session['uid']);
		$criteria->order = 'order_number DESC';

		$curOrder = '';
		$orderArr = array();
		$typeArr = array();

		$transactions = Transaction::model()->findAll($criteria);
		foreach ($transactions as $transaction):

			if (!($model))
			{
				$date = $transaction->timestamp;
				$chk = sprintf("%04s%02s%02s", substr($date,0,4),substr($date,5,2),substr($date,8,2));
				if (($chk < $fChk) || ($chk > $tChk))
					continue;
			
				$criteria = new CDbCriteria;
				$criteria->addCondition("id = " . $transaction->event_id);
				$event = Event::model()->find($criteria);
				$eventTitle = "*Missing*";
				if ($event)
					$eventTitle = $event->title;

			}
			// Check for dups. Cant have more than one record of the same ticket type per order
			//if ($curOrder != $transaction->order_number)
			//{
				//unset($orderArr);
				//$orderArr = array();
				//unset($typeArr);
				//$typeArr = array();
				//$curOrder = $transaction->order_number;
			//}
			//if ((in_array($transaction->order_number, $orderArr)) && (in_array($transaction->http_ticket_type_id, $typeArr)))
				//continue;
			array_push($orderArr, $transaction->order_number);
			array_push($typeArr, $transaction->http_ticket_type_id);

		 	$criteria = new CDbCriteria;
			//$criteria->addCondition("uid = " . Yii::app()->session['uid']);
			$criteria->addCondition("order_number = '" . $transaction->order_number . "'");
			$auth = Auth::model()->find($criteria);
			if (!($auth))
				continue;

            // Suppress values for non-paymentsense tickets
            if (!($transaction->auth_code))
                $transaction->http_ticket_total = 0;

			if ($prevOrder != $transaction->order_number)
			$lc++; 
			$class = ($lc%2 == 0)? 'background1': 'background2';
		?>
		<?php echo "<tr class=" . $class . ">" ?>
			<?php if ($prevOrder != $transaction->order_number) : ?>

			<td>
			<?php if ($model)
				echo '<a title="Email this customers tickets" href="' . Yii::app()->createUrl('event/remailConfirm', array('id' => $transaction->id, 'name' => $auth->card_name))  . '" target="_blank"><img src="/ticket/img/email.png" border="0" ></a>';
			else
				echo $eventTitle;
			?>
			</td>

			<td>
				<?php
				$date = $transaction->timestamp;
				echo sprintf("%02s/%02s/%02s", substr($date,8,2),substr($date,5,2),substr($date,2,2));
				?>
			</td>
			<td>
				<?php if ($model)
					 echo '<a title="View this customers tickets" href="' . Yii::app()->baseUrl . '/tkts/' . $transaction->order_number . '.pdf">' . $transaction->order_number . '</a>';
				?>
			</td>
			<td>
				<?php if ($auth) echo substr($auth->card_name, 0, 30); else echo 'Name not available';?>
			</td>
			<td>
				<?php echo $transaction->telephone;?>
			</td>
			<td>
				<?php if ($auth) echo $auth->post_code; ?>
			</td>
<?php else: ?>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
<?php endif; ?>
			<td>
				<?php
				$criteria = new CDbCriteria;
				$criteria->addCondition("id = " . $transaction->http_area_id);
				$criteria->addCondition("uid = " . Yii::app()->session['uid']);
				$area = Area::model()->find($criteria);
				echo substr($area->description, 0, 30);
				?>
			</td>
			<td>
				<?php
				$criteria = new CDbCriteria;
				$criteria->addCondition("id = " . $transaction->http_ticket_type_id);
				$criteria->addCondition("uid = " . Yii::app()->session['uid']);
				$ticketType = TicketType::model()->find($criteria);
				echo substr($ticketType->description, 0, 30);
				?>
			</td>
			<td style="text-align:right">
				<?php
				echo $transaction->http_ticket_qty;
				$ticketTotal += $transaction->http_ticket_qty;
				if (!(array_key_exists($ticketType->description, $ticketArray)))
				{
					$ticketArray[$ticketType->description] = 0;
					$valueArray[$ticketType->description] = 0;
				}
				$ticketArray[$ticketType->description] += $transaction->http_ticket_qty;
				$valueArray[$ticketType->description] += $transaction->http_ticket_total;
				?>
			</td>
			<td style="text-align:right">
				<?php
				if ($transaction->http_ticket_total != 0)
					echo $transaction->http_ticket_total;
				$valueTotal += $transaction->http_ticket_total;
				?>
			</td>
		</tr>
		<?php $prevOrder = $transaction->order_number; ?>
		<?php endforeach;?>

		<tr style="background-color:#c3d9ff; color:#0088cc;">
			<td>Totals</td>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
			<td style="text-align:right"><?php echo $ticketTotal?></td>
			<td style="text-align:right; width:70px"><?php echo number_format((float)$valueTotal, 2, '.', '')?></td>
		</tr>

	</table>
</div>
</div>

<script>
$(document).ready(function() {
	var table = document.getElementById("reportTable");
	<?php
	$lc = 0;
	$totalQ = 0;
	$totalV = 0;
	foreach ($ticketArray as $k => $v):
		echo "var row = table.insertRow(" . $lc++ . ");\n";
		for ($x = 0; $x < 7; $x++)
			echo "row.insertCell(" . $x . ");";
		echo "\n";

		echo "var ticketType = row.insertCell(7);\n";
		$clean = str_replace('"', "'", $k);
		echo 'ticketType.innerHTML = "' . substr($clean, 0, 30) . '";' . "\n";

		echo "var ticketQ = row.insertCell(8);\n";
		echo 'ticketQ.innerHTML = "' . $v . '";' . "\n";
		echo 'ticketQ.style.textAlign = "' . 'right' . '";' . "\n";

		echo "var ticketV = row.insertCell(9);\n";
		echo 'ticketV.innerHTML = "' . number_format((float)$valueArray[$k], 2, '.', '') . '";' . "\n";
		echo 'ticketV.style.textAlign = "' . 'right' . '";' . "\n";

		$totalQ += $v;
		$totalV += $valueArray[$k];
	endforeach;
		echo "var row = table.insertRow(" . $lc++ . ");\n";
		echo "dummy = row.insertCell(0);";
		echo 'dummy.innerHTML = "&nbsp";';

		// Finally the total at the top
		echo "var row = table.insertRow(" . '0' . ");\n";
		for ($x = 0; $x < 7; $x++)
			echo "row.insertCell(" . $x . ");";
		echo "var totalType = row.insertCell(7);\n";
		echo "totalType.innerHTML = 'Total Tickets';\n";
		echo "totalType.style.fontWeight = 'bold';\n";

		echo "var totalQ = row.insertCell(8);\n";
		echo 'totalQ.innerHTML = "' . $totalQ . '";' . "\n";
		echo 'totalQ.style.textAlign = "' . 'right' . '";' . "\n";
		echo "totalQ.style.fontWeight = 'bold';\n";

		echo "var totalV = row.insertCell(9);\n";
		echo 'totalV.innerHTML = "' . number_format((float)$totalV, 2, '.', '') . '";' . "\n";
		echo 'totalV.style.textAlign = "' . 'right' . '";' . "\n";
		echo "totalV.style.fontWeight = 'bold';\n";
	?>
});
</script>

<?php
if (!($model))
{
	$this->widget('bootstrap.widgets.TbButton', array(
		'label'=>'Download as CSV',
		'url'   => Yii::app()->controller->createUrl("downloadFull", array("fromD" => $fromD, "toD" => $toD, "fromT" => $fromT, "toT" => $toT)),
		'type'=>'primary',
		'buttonType' =>'link',
		//'imageUrl'=>Yii::app()->request->baseUrl.'/img/arrow_down.png',
	));
}
?> 

<?php $this->endWidget(); ?>
