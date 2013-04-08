<?php echo $form->errorSummary($model); ?>

<style>
    table { table-layout: fixed; }
    td { width: 16%; }
</style>

<div class="row">
    <div class="span7">
    <table class="Xtable Xtable-bordered">
        <thead>
        <tr>
	        <th style="width:50%"></th>
            <th>Single</th>
            <th>Double</th>
            <th>Any</th>
            <th>Per Adult</th>
            <th>Per Child</th>
        </tr>
        </thead>
        <tbody>
        <?php
		// Show ALL occupancy types
		$criteria = new CDbCriteria;
		$criteria->addCondition("uid = " . Yii::app()->session['uid']);
		$occupancyTypes = OccupancyType::model()->findAll($criteria);
		foreach ($occupancyTypes as $occupancyType):
	        echo "<tr>";
			echo "<td>" . $occupancyType->description . "</td>";

			// Show room-has-occupancy-types (unless in 'create' mode)
			$criteria = new CDbCriteria;
			$criteria->addCondition("room_id = " . $model->id);
			$criteria->addCondition("occupancy_type_id = " . $occupancyType->id);
			$criteria->addCondition("uid = " . Yii::app()->session['uid']);
			$roomHasOccupancyTypes = $model->isNewRecord ? null : RoomHasOccupancyType::model()->find($criteria);
			$single = ($roomHasOccupancyTypes == null) ? "" : " value='" . $roomHasOccupancyTypes->single_rate . "' ";
			$double = ($roomHasOccupancyTypes == null) ? "" : " value='" . $roomHasOccupancyTypes->double_rate . "' ";
			$any    = ($roomHasOccupancyTypes == null) ? "" : " value='" . $roomHasOccupancyTypes->any_rate . "' ";
			$adult  = ($roomHasOccupancyTypes == null) ? "" : " value='" . $roomHasOccupancyTypes->adult_rate . "' ";
			$child  = ($roomHasOccupancyTypes == null) ? "" : " value='" . $roomHasOccupancyTypes->child_rate . "' ";
			if ($single == " value='0.00' ") $single = "";
			if ($double == " value='0.00' ") $double = "";
			if ($any    == " value='0.00' ") $any    = "";
			if ($adult  == " value='0.00' ") $adult  = "";
			if ($child  == " value='0.00' ") $child  = "";
	        echo "<td><input type='text' name='" . $occupancyType->id . "_single' " . $single . " style='width:45px;'></td>";
			echo "<td><input type='text' name='" . $occupancyType->id . "_double' " . $double . " style='width:45px;'></td>";
			echo "<td><input type='text' name='" . $occupancyType->id . "_any'    " . $any    . " style='width:45px;'></td>";
			echo "<td><input type='text' name='" . $occupancyType->id . "_adult'  " . $adult  . " style='width:45px;'></td>";
			echo "<td><input type='text' name='" . $occupancyType->id . "_child'  " . $child  . " style='width:45px;'></td>";
	        echo "</tr>";

		endforeach; ?>

        </tbody>
    </table>
</div>
	</div>