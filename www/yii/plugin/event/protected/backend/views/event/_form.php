<style>
.control-group{ margin-bottom:3px !important;}
</style>

<div class="form">
<?php $form=$this->beginWidget('bootstrap.widgets.TbActiveForm',array(
	'id'=>'event-form',
	'enableAjaxValidation'=>false,
	'type'=>'horizontal',
	'htmlOptions'=>array('enctype'=>'multipart/form-data'),
)); ?>

<?php

$wsFlag = 0;
if (($model->isNewRecord) && ($this->creatingWildSeasons()))
	$wsFlag = 1;
if ((!($model->isNewRecord)) && ($this->isWildSeasons($model->id)))
	$wsFlag = 1;

if ($wsFlag)
{
	$this->widget('bootstrap.widgets.TbTabs',array(
    	'type'=>'tabs',
    	'tabs' => array(
        	array('label'=>'Standard', 'content' => $this->renderPartial('_form_standard', array('form' => $form, 'model' => $model, 'model2' => $model2, 'updateMode' => $updateMode, 'ticketUid' => $ticketUid), true), 'active'=>true),
        	array('label'=>'Wild Seasons', 'content' => $this->renderPartial('_form_ws', array('form' => $form, 'model' => $model, 'model2' => $model2, 'updateMode' => $updateMode, 'ticketUid' => $ticketUid),  true)),
    	),
	));
}
else	// Default (no lock)
{
	$this->widget('bootstrap.widgets.TbTabs',array(
    	'type'=>'tabs',
    	'tabs' => array(
        	array('label'=>'Standard', 'content' => $this->renderPartial('_form_standard', array('form' => $form, 'model' => $model, 'model2' => $model2, 'updateMode' => $updateMode, 'ticketUid' => $ticketUid), true), 'active'=>true),
    	),
	));
}

?>

    <?php $this->endWidget(); ?>
</div><!-- form -->
