<?php $form=$this->beginWidget('bootstrap.widgets.TbActiveForm',array(
	'id'=>'mailer-content-form',
	'enableAjaxValidation'=>false,
	'type'=>'horizontal',
	'htmlOptions'=>array('enctype'=>'multipart/form-data')
)); ?>

	<p class="help-block">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<?php //echo $form->textFieldRow($model,'uid',array('class'=>'span5')); ?>

	<?php echo $form->textFieldRow($model,'title',array('class'=>'span3','maxlength'=>255)); ?>

	<?php //echo $form->textFieldRow($model,'date',array('class'=>'span2')); ?>

	<?php /// @@EG How to line up custom content ?>
	<?php /// @@EG CJuiDate needs UK DATE formatting. See the model for before/after ?>
    <div class="control-group "><label class="control-label" for="MailerContent_date">Date <span class="required">*</span></label>
        <div class="controls">
            <?php Yii::import('application.extensions.CJuiDateTimePicker.CJuiDateTimePicker');
                $this->widget('CJuiDateTimePicker',array(
                    'model'=>$model, //Model object
                    'attribute'=>'date', //attribute name
                    'mode'=>'date', //use "time","date" or "datetime" (default)
                    'language' => '',
                    'options'=>array( // jquery plugin options
                        'showAnim'=>'fold',
                        'dateFormat'=>'dd-mm-yy',
                    ),
                ));
            ?>
        </div>
    </div>

	<?php //echo $form->textAreaRow($model,'content',array('rows'=>6, 'cols'=>50, 'class'=>'span6')); ?>
	<div id="row">
Free format content
		<div style="width:700px">
		<?php
		$this->widget('bootstrap.widgets.TbRedactorJs',
    		array(
      		'model'=>$model,
      		'attribute'=>'content',
      		'editorOptions'=>array(
          		'imageUpload' => $this->createUrl('mailerContent/imageUpload'),
          		'imageGetJson' => $this->createUrl('mailerContent/imageList'),
          		'width'=>'100%',
          		'height'=>'400px'
       		)
    		));
		?>
		</div>
	</div>

<br>
Attached mailing lists<br>
<div class="row">
    <div class="span2 well">
        <?php
            $criteria = new CDbCriteria;
            $criteria->addCondition("uid = " . Yii::app()->session['uid']);
            $lists = MailerList::model()->findAll($criteria);
            foreach ($lists as $list):
                $criteria = new CDbCriteria;
                $criteria->addCondition("mailer_content_id = $model->id");
                $criteria->addCondition("mailer_list_id = $list->id");
                $match = $model->isNewRecord ? 0 : MailerContentHasList::model()->exists($criteria);
        ?>
        <label class="checkbox">
            <input name="list[]" <?php if ($match) echo ' checked="checked" '?> type="checkbox" value="<?php echo $list->id; ?>"><?php echo $list->name; ?>
            </label>
        <?php endforeach; ?>
    </div><!-- /span -->
</div>


	<?php //echo $form->textFieldRow($model,'sent',array('class'=>'span1')); ?>

	<div class="form-actions">
		<?php $this->widget('bootstrap.widgets.TbButton', array(
			'buttonType'=>'submit',
			'type'=>'primary',
			'label'=>$model->isNewRecord ? 'Create' : 'Save',
		)); ?>
	</div>

<?php $this->endWidget(); ?>
