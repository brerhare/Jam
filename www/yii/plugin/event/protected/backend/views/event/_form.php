<div class="form">
<?php $form=$this->beginWidget('bootstrap.widgets.TbActiveForm',array(
	'id'=>'event-form',
	'enableAjaxValidation'=>false,
	'type'=>'horizontal',
	'htmlOptions'=>array('enctype'=>'multipart/form-data'),
)); ?>

<?php $this->widget('bootstrap.widgets.TbTabs',array(
    'type'=>'tabs',
    'tabs' => array(
        array('label'=>'Standard', 'content' => $this->renderPartial('_form_standard', array('form' => $form, 'model' => $model, 'model2' => $model2, 'ticketUid' => $ticketUid), true), 'active'=>true),
        array('label'=>'Wild Seasons', 'content' => $this->renderPartial('_form_ws', array('form' => $form, 'model' => $model, 'model2' => $model2, 'ticketUid' => $ticketUid),  true)),
    ),
));
?>

    <?php $this->endWidget(); ?>
</div><!-- form -->
