<?php $form=$this->beginWidget('bootstrap.widgets.TbActiveForm',array(
	'id'=>'program-form',
	'enableAjaxValidation'=>false,
	'htmlOptions'=>array('enctype'=>'multipart/form-data'),
	'type'=>'horizontal',
)); ?>

	<p class="help-block">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<?php echo $form->textFieldRow($model,'name',array('class'=>'span5','maxlength'=>255)); ?>

    <div class="control-group">
    	<label class="control-label" for="thumb_path">Thumb Path</label>
           	<div class="controls">
        	<?php echo CHtml::activeFileField($model,'thumb_path',array('size'=>60,'maxlength'=>255)); ?>
        	<?php echo $form->error($model,'thumb_path'); ?>
        </div>
    </div>

    <div class="control-group">
    	<label class="control-label" for="icon_path">Icon Path</label>
           	<div class="controls">
	        <?php echo CHtml::activeFileField($model,'icon_path',array('size'=>60,'maxlength'=>255)); ?>
    	    <?php echo $form->error($model,'icon_path'); ?>
    	</div>
    </div>

    <?php if (!($model->isNewRecord)): ?>
        <div class="control-group">
    	<label class="control-label" for="icon_path">Database Events</label>
           	<div class="controls">
           		<?php
    			// @@EG TbButton to redirect to another url. If you leave off the 'array()' you can put in any external url
				$this->widget('bootstrap.widgets.TbButton', array(
					'type'=>'primary',
					'label'=>'Export',
					'url'=> array('/program/export'),
				)); ?> &nbsp&nbsp&nbsp&nbspThe report will be emailed to you
			</div>
		</div>
	<?php endif; ?>

	<div class="form-actions">
		<?php $this->widget('bootstrap.widgets.TbButton', array(
			//'buttonType'=>'submit',
			'type'=>'primary',
			'label'=>$model->isNewRecord ? 'Create' : 'Save',
		)); ?>
	</div>

<?php $this->endWidget(); ?>

