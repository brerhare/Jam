<?php echo $form->errorSummary($model); ?>
<div class="row">
	<div class="span3 well">
		<h3>Facilities</h3>
		<?php
			$criteria = new CDbCriteria;
			$criteria->addCondition("uid = " . Yii::app()->session['uid']);
			$facilities = Facility::model()->findAll($criteria);
			foreach ($facilities as $facility):
				$criteria = new CDbCriteria;
		        $criteria->addCondition("room_id = $model->id");
				$criteria->addCondition("facility_id = $facility->id");
				$criteria->addCondition("uid = " . Yii::app()->session['uid']);
				$match = $model->isNewRecord ? 0 : RoomHasFacility::model()->exists($criteria);
		?>
		<label class="checkbox">
			<input name="facility[]" <?php if ($match) echo ' checked="checked" '?> type="checkbox" value="<?php echo $facility->id; ?>"><?php echo $facility->description; ?>
			</label>
		<?php endforeach; ?>
	</div><!-- /span -->

	<div class="span3 well">
		<h3>Extras</h3>
		<?php
			$criteria = new CDbCriteria;
			$criteria->addCondition("uid = " . Yii::app()->session['uid']);
			$extras = Extra::model()->findAll($criteria);
			foreach ($extras as $extra):
				$criteria = new CDbCriteria;
				$criteria->addCondition("room_id = $model->id");
				$criteria->addCondition("extra_id = $extra->id");
				$criteria->addCondition("uid = " . Yii::app()->session['uid']);
				$match = $model->isNewRecord ? 0 : RoomHasExtra::model()->exists($criteria);
		?>
        <label class="checkbox">
             <input name="extra[]" <?php if ($match) echo ' checked="checked" '?> type="checkbox" value="<?php echo $extra->id; ?>"><?php echo $extra->description; ?>
            </label>
			<?php endforeach; ?>

	</div><!-- /span -->

</div><!-- /row -->
