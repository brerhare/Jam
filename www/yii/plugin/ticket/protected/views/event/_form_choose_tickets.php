
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
	<div class="row">
		<?php echo CHtml::textField('Text', '', array(
			'id'=>'idTextField',
			'width'=>'200px',
			
			'maxlength'=>100)); ?>
	</div>
		<div class="row">
		<?php echo CHtml::textField('Text', '', array(
			'id'=>'idTextField',
			'width'=>'100px',
			'maxlength'=>100)); ?>
	</div>
	style="width:150px;"
</div>