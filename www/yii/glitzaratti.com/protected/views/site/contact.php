<?php
/* @var $this SiteController */
/* @var $model ContactForm */
/* @var $form CActiveForm */

/*$this->pageTitle=Yii::app()->name . ' - Contact Us';
$this->breadcrumbs=array(
	'Contact',
); */
?>

<style>
        /* Glitz specific */
    .Normal-P
    {
        margin:0.0px 0.0px 0.0px 0.0px;
        /*width:800px;*/
        font-size:90%;
        text-align:left;
        font-weight:100;
        color:#ede587;
    }
    .Big-P {
        color:#4b482a;
        font-weight:800;
        font-size: 175%;
    }
    .Medium-P {
        color:#4b482a;
        font-weight:800;
        font-size: 120%;
    }

</style>

<div style='left:50px; font-family:"Arial", sans-serif; font-size:16.0px; line-height:1.27em;'>
<br></br>
<h3 class="Big-P">Sign up</h3>

<?php if(Yii::app()->user->hasFlash('contact')): ?>

<div class="flash-success, Normal-P">
	<?php echo Yii::app()->user->getFlash('contact'); ?>
</div>

<?php else: ?>

<p class="Normal-P">
    Enter your email address and we'll keep you up to date. We will not pass on your details to any 3rd party.
</p>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'contact-form',
	'enableClientValidation'=>true,
	'clientOptions'=>array(
		'validateOnSubmit'=>true,
	),
)); ?>



	<p class="note, Normal-P">Fields with <span class="required">*</span> are required.</p>

<p></p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row, Normal-P">
		<?php echo $form->labelEx($model,'name'); ?>
		<?php echo $form->textField($model,'name'); ?>
		<?php echo $form->error($model,'name'); ?>
	</div>

	<div class="row, Normal-P">
		<?php echo $form->labelEx($model,'email'); ?>
		<?php echo $form->textField($model,'email'); ?>
		<?php echo $form->error($model,'email'); ?>
	</div>

	<?php if(CCaptcha::checkRequirements()): ?>
	<div class="row, Normal-P">
		<?php echo $form->labelEx($model,'verifyCode'); ?>
		<div>
		<?php $this->widget('CCaptcha'); ?><br>
		<?php echo $form->textField($model,'verifyCode'); ?>
		</div>
		<div class="Normal-P" style="font-style:italic">Please enter the letters as they are shown in the image above.
		<br/>Letters are not case-sensitive.</div>
		<?php echo $form->error($model,'verifyCode'); ?>
	</div>
	<?php endif; ?>

	<div class="row buttons">
		<?php echo CHtml::submitButton('Submit'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->

	</div> <!-- font -->


<?php endif; ?>