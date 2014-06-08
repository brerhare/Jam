<?php $form=$this->beginWidget('bootstrap.widgets.TbActiveForm',array(
	'id'=>'event-form',
	'type'=>'horizontal',
	'enableAjaxValidation'=>false,
	'htmlOptions'=>array('enctype'=>'multipart/form-data'),
)); ?>

	<p class="help-block">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<?php // echo $form->textFieldRow($model,'uid',array('class'=>'span5')); ?>


	<?php echo $form->textFieldRow($model,'title',array('class'=>'span5','maxlength'=>255, )); ?>


	<?php echo $form->textFieldRow($model,'date',array('class'=>'span3','maxlength'=>45)); ?>

	<?php echo $form->textAreaRow($model,'address',array('rows'=>6, 'cols'=>50, 'class'=>'span5')); ?>

	<?php echo $form->textFieldRow($model,'post_code',array('class'=>'span2','maxlength'=>45)); ?>

	<?php echo $form->textAreaRow($model,'banner_text',array('rows'=>3, 'cols'=>50, 'class'=>'span5')); ?>

	<?php //echo $form->textFieldRow($model,'ticket_logo_path',array('class'=>'span5','maxlength'=>255)); ?>
	<?php echo $form->fileFieldRow($model, 'ticket_logo_path'); ?>



	<?php // @@TODO @@EG usage of the various editors. Redactor still buggered ?>
	<?php //echo $form->textAreaRow($model,'ticket_text',array('rows'=>6, 'cols'=>50, 'class'=>'span8')); ?>
	<?php //echo $form->redactorRow($model, 'ticket_text', array('class'=>'span4', 'rows'=>5)); ?>
	<?php echo $form->html5EditorRow($model, 'ticket_text', array('class'=>'span8', 'rows'=>5, 'height'=>'200px', 'options'=>array('color'=>true)));?>
	<?php // echo $form->ckEditorRow($model, 'ticket_text', array('options'=>array('fullpage'=>'js:true', 'width'=>'640', 'resize_maxWidth'=>'640','resize_minWidth'=>'320')));?>


	<?php //echo $form->textAreaRow($model,'ticket_terms',array('rows'=>6, 'cols'=>50, 'class'=>'span8')); ?>
	<?php echo $form->html5EditorRow($model, 'ticket_terms', array('class'=>'span8', 'rows'=>5, 'height'=>'200px', 'options'=>array('color'=>true)));?>

	<?php
	if ($model->isNewRecord) 
		echo $form->textFieldRow($model,'optional_start_ticket_number',array('class'=>'span1','maxlength'=>10, 'style'=>'text-align:right'));
	else
		echo $form->textFieldRow($model,'optional_start_ticket_number',array('class'=>'span1','maxlength'=>10, 'style'=>'text-align:right', 'readonly'=>true));
	?>

	<?php // echo $form->textFieldRow($model,'active',array('class'=>'span1')); ?>
	<?php echo $form->toggleButtonRow($model, 'active'); ?>

<!-- @@TODO - sort this whole ymd/dmy thing out. Dates are screwed, times are fine -->

	<?php // echo $form->textFieldRow($model,'active_start_date',array('class'=>'span2')); ?>
	<?php //echo $form->datepickerRow(
	//	$model,
	//	'active_start_date',
	//	array(
	//		'class'=>'span2',
	//		'append'=>'<i class="icon-calendar"></i>',
	//		'options'=>array('format' => 'dd/mm/yyyy' , 'weekStart'=> 1)
	//	)
	//); ?>

	<?php // echo $form->textFieldRow($model,'active_start_time',array('class'=>'span2')); ?>
	<?php // echo $form->timepickerRow($model, 'active_start_time', array('class'=>'span1', 'append'=>'<i class="icon-time" style="cursor:pointer"></i>'));?>

	<?php // echo $form->textFieldRow($model,'active_end_date',array('class'=>'span2')); ?>
	<?php // echo $form->datepickerRow($model, 'active_end_date', array('class'=>'span2', 'append'=>'<i class="icon-calendar"></i>')); ?>

	<?php // echo $form->textFieldRow($model,'active_end_time',array('class'=>'span2')); ?>
	<?php // echo $form->timepickerRow($model, 'active_end_time', array('class'=>'span1', 'append'=>'<i class="icon-time" style="cursor:pointer"></i>'));?>

<!-- Hide the foreign key, although its included -->
	<?php //echo $form->textFieldRow($model,'ticket_vendor_id',array('class'=>'span2')); ?>
	<?php echo $form->hiddenField($model,'ticket_vendor_id'); ?>

	<div class="form-actions">
		<?php $this->widget('bootstrap.widgets.TbButton', array(
			'buttonType'=>'submit',
			'type'=>'primary',
			'label'=>$model->isNewRecord ? 'Create' : 'Save',
		)); ?>
	</div>

<script>
function embedWirefly()	/* Embed a wirefly hosted site curly wurly */
{
	var oArg = new Object();
	var text = " <?php echo '{{ticket ' . $model->id . ' ' . $model->title . '}}' ?> ";
	text = text.replace("'","");
	text = text.replace('"',"");
	text = text.replace('&',"");
	oArg.Document = text;
	prompt("Copy to clipboard: Ctrl+C", oArg.Document);
}
function embedExternal()	/* Embed a non-wirefly hosted site iframe */
{
	var oArg = new Object();


	var text = "<iframe width='100%' scrolling='no' style='overflow-x:hidden; overflow-y:auto;' src='https://plugin.wireflydesign.com/ticket/index.php/ticket/book/207?sid=ebh8d8h7nvos82om9remsi8fc5&amp;ref=none'></iframe><br/>";

	text += '<scr' + 'ipt type="text/javascript" src="https://plugin.wireflydesign.com/js/jquery.iframeResizerWrapper.js"></scr' + 'ipt>';

    oArg.Document = text;
    prompt("Copy to clipboard: Ctrl+C", oArg.Document);
}
</script>

    <?php if (!($model->isNewRecord)): ?>
        <div class="control-group">
        <label class="control-label" for="icon_path">Wirefly Hosted site</label>
            <div class="controls">
                <?php
                // @@EG TbButton to fire javascript
                $this->widget('bootstrap.widgets.TbButton', array(
                    'type'=>'primary',
                    'label'=>'Get Embed Code',
                        'htmlOptions'=>array(
                            'onclick'=>'js:embedWirefly()',
                         )

                )); ?>
            </div>
        </div>
    <?php endif; ?>

    <?php if (!($model->isNewRecord)): ?>
        <div class="control-group">
        <label class="control-label" for="icon_path">External site</label>
            <div class="controls">
                <?php
                // @@EG TbButton to fire javascript
                $this->widget('bootstrap.widgets.TbButton', array(
                    'type'=>'primary',
                    'label'=>'Get Embed Code',
                        'htmlOptions'=>array(
                            'onclick'=>'js:embedExternal()',
                         )

                )); ?>&nbsp&nbsp&nbsp&nbsp(Note that this requires JQuery)
            </div>
        </div>
    <?php endif; ?>

<?php $this->endWidget(); ?>
