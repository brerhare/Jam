<div class="form">
	<?php $form=$this->beginWidget('bootstrap.widgets.TbActiveForm',array(
		'id'=>'room-form',
		'enableAjaxValidation'=>false,
		'type'=>'horizontal',
	)); ?>

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
		<h3 style="color:#46679c">Step 2 - Choose Room Options</h3>
	</div>
	<div class="span2" style="vertical-align:middle; text-align:left">
		<?php
		$this->widget('bootstrap.widgets.TbButton',array(
			'label' => 'Next',
			'buttonType'=>'submit',
			'type' => 'primary',
			'size' => 'large',
			'htmlOptions' => array(
				'class' => 'disabled',
				'id'=> 'nextButton',
				'name' => 'nextButton',
				'onclick'=>'js:return nextButtonClick()',
			),
		));?>
	</div>
</div>

<script>
function nextButtonClick() {
	classes = document.getElementById("nextButton").className;
	if (classes.indexOf('disabled') !== -1)
		return false;
	else
		return true;
}
</script>

<?php $this->endWidget(); ?>
</div><!-- form -->
