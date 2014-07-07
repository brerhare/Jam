<div class="form">
<?php $form=$this->beginWidget('bootstrap.widgets.TbActiveForm',array(
	'id'=>'event-form',
	'enableAjaxValidation'=>false,
	'type'=>'horizontal',
	'htmlOptions'=>array('enctype'=>'multipart/form-data'),
)); ?>

<?php
if (Yii::app()->session['pid'] == 6)	// Wild Seasons
{
	$this->widget('bootstrap.widgets.TbTabs',array(
    	'type'=>'tabs',
    	'tabs' => array(
        	array('label'=>'Standard', 'content' => $this->renderPartial('_form_standard', array('form' => $form, 'model' => $model, 'model2' => $model2, 'ticketUid' => $ticketUid), true), 'active'=>true),
        	array('label'=>'Wild Seasons', 'content' => $this->renderPartial('_form_ws', array('form' => $form, 'model' => $model, 'model2' => $model2, 'ticketUid' => $ticketUid),  true)),
    	),
	));
}
else	// Default (no lock)
{
	$this->widget('bootstrap.widgets.TbTabs',array(
    	'type'=>'tabs',
    	'tabs' => array(
        	array('label'=>'Standard', 'content' => $this->renderPartial('_form_standard', array('form' => $form, 'model' => $model, 'model2' => $model2, 'ticketUid' => $ticketUid), true), 'active'=>true),
    	),
	));
}

?>

    <?php $this->endWidget(); ?>
</div><!-- form -->
