<style>
.control-group{ margin-bottom:3px !important;}
</style>

<div class="form">
    <?php $form=$this->beginWidget('bootstrap.widgets.TbActiveForm',array(
        'id'=>'product-form',
        'enableAjaxValidation'=>false,
        'type'=>'horizontal',
    )); ?>

    <?php
    // The original default price option, overridden if changed on the pricing screen. Sent back to server
    $criteria = new CDbCriteria;
    $criteria->addCondition("product_product_id = " . $model->id);
    $criteria->addCondition("is_default = " . 1);
    $productHasOption = $model->isNewRecord ? null : ProductHasOption::model()->find($criteria);
    $def = ($productHasOption == null) ? 0 : $productHasOption->product_option_id;
    echo "<input type='hidden' name='defaultOption' id='defaultOption' value='" . $def . "'>";
    ?>

    <?php
		$tabs = array();
		array_push($tabs, array('label'=>'Basic Details', 'content' => $this->renderPartial('_form_details', array('form' => $form, 'model' => $model), true), 'active'=>true));
		array_push($tabs, array('label'=>'Order Options', 'content' => $this->renderPartial('_form_options', array('form' => $form, 'model' => $model),  true)));
		array_push($tabs, array('label'=>'Features', 'content' => $this->renderPartial('_form_features', array('form' => $form, 'model' => $model), true)));
		array_push($tabs, array('label'=>'Packing', 'content' => $this->renderPartial('_form_packing', array('form' => $form, 'model' => $model), true)));
		// Only show the department move tab if its not a new record
		if (!$model->isNewRecord)
			array_push($tabs, array('label'=>'Change Department', 'content' => $this->renderPartial('_form_department', array('form' => $form, 'model' => $model), true)));

		$this->widget('bootstrap.widgets.TbTabs',array(
        'type'=>'tabs',
		'tabs' => $tabs,
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

