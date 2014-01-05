<div style="width:920px">
<?php $form=$this->beginWidget('bootstrap.widgets.TbActiveForm',array(
	'id'=>'content-block-form',
	'enableAjaxValidation'=>false,
	'type'=>'horizontal',
)); ?>

<?php $this->widget('bootstrap.widgets.TbTabs',array(
    'type'=>'tabs',
    'tabs' => array(
        array('label'=>'Standard', 'content' => $this->renderPartial('_form_standard', array('form' => $form, 'model' => $model), true), 'active'=>true),
        array('label'=>'SEO', 'content' => $this->renderPartial('_form_meta', array('form' => $form, 'model' => $model),  true)),
    ),
));
?>

<?php $this->endWidget(); ?>

</div>
