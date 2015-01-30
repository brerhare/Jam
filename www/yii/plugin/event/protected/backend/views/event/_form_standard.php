	<?php
//		if (($this->updateAsAdmin) && (!($model->isNewRecord)))
//		{
//			echo $form->toggleButtonRow($model, 'approved' );
//		echo "<hr/>";
//		}
	?>

	<p class="help-block">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<?php echo $form->textFieldRow($model,'title',array('class'=>'span5','maxlength'=>255)); ?>

	<?php
		//$criteria = new CDbCriteria;
		//$criteria->addCondition("id = "  . Yii::app()->session['pid']); // Ony see the lock_program program
		//echo $form->dropDownListRow($model,'program_id', CHtml::listData(Program::model()->findAll($criteria), 'id', 'name'));
	?>

<!------------------------------------------ @@EG: dropdown date starts ------------------------------------------->
	<script type="text/javascript" src="/js/dropdownDate.js"></script>
	<style>
		span#startDate select {width:70px; margin-right:5px}
		span#endDate select {width:70px; margin-right:5px}
	</style>

	<div class="control-group "><label class="control-label" for="Event_start">Start Date <span class="required">*</span></label>
		<div class="controls">
			<?php echo $form->hiddenField($model, 'start'); ?>
			<span id='startDate'></span>
		</div>
	</div>
	<div class="control-group "><label class="control-label" for="Event_end">End Date <span class="required">*</span></label>
		<div class="controls">
			<?php echo $form->hiddenField($model, 'end'); ?>
			<span id='endDate'></span>
		</div>
	</div>

	<script>
		dropdownDate('startDate', 'Event_start', 'dd-mm-yyyy hr:mn');
		dropdownDate('endDate', 'Event_end', 'dd-mm-yyyy hr:mn');
	</script>
<!-------------------------------------------- dropdown date ends ------------------------------------------------>

	<?php echo $form->textAreaRow($model,'address',array('rows'=>6, 'cols'=>50, 'class'=>'span5')); ?>
	<?php echo $form->textFieldRow($model,'post_code',array('class'=>'span2','maxlength'=>255)); ?>
	<?php echo $form->textFieldRow($model,'web',array('class'=>'span5','maxlength'=>255)); ?>
	<?php
		$criteria = new CDbCriteria;
		echo $form->dropDownListRow($model,'event_price_band_id', CHtml::listData(PriceBand::model()->findAll($criteria), 'id', 'name'), array('empty'=>'Choose'));
	?>
	<?php echo $form->textAreaRow($model,'contact',array('rows'=>6, 'cols'=>50, 'class'=>'span5')); ?>
	<div class="row">
		<div class="span2"></div>
	    <div class="xspan2 well" style="margin-left:-20px">
	    	<center><h4>Interest</h4></center>
	        <?php
	            $criteria = new CDbCriteria;
	            $criteria->order = 'id ASC';
	            $interests = Interest::model()->findAll($criteria);
	            foreach ($interests as $interest):
	                $criteria = new CDbCriteria;
	                $criteria->addCondition("event_event_id = $model->id");
	                $criteria->addCondition("event_interest_id = $interest->id");
	                $match = $model->isNewRecord ? 0 : EventHasInterest::model()->exists($criteria);
	        ?>
	        <label class="checkbox">
	            <input name="interest[]" <?php if ($match) echo ' checked="checked" '?> type="checkbox" value="<?php echo $interest->id; ?>"><?php echo $interest->name; ?>
	            </label>
	        <?php endforeach; ?>
	    </div>
	    <div class="xspan2 well">
	    	<center><h4>Format</h4></center>
	        <?php
	            $criteria = new CDbCriteria;
	            $criteria->order = 'id ASC';
	            $formats = Format::model()->findAll($criteria);
	            foreach ($formats as $format):
	                $criteria = new CDbCriteria;
	                $criteria->addCondition("event_event_id = $model->id");
	                $criteria->addCondition("event_format_id = $format->id");
	                $match = $model->isNewRecord ? 0 : EventHasFormat::model()->exists($criteria);
	        ?>
	        <label class="checkbox">
	            <input name="format[]" <?php if ($match) echo ' checked="checked" '?> type="checkbox" value="<?php echo $format->id; ?>"><?php echo $format->name; ?>
	            </label>
	        <?php endforeach; ?>
	    </div>
	    <div class="xspan2 well">
	    	<center><h4>Facilities</h4></center>
	        <?php
	            $criteria = new CDbCriteria;
	            $criteria->order = 'id ASC';
	            $facilities = Facility::model()->findAll($criteria);
	            foreach ($facilities as $facility):
	                $criteria = new CDbCriteria;
	                $criteria->addCondition("event_event_id = $model->id");
	                $criteria->addCondition("event_facility_id = $facility->id");
	                $match = $model->isNewRecord ? 0 : EventHasFacility::model()->exists($criteria);
	        ?>
	        <label class="checkbox">
	            <input name="facility[]" <?php if ($match) echo ' checked="checked" '?> type="checkbox" value="<?php echo $facility->id; ?>"><?php echo $facility->name; ?>
	            </label>
	        <?php endforeach; ?>
	    </div>
	</div>
	<?php echo $form->fileFieldRow($model, 'thumb_path'); ?>
	<?php
		if (($model->isNewRecord) || ($model->ticket_event_id == 0))
		{
			if ($ticketUid == -1) // @@EG Disable any cactiveform field
			{
				echo $form->toggleButtonRow($model, 'ticket_event_id');
				//// @@NB: the 'options' buggers Yii although docs say its right: .. echo $form->toggleButtonRow($model, 'ticket_event_id' , array('disabled'=>'true','options'=>array('enabledLabel'=>'Yes' , 'disabledLabel'=>'No')));
			}
			else
			{
				echo $form->toggleButtonRow($model, 'ticket_event_id');
				//// @@NB: the 'options' buggers Yii although docs say its right: .. echo $form->toggleButtonRow($model, 'ticket_event_id' , array('options'=>array('enabledLabel'=>'Yes' , 'disabledLabel'=>'No')));
			}
		}
		else
		{
			echo "<div class='control-group'>";
				echo $form->labelEx($model,'Ticket event id');
				echo "<div style='margin-top:-30px' class='controls'>";
					echo $form->textField($model,'ticket_event_id');
				echo "</div>";
			echo "</div>";
		}
	?>

<br>
<?php
	echo $form->toggleButtonRow($model, 'active' );
	///// @@NB: the 'options' buggers Yii although docs say its right: .. echo $form->toggleButtonRow($model, 'active' , array('options'=>array('enabledLabel'=>'Yes' , 'disabledLabel'=>'Yes')));
?>

<!-- CKEditor starts -->

    <script src="<?php echo Yii::app()->baseUrl.'/scripts/editors/ck/ckeditor/ckeditor.js'; ?>"></script>
    <?php
        $_SESSION['KCFINDER']['disabled'] = false; // enables the file browser in the admin
        $_SESSION['KCFINDER']['uploadURL'] = Yii::app()->baseUrl."/userdata/image/"; // URL for the uploads folder
        $_SESSION['KCFINDER']['uploadDir'] = Yii::app()->basePath."/../userdata/image/"; // path to the uploads folder
    ?>
    <!-- <div class="row"> -->
    <?php echo $form->labelEx($model,'description'); ?>
    <?php echo $form->textArea($model, 'description', array('id'=>'editor1')); ?>
    <?php echo $form->error($model,'description'); ?>
    <!-- </div> -->

    <script type="text/javascript">
    CKEDITOR.replace( 'editor1', {
        width: <?php echo Yii::app()->params['editorpagewidth'];?>,
        height: <?php echo Yii::app()->params['editorpageheight'];?>,
        filebrowserUploadUrl: '<?php echo Yii::app()->baseUrl; ?>/scripts/editors/ck/kcfinder/upload.php?type=files',
        filebrowserImageUploadUrl: '<?php echo Yii::app()->baseUrl; ?>/scripts/editors/ck/kcfinder/upload.php?type=images',
        filebrowserFlashUploadUrl: '<?php echo Yii::app()->baseUrl; ?>/scripts/editors/ck/kcfinder/upload.php?type=flash'
    });
    </script>

<!-- CKEditor ends -->

	<br>&nbsp
	<?php //echo $form->textFieldRow($model,'approved',array('class'=>'span5')); ?>
	<?php //echo $form->textFieldRow($model,'member_id',array('class'=>'span5')); ?>
	<div class="form-actions">
<?php
	if ($updateMode == "update")
	{
		if ($this->creatingWildSeasons())
		{
			$this->widget('bootstrap.widgets.TbButton', array(
				'buttonType'=>'submit',
				'type'=>'primary',
            	'htmlOptions' => array(
                	'class' => $model->isNewRecord ? 'disabled' : '',
                	'disabled'=>$model->isNewRecord ? 'true' : '',
                	//'id'=> 'nextButton',
                	//'name' => 'nextButton',
                	//'onclick'=>'js:return nextButtonClick()',
            	),

				//'label'=>$model->isNewRecord ? 'Create' : 'Save',
				'label'=>$model->isNewRecord ? 'Save on next tab' : 'Save',
			));
		}
		else
		{
			$this->widget('bootstrap.widgets.TbButton', array(
				'buttonType'=>'submit',
				'type'=>'primary',
				'label'=>$model->isNewRecord ? 'Create' : 'Save',
			));
		}
	}
?>
	</div>
