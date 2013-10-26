	<p class="help-block">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<?php echo $form->textFieldRow($model,'title',array('class'=>'span5','maxlength'=>255)); ?>

	<?php //echo $form->textFieldRow($model,'program_id',array('class'=>'span5')); ?>
	<?php
		$criteria = new CDbCriteria;
		//$criteria->addCondition("uid = " . Yii::app()->session['uid']);
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

	<?php // Price band
		//$criteria = new CDbCriteria;
		//$criteria->addCondition("uid = " . Yii::app()->session['uid']);
		//echo $form->dropDownListRow($model,'blog_category_id', CHtml::listData(Category::model()->findAll($criteria), 'id', 'name'), array('empty'=>'Choose'));
	?>

	<?php echo $form->textAreaRow($model,'contact',array('rows'=>6, 'cols'=>50, 'class'=>'span5')); ?>

	<?php //echo $form->textFieldRow($model,'thumb_path',array('class'=>'span5','maxlength'=>255)); ?>
	<?php echo $form->fileFieldRow($model, 'thumb_path'); ?>

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
