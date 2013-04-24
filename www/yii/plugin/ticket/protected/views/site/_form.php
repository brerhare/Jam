<!--
<style>
	body {width:520px;overflow: hidden;}
	div.form .form-horizontal {width:520px;}
	#content {width:520px;}
</style>
-->

<div class="form">
	<?php $form=$this->beginWidget('bootstrap.widgets.TbActiveForm',array(
		'id'=>'event-form',
		'enableAjaxValidation'=>false,
		'type'=>'horizontal',
	)); ?>

	<?php $this->endWidget(); ?>
</div><!-- form -->



