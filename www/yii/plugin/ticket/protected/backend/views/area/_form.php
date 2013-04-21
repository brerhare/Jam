<?php $form=$this->beginWidget('bootstrap.widgets.TbActiveForm',array(
	'id'=>'area-form',
	'type'=>'horizontal',
	'enableAjaxValidation'=>false,
)); ?>

	<p class="help-block">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<?php //echo $form->textFieldRow($model,'uid',array('class'=>'span5')); ?>

	<?php echo $form->textFieldRow($model,'description',array('class'=>'span5','maxlength'=>255)); ?>

	<?php echo $form->textFieldRow($model,'max_places',array('class'=>'span1','maxlength'=>10, 'style'=>'text-align:right')); ?>

	<?php //echo $form->textFieldRow($model,'ticket_event_id',array('class'=>'span5')); ?>


<!- @@EG Checkbox in view, can even be from different model. See controller update and create. -->

<div class="row">
    <div class="span3 well">
        <h3>Permitted ticket types</h3>
        <?php
            $criteria = new CDbCriteria;
            $criteria->addCondition("ticket_event_id = " . Yii::app()->session['event_id']);
            $criteria->addCondition("uid = " . Yii::app()->session['uid']);
            $ticketTypes = TicketType::model()->findAll($criteria);
            foreach ($ticketTypes as $ticketType):
                $criteria = new CDbCriteria;
                $criteria->addCondition("ticket_area_id = $model->id");
                $criteria->addCondition("ticket_ticket_type_id = $ticketType->id");
                $criteria->addCondition("uid = " . Yii::app()->session['uid']);
                $match = $model->isNewRecord ? 0 : AreaHasTicketType::model()->exists($criteria);
        ?>
        <label class="checkbox">
            <input name="ticktock[]" <?php if ($match) echo ' checked="checked" '?> type="checkbox" value="<?php echo $ticketType->id; ?>"><?php echo $ticketType->description; ?>
        </label>
	        <?php endforeach; ?>
    </div><!-- /span -->
</div> <!-- /row -->






	<div class="form-actions">
		<?php $this->widget('bootstrap.widgets.TbButton', array(
			'buttonType'=>'submit',
			'type'=>'primary',
			'label'=>$model->isNewRecord ? 'Create' : 'Save',
		)); ?>
	</div>

<?php $this->endWidget(); ?>
