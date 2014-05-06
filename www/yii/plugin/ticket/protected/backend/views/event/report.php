<?php
$vendorName = "Missing vendor";
$criteria = new CDbCriteria;
$criteria->addCondition("uid = " . Yii::app()->session['uid']);
$vendor = Vendor::model()->find($criteria);
if ($vendor)
	$vendorName = $vendor->name;
?>

<h4>
<?php
echo $model->title . " as at " . date("d/m/y") . ' (' . $vendorName . ' / ' . str_replace('-', '/', $model->date) . ')'
?>
</h4>

<style>
table td, table th {
	padding: 0;
}
table tr {
	background-color:#f8f8f8;
	padding: 2px;
</style>

<div class="row">
<div class="span11">
	<table>
		<tr style="background-color:#c3d9ff; color:#0088cc;">
			<td>Date</td>
			<td>Order Number</td>
			<td>Name</td>
			<td>Phone</td>
			<td>Post Code</td>
			<td>Area</td>
			<td>Ticket</td>
			<td style="text-align:right">No</td>
			<td style="text-align:right; width:60px">£</td>
		</tr>
		<?php
		$prevOrder = "";
		$ticketTotal = 0;
		$valueTotal = 0;
		$criteria = new CDbCriteria;
		$criteria->addCondition("event_id = " . $model->id);
		$criteria->addCondition("uid = " . Yii::app()->session['uid']);
		$transactions = Transaction::model()->findAll($criteria);
		foreach ($transactions as $transaction):
		 	$criteria = new CDbCriteria;
			$criteria->addCondition("uid = " . Yii::app()->session['uid']);
			$criteria->addCondition("order_number = '" . $transaction->order_number . "'");
			$auth = Auth::model()->find($criteria);
		?>
		<tr>
<?php if ($prevOrder != $transaction->order_number): ?>
			<td>
				<?php
				$date = $transaction->timestamp;
				echo sprintf("%02s/%02s/%02s", substr($date,8,2),substr($date,5,2),substr($date,2,2));
				?>
			</td>
			<td>
				<?php echo '<a href="' . Yii::app()->baseUrl . '/tkts/' . $transaction->order_number . '.pdf">' . $transaction->order_number . '</a>'; ?>
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
				<?php echo $transaction->http_ticket_qty?>
				<?php $ticketTotal += $transaction->http_ticket_qty?>
			</td>
			<td style="text-align:right">
				<?php echo $transaction->http_ticket_total?>
				<?php $valueTotal += $transaction->http_ticket_total?>
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
			<td style="text-align:right"><?php echo $ticketTotal?></td>
			<td style="text-align:right; width:60px"><?php echo number_format((float)$valueTotal, 2, '.', '')?></td>
		</tr>
	</table>
</div>
</div>
