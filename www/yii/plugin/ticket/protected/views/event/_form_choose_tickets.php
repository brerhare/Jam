<div class="row">

		<?php
		$criteria = new CDbCriteria;
		$criteria->addCondition("ticket_event_id = " . $model->id);
		$criteria->addCondition("uid = " . Yii::app()->session['uid']);
		$areas = Area::model()->findAll($criteria);
		foreach ($areas as $area):
			echo "<h5>$area->description</h5>";
		?>
		<table>	
			<?php
			$criteria = new CDbCriteria;
			$criteria->addCondition("ticket_area_id = " . $area->id);
			$criteria->addCondition("uid = " . Yii::app()->session['uid']);
			$ticketTypes = TicketType::model()->findAll($criteria);
			foreach ($ticketTypes as $ticketType):
			?>
			<tr>
				<!-- blank -->
				<td width="10%">
				</td>
				<!-- ticket type -->
				<td width="60%">
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
					<?php echo CHtml::dropDownList('listname', $select, 
              /*array('1' => '1', '2' => '2')*/ $arr,
array('style'=>'width:50px')             
              );?>
				</td>
				<!-- price -->
				<td width="20%">
					<?php echo $ticketType->price;?>
				</td>
			</tr>
			<?php endforeach;?>
		</table>
		<?php endforeach;?>

</div>