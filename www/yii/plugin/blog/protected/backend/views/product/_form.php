<div class="form">
    <?php $form=$this->beginWidget('bootstrap.widgets.TbActiveForm',array(
        'id'=>'product-form',
        'enableAjaxValidation'=>false,
        'type'=>'horizontal',
    )); ?>

    <?php $this->widget('bootstrap.widgets.TbTabs',array(
        'type'=>'tabs',
        'tabs' => array(
            array('label'=>'Basic Details', 'content' => $this->renderPartial('_form_details', array('form' => $form, 'model' => $model), true), 'active'=>true),
            array('label'=>'Order Options', 'content' => $this->renderPartial('_form_options', array('form' => $form, 'model' => $model),  true)),
            array('label'=>'Features', 'content' => $this->renderPartial('_form_features', array('form' => $form, 'model' => $model), true)),
            array('label'=>'Packing', 'content' => $this->renderPartial('_form_packing', array('form' => $form, 'model' => $model), true)),
        ),
    ));
    ?>

<!-------
    <div class="form-actions">
        <?php $this->widget('bootstrap.widgets.TbButton', array(
        'buttonType'=>'submit',
        'type'=>'primary',
        'label'=>$model->isNewRecord ? 'Create' : 'Save',
    )); ?>
    </div>
-------->

    <?php $this->endWidget(); ?>
</div><!-- form -->

