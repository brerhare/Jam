	<p class="help-block">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<?php echo $form->textFieldRow($model,'title',array('class'=>'span5','maxlength'=>255)); ?>

	<?php //echo $form->textFieldRow($model,'program_id',array('class'=>'span5')); ?>
	<?php
		$criteria = new CDbCriteria;
		echo $form->dropDownListRow($model,'program_id', CHtml::listData(Program::model()->findAll($criteria), 'id', 'name'), array('empty'=>'Choose'));
	?>

	<?php //echo $form->textFieldRow($model,'start',array('class'=>'span5')); ?>
	<?php /// @@EG How to line up custom content ?>
	<div class="control-group "><label class="control-label" for="Event_start">Start Date <span class="required">*</span></label>
		<div class="controls">
			<?php Yii::import('application.extensions.CJuiDateTimePicker.CJuiDateTimePicker');
    			$this->widget('CJuiDateTimePicker',array(
        			'model'=>$model, //Model object
        			'attribute'=>'start', //attribute name
        			'mode'=>'datetime', //use "time","date" or "datetime" (default)
        			'language' => '',
        			'options'=>array( // jquery plugin options
        				'showAnim'=>'fold',
        				'dateFormat'=>'dd-mm-yy',
        			),
    			));
			?>
		</div>
	</div>

	<?php //echo $form->textFieldRow($model,'end',array('class'=>'span5')); ?>
	<div class="control-group "><label class="control-label" for="Event_end">End Date</label>
		<div class="controls">
<?php Yii::import('application.extensions.CJuiDateTimePicker.CJuiDateTimePicker');
    $this->widget('CJuiDateTimePicker',array(
        'model'=>$model, //Model object
        'attribute'=>'end', //attribute name
        'mode'=>'datetime', //use "time","date" or "datetime" (default)
        'language' => '',
        'options'=>array( // jquery plugin options
        	'showAnim'=>'fold',
        	'dateFormat'=>'dd-mm-yy',
        )
    ));
?>
		</div>
	</div>

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
		/*if ($model->isNewRecord)*/
		{
			if ($ticketUid != -1)
			// @@EG Disable any cactiveform field
				echo $form->toggleButtonRow($model, 'ticket_event_id' , array('disabled'=>'true','options'=>array('enabledLabel'=>'Yes' , 'disabledLabel'=>'No')));
			else
				echo $form->toggleButtonRow($model, 'ticket_event_id' , array('options'=>array('enabledLabel'=>'Yes' , 'disabledLabel'=>'No')));
			}
	?>

	<?php //echo $form->textAreaRow($model,'description',array('rows'=>6, 'cols'=>50, 'class'=>'span8')); ?>
	<div class="control-group "><label class="control-label" for="Event_start">Description <span class="required">*</span></label>
		<div class="controls">
			<div style="width:500px">
			<?php
			$this->widget('bootstrap.widgets.TbRedactorJs',
		    	array(
		      	'model'=>$model,
		      	'attribute'=>'description',
		      	'editorOptions'=>array(
		          	'imageUpload' => $this->createUrl('event/imageUpload'),
		          	'imageGetJson' => $this->createUrl('event/imageList'),
		          	'width'=>'100%',
		          	'height'=>'400px'
		       	)
		    	));
			?>
			</div>
		</div>
	</div>
	<br>&nbsp

	<?php //echo $form->textFieldRow($model,'approved',array('class'=>'span5')); ?>
	<?php //echo $form->textFieldRow($model,'member_id',array('class'=>'span5')); ?>

	<div class="form-actions">
		<?php $this->widget('bootstrap.widgets.TbButton', array(
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
		)); ?>
	</div>
