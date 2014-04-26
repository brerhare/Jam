
<h2>Event Report for <?php echo $model->title?></h2>

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
			<td>Timestamp</td>
			<td>Order Number</td>
			<td>Name/Email/Phone</td>
			<td>Address</td>
			<td style="text-align:right">Tickets</td>
			<td style="text-align:right; width:60px">Each</td>
			<td style="text-align:right; width:60px">Total</td>
		</tr>
		<?php
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
			<td>
				<?php echo $transaction->timestamp?>
			</td>
			<td>
				<?php echo '<a href="' . Yii::app()->baseUrl . '/tkts/' . $transaction->order_number . '.pdf">' . $transaction->order_number . '</a>'; ?>
			</td>
			<td>
				<?php if ($auth) echo $auth->card_name; else echo 'Name not available';?>
			</td>
			<td>
				<?php if ($auth) echo $auth->address1;?>
			</td>
			<td style="text-align:right">
				<?php echo $transaction->http_ticket_qty?>
				<?php $ticketTotal += $transaction->http_ticket_qty?>
			</td>
			<td style="text-align:right">
				<?php echo $transaction->http_ticket_price?>
			</td>
			<td style="text-align:right">
				<?php echo $transaction->http_ticket_total?>
				<?php $valueTotal += $transaction->http_ticket_total?>
			</td>
		</tr>
		<tr>
			<td></td>
			<td></td>
			<td>
				<?php echo $transaction->email;?>
			</td>
			<td>
				<?php if ($auth) { $addr = $auth->address2 . " " . $auth->address3 . " " . $auth->address4 . " " . $auth->city . " " . $auth->state; echo $addr; } ?>
			</td>
			<td></td>
			<td></td>
			<td></td>
		</tr>
		<tr>
			<td></td>
			<td></td>
			<td>
				<?php echo $transaction->telephone;?>
			</td>
			<td>
				<?php if ($auth) echo $auth->post_code; ?>
			</td>
			<td></td>
			<td></td>
			<td></td>
		</tr>
		<?php endforeach;?>
		<tr style="background-color:#c3d9ff; color:#0088cc;">
			<td>Totals</td>
			<td></td>
			<td></td>
			<td></td>
			<td style="text-align:right"><?php echo $ticketTotal?></td>
			<td style="text-align:right; width:60px"></td>
			<td style="text-align:right; width:60px"><?php echo $valueTotal?></td>
		</tr>
	</table>
</div>
</div>
