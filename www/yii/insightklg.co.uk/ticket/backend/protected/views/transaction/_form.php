<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'transaction-form',
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'timeStamp'); ?>
		<?php echo $form->textField($model,'timeStamp'); ?>
		<?php echo $form->error($model,'timeStamp'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'ip'); ?>
		<?php echo $form->textField($model,'ip',array('size'=>60,'maxlength'=>128)); ?>
		<?php echo $form->error($model,'ip'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'email'); ?>
		<?php echo $form->textField($model,'email',array('size'=>60,'maxlength'=>255)); ?>
		<?php echo $form->error($model,'email'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'friday'); ?>
		<?php echo $form->textField($model,'friday'); ?>
		<?php echo $form->error($model,'friday'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'saturday'); ?>
		<?php echo $form->textField($model,'saturday'); ?>
		<?php echo $form->error($model,'saturday'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'weekend'); ?>
		<?php echo $form->textField($model,'weekend'); ?>
		<?php echo $form->error($model,'weekend'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'vip'); ?>
		<?php echo $form->textField($model,'vip'); ?>
		<?php echo $form->error($model,'vip'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'children'); ?>
		<?php echo $form->textField($model,'children'); ?>
		<?php echo $form->error($model,'children'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'telephone'); ?>
		<?php echo $form->textField($model,'telephone',array('size'=>60,'maxlength'=>255)); ?>
		<?php echo $form->error($model,'telephone'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'orderNum'); ?>
		<?php echo $form->textField($model,'orderNum',array('size'=>60,'maxlength'=>255)); ?>
		<?php echo $form->error($model,'orderNum'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'amount'); ?>
		<?php echo $form->textField($model,'amount'); ?>
		<?php echo $form->error($model,'amount'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'cardName'); ?>
		<?php echo $form->textField($model,'cardName',array('size'=>60,'maxlength'=>255)); ?>
		<?php echo $form->error($model,'cardName'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'address1'); ?>
		<?php echo $form->textField($model,'address1',array('size'=>60,'maxlength'=>255)); ?>
		<?php echo $form->error($model,'address1'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'address2'); ?>
		<?php echo $form->textField($model,'address2',array('size'=>60,'maxlength'=>255)); ?>
		<?php echo $form->error($model,'address2'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'address3'); ?>
		<?php echo $form->textField($model,'address3',array('size'=>60,'maxlength'=>255)); ?>
		<?php echo $form->error($model,'address3'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'address4'); ?>
		<?php echo $form->textField($model,'address4',array('size'=>60,'maxlength'=>255)); ?>
		<?php echo $form->error($model,'address4'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'city'); ?>
		<?php echo $form->textField($model,'city',array('size'=>60,'maxlength'=>255)); ?>
		<?php echo $form->error($model,'city'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'state'); ?>
		<?php echo $form->textField($model,'state',array('size'=>60,'maxlength'=>255)); ?>
		<?php echo $form->error($model,'state'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'postCode'); ?>
		<?php echo $form->textField($model,'postCode',array('size'=>60,'maxlength'=>255)); ?>
		<?php echo $form->error($model,'postCode'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'countryShort'); ?>
		<?php echo $form->textField($model,'countryShort',array('size'=>60,'maxlength'=>255)); ?>
		<?php echo $form->error($model,'countryShort'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'message'); ?>
		<?php echo $form->textField($model,'message',array('size'=>60,'maxlength'=>255)); ?>
		<?php echo $form->error($model,'message'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->
