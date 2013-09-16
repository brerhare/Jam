<?php $form=$this->beginWidget('bootstrap.widgets.TbActiveForm',array(
	'id'=>'article-form',
	'enableAjaxValidation'=>false,
)); ?>

	<p class="help-block">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<?php //echo $form->textFieldRow($model,'uid',array('class'=>'span5')); ?>

	<?php
		//echo $form->textFieldRow($model,'blog_category_id',array('class'=>'span5'));
		$criteria = new CDbCriteria;
		$criteria->addCondition("uid = " . Yii::app()->session['uid']);
		echo $form->dropDownListRow($model,'blog_category_id', CHtml::listData(Category::model()->findAll($criteria), 'id', 'name'), array('empty'=>'Choose')); ?>

	<?php //echo $form->textFieldRow($model,'date',array('class'=>'span5')); ?>


<div id = "row">
Date *
</div>
<div id = "row">

	<?php
// @@EG CJuiDatePicker. See also the model for the before/after function I added to support this

		$this->widget('zii.widgets.jui.CJuiDatePicker',array(
			'name'=>'publishDate',
			'model' => $model,
			'attribute' => 'date',
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


	<?php echo $form->textFieldRow($model,'title',array('class'=>'span5','maxlength'=>255)); ?>

	<?php echo $form->textAreaRow($model,'intro',array('rows'=>6, 'cols'=>50, 'class'=>'span8')); ?>

	<?php //echo $form->textAreaRow($model,'content',array('rows'=>6, 'cols'=>50, 'class'=>'span8')); ?>
	<div id="row">
Free format content
	<div style="width:908px">
	<?php
	$this->widget('bootstrap.widgets.TbRedactorJs',
    	array(
      	'model'=>$model,
      	'attribute'=>'content',
      	'editorOptions'=>array(
          	'imageUpload' => $this->createUrl('contentBlock/imageUpload'),
          	'imageGetJson' => $this->createUrl('contentBlock/imageList'),
          	'width'=>'100%',
          	'height'=>'400px'
       	)
    	));
	?>
	</div>
	</div>


	<div class="form-actions">
		<?php $this->widget('bootstrap.widgets.TbButton', array(
			'buttonType'=>'submit',
			'type'=>'primary',
			'label'=>$model->isNewRecord ? 'Create' : 'Save',
		)); ?>
	</div>

<?php $this->endWidget(); ?>
