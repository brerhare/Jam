<div class="form">
	<?php $form=$this->beginWidget('bootstrap.widgets.TbActiveForm',array(
		'id'=>'room-form',
		'enableAjaxValidation'=>false,
		'type'=>'horizontal',
	)); ?>

<?php
$baseUrl = Yii::app()->baseUrl;
$cs = Yii::app()->getClientScript();
$cs->registerCoreScript('jquery');
$cs->registerScriptFile($baseUrl.'/js/jquery-ui.min.js');
?>

<?php // Control variables
$maxRooms = 3; // How many rooms can be booked
$showDays = 14; // How many days to show on calendar grid
//-------------------
echo "<script>var maxRooms=" . $maxRooms . ";</script>";
echo "<script>var showDays=" . $showDays . ";</script>";
//-------------------
?>

<style>
.form-horizontal .control-group {
margin-bottom: 0px;
}


</style>

<div class="row">
	<div class="span2" style="vertical-align:middle; text-align:right">
		<?php
		$this->widget('bootstrap.widgets.TbButton',array(
			'label' => 'Back',
			'buttonType'=>'submit',
			'type' => 'primary',
			'size' => 'large',
			'htmlOptions' => array(
				//'class' => 'disabled',
				'id'=> 'backButton',
				'name' => 'backButton',
				//'onclick'=>'js:return backButtonClick()',
			),
		));?>
	</div>
	<div class="span4" style="vertical-align:middle; text-align:center">
		<h3 style="color:#46679c">Step 3 - Payment details</h3>
	</div>
	<div class="span2" style="vertical-align:middle; text-align:left">
		<?php
		$this->widget('bootstrap.widgets.TbButton',array(
			'label' => 'Make Booking',
			'buttonType'=>'submit',
			'type' => 'primary',
			'size' => 'large',
			'htmlOptions' => array(
				//'class' => 'disabled',
				'id'=> 'nextButton3',
				'name' => 'nextButton3',
				//'onclick'=>'js:return nextButtonClick()',
			),
		));?>
	</div>
</div>

<div class="row">
	<div class="span1"></div>
	<div class='well span6'>

	<?php echo $form->errorSummary($model); ?>

	<?php //echo $form->textFieldRow($model,'uid',array('class'=>'span5')); ?>

	<?php //echo $form->textFieldRow($model,'ref',array('class'=>'span5','maxlength'=>255)); ?>

	<?php echo $form->textFieldRow($model,'address_1',array('class'=>'span4','maxlength'=>255)); ?>

	<?php echo $form->textFieldRow($model,'address_2',array('class'=>'span4','maxlength'=>255)); ?>

	<?php echo $form->textFieldRow($model,'town',array('class'=>'span4','maxlength'=>255)); ?>

	<?php echo $form->textFieldRow($model,'county',array('class'=>'span4','maxlength'=>255)); ?>

	<?php echo $form->textFieldRow($model,'post_code',array('class'=>'span1','maxlength'=>255)); ?>

	<?php echo $form->textFieldRow($model,'telephone',array('class'=>'span2','maxlength'=>255)); ?>

	<?php echo $form->textFieldRow($model,'email',array('class'=>'span3','maxlength'=>255)); ?>

	<?php echo $form->textFieldRow($model,'card_name',array('class'=>'span3','maxlength'=>255)); ?>

	<?php echo $form->textFieldRow($model,'card_number',array('class'=>'span2','maxlength'=>255)); ?>

	<?php echo $form->textFieldRow($model,'card_expiry_mm',array('class'=>'span1')); ?>

	<?php echo $form->textFieldRow($model,'card_expiry_yy',array('class'=>'span1')); ?>

	<?php echo $form->textFieldRow($model,'card_cvv',array('class'=>'span1')); ?>

	</div> <!-- well -->
</div> <!-- row -->


<!-------------------------------------------------------------------->
<?php
echo "<p>";
				foreach (Yii::app()->session as $field => $value)
				{
					echo $field . ":" . $value . "<br>";
					//Yii::app()->session[$field] = $value;
					//Yii::log("GIVING INDEX2 VALUES FOR " . Yii::app()->session[$field] . " = " . $value , CLogger::LEVEL_WARNING, 'system.test.kim');
				}
?>
<!-------------------------------------------------------------------->


<?php $this->endWidget(); ?>
</div><!-- form -->
