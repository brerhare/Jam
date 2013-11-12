	<p class="help-block">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<?php //echo $form->textFieldRow($model,'uid',array('class'=>'span5')); ?>

	<?php echo $form->textFieldRow($model,'name',array('class'=>'span5','maxlength'=>255)); ?>

	<?php echo $form->textAreaRow($model,'description',array('rows'=>6, 'cols'=>50, 'class'=>'span5')); ?>

	<?php //echo $form->textFieldRow($model,'product_department_id',array('class'=>'span5')); ?>

<!-- @@EG: Dropdowns -->

<?php
$criteria = new CDbCriteria;
 $criteria->addCondition("uid = " . Yii::app()->session['uid']);
?>

	<?php //echo $form->textFieldRow($model,'product_vat_id',array('class'=>'span5')); ?>

	<?php echo $form->dropDownListRow($model,'product_vat_id', CHtml::listData(Vat::model()->findAll($criteria), 'id', 'description'), array('empty'=>'Choose')); ?>

	<?php echo $form->textFieldRow($model,'display_priority',array('class'=>'span1','maxlength'=>10, 'style'=>'text-align:right')); ?>

	<div class="form-actions">
		<?php $this->widget('bootstrap.widgets.TbButton', array(
			'buttonType'=>'submit',
			'type'=>'primary',
			'label'=>$model->isNewRecord ? 'Create' : 'Save',
		)); ?>
	</div>

