
<h2>Event Report for <?php echo $model->title?></h2>

<div class="row">
	<table>
		<tr style="background-color:#c3d9ff; color:#0088cc;">
			<td>Timestamp</td>
			<td>Order Number</td>
			<td>Name</td>
			<td>Email</td>
			<td style="text-align:right">Tickets</td>
			<td style="text-align:right">Each</td>
			<td style="text-align:right">Total</td>
		</tr>
		<?php
		$criteria = new CDbCriteria;
		$criteria->addCondition("event_id = " . $model->id);
		$criteria->addCondition("uid = " . Yii::app()->session['uid']);
		$transactions = Transaction::model()->findAll($criteria);
		foreach ($transactions as $transaction):
		 	$criteria = new CDbCriteria;
			$criteria->addCondition("uid = " . Yii::app()->session['uid']);
			$criteria->addCondition("order_number = " . $transaction->order_number);
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
				<?php if ($auth) echo $auth->card_name;?>
			</td>
			<td>
				<?php echo $transaction->email;?>
			</td>
			<td style="text-align:right">
				<?php echo $transaction->http_ticket_qty?>
			</td>
			<td style="text-align:right">
				<?php echo $transaction->http_ticket_price?>
			</td>
			<td style="text-align:right">
				<?php echo $transaction->http_ticket_total?>
			</td>
		</tr>
		<?php endforeach;?>
	</table>
</div>