<?php echo $form->errorSummary($model); ?>
<div class="row">
    <div class="span2 well">
        <?php
            $criteria = new CDbCriteria;
            $criteria->addCondition("uid = " . Yii::app()->session['uid']);
            $criteria->addCondition("product_department_id = " . $model->product_department_id);
            $features = Feature::model()->findAll($criteria);
            foreach ($features as $feature):
                $criteria = new CDbCriteria;
                $criteria->addCondition("product_product_id = $model->id");
                $criteria->addCondition("product_feature_id = $feature->id");
                //$criteria->addCondition("uid = " . Yii::app()->session['uid']);
                $match = $model->isNewRecord ? 0 : ProductHasFeature::model()->exists($criteria);
        ?>
        <label class="checkbox">
            <input name="feature[]" <?php if ($match) echo ' checked="checked" '?> type="checkbox" value="<?php echo $feature->id; ?>"><?php echo $feature->name; ?>
            </label>
        <?php endforeach; ?>
    </div><!-- /span -->

</div><!-- /row -->

	<div class="form-actions">
		<?php $this->widget('bootstrap.widgets.TbButton', array(
			'buttonType'=>'submit',
			'type'=>'primary',
			'label'=>$model->isNewRecord ? 'Create' : 'Save',
		)); ?>
	</div>
