<?php $form=$this->beginWidget('bootstrap.widgets.TbActiveForm',array(
	'id'=>'event-form',
	'enableAjaxValidation'=>false,
	'type'=>'horizontal',
	'htmlOptions'=>array('enctype'=>'multipart/form-data'),
)); ?>

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
	<div class="control-group "><label class="control-label" for="Event_start">Start Date</label>
		<div class="controls">
		<?php
// @@EG CJuiDatePicker. See also the model for the before/after function I added to support this
		$this->widget('zii.widgets.jui.CJuiDatePicker',array(
			'name'=>'startDate',
			'model' => $model,
			'attribute' => 'start',
			// additional javascript options for the date picker plugin
			'options'=>array(
				'showAnim'=>'fold',
				'dateFormat' => 'dd-mm-yy', // save to db format
			),
			'htmlOptions'=>array(
				'style'=>'height:20px;'
			),
		));
	?>
		</div>
	</div>


	<?php //echo $form->textFieldRow($model,'end',array('class'=>'span5')); ?>
	<div class="control-group "><label class="control-label" for="Event_end">End Date</label>
		<div class="controls">
		<?php
// @@EG CJuiDatePicker. See also the model for the before/after function I added to support this
		$this->widget('zii.widgets.jui.CJuiDatePicker',array(
			'name'=>'endDate',
			'model' => $model,
			'attribute' => 'end',
			// additional javascript options for the date picker plugin
			'options'=>array(
				'showAnim'=>'fold',
				'dateFormat' => 'dd-mm-yy', // save to db format
			),
			'htmlOptions'=>array(
				'style'=>'height:20px;'
			),
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
	<br>&nbsp
	<div id="row">
		Free format description
		<div style="width:650px">
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
	<br>&nbsp





	<?php echo $form->textFieldRow($model,'approved',array('class'=>'span5')); ?>

	<?php echo $form->textFieldRow($model,'member_id',array('class'=>'span5')); ?>

	<div class="form-actions">
		<?php $this->widget('bootstrap.widgets.TbButton', array(
			'buttonType'=>'submit',
			'type'=>'primary',
			'label'=>$model->isNewRecord ? 'Create' : 'Save',
		)); ?>
	</div>

<?php $this->endWidget(); ?>
