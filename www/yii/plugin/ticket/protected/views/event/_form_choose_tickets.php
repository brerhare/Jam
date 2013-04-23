
<style>

.row {
    margin-left: 3px;
}

</style>

<div class="row">
	<table>	
		<?php
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
			?>
			<tr style="background-color:#EDE4F2">
				<!-- blank -->
				<td width="10%">
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
					for ($x = 0; $x <= $ticketType->max_tickets_per_order; $x++)
						array_push($arr, $x);
					?>
					<?php echo CHtml::dropDownList('listname', $select, $arr, array('style'=>'width:50px'));?>
				</td>
				<!-- unit price -->
				<td width="20%" style="text-align:right">
					<?php echo $ticketType->price;?>
				</td>
				<!-- line total -->
				<td width="20%"  style="text-align:right">
					<?php echo $ticketType->price;?>
				</td>
			</tr>
			<?php endforeach;?>
		<?php endforeach;?>
	</table>
</div>

<div class="span5 well">
	<table>
		<tr class="row">
			<td style="text-align:right">
				Email Address
			</td>
			<td>
				<input id="email1" value="" class="" MaxLength="50" />
			</td>
		</tr>
		<tr class="row">
			<td style="text-align:right">
				Again
			</td>
			<td>
				<input id="email2" value="" class="" MaxLength="50" />
			</td>
		</tr>
		<tr class="row">
			<td style="text-align:right">
				Telephone Number
			</td>
			<td>
				<input id="telephone" value="" class="" MaxLength="50" />
			</td>
		</tr>
	</table>
</div>

<!--
	<div class="row">
		<input id="email2" value="" class="" MaxLength="50" />
	</div>
	<div class="row">
		<input name="phone" value="" class="InputTextField" MaxLength="15" style="width:100px"/>
	</div>
-->

