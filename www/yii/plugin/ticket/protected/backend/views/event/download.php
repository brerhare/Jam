<?php $form=$this->beginWidget('bootstrap.widgets.TbActiveForm',array(
	'id'=>'program-form',
	'enableAjaxValidation'=>false,
	'htmlOptions'=>array('enctype'=>'multipart/form-data'),
	'type'=>'horizontal',
)); ?>

	<?php echo $form->errorSummary($model); ?>

    <?php if (!($model->isNewRecord)): ?>
        <div class="control-group">
    	<label class="control-label" for="icon_path">Delegates list</label>
           	<div class="controls">
           		<?php
    			// @@EG TbButton to redirect to another url. If you leave off the 'array()' you can put in any external url
				$this->widget('bootstrap.widgets.TbButton', array(
					'type'=>'primary',
					'label'=>'Download',
					'url'=> array('/event/exportCSV/?id='.$model->id),
				)); ?> &nbsp&nbsp&nbsp&nbspThe file is in CSV format
			</div>
		</div>
	<?php endif; ?>

<?php $this->endWidget(); ?>

