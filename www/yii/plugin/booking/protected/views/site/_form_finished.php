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

<style>
.form-horizontal .control-group {
margin-bottom: 0px;
}


</style>

<div class="row">
	<div class="span1"></div>
	<div class='well span6'>
	<h4>Thank you for your reservation.</h4>
	<h5>A confirmation email has been sent to you.<br>
	Should you not receive it within a few minutes please check your junk folder</h5>
	<br/><br/>
	<h4>You may now close this window</h4>
	<br><br>

	<div class="span2" style="vertical-align:middle; text-align:left">
		<?php
		$this->widget('bootstrap.widgets.TbButton',array(
			'label' => 'Close',
			'buttonType'=>'submit',
			'type' => 'primary',
			'size' => 'large',
			'htmlOptions' => array(
				//'class' => 'disabled',
				'id'=> 'finishedButton',
				'name' => 'finishedButton',
				'onclick'=>'js:void();',
			),
		));?>
	</div>
	
	</div> <!-- well -->
</div> <!-- row -->


<?php $this->endWidget(); ?>
</div><!-- form -->
