<style>
.control-group{ margin-bottom:3px !important;}
</style>

<?php echo $form->errorSummary($model); ?>

<style>
    table { table-layout: fixed; }
    td { width: 16%; }
</style>

<div class="row">
    <div class="span7">
    <table class="Xtable Xtable-bordered">
        <thead>
        <tr>
	        <th style="width:40%">Option</th>
            <th>Price</th>
            <th>POA</th>
            <th>Default?</th>
        </tr>
        </thead>
        <tbody>
        <?php
		// Show ALL product options
		$criteria = new CDbCriteria;
		$criteria->addCondition("uid = " . Yii::app()->session['uid']);
		$criteria->addCondition("product_department_id = " . Yii::app()->session['department_id']);
		$options = Option::model()->findAll($criteria);
		foreach ($options as $option):
	        echo "<tr>";
			echo "<td>" . $option->name . "</td>";

			// Show product-has-option (unless in 'create' mode)
			$criteria = new CDbCriteria;
			$criteria->addCondition("product_product_id = " . $model->id);
			$criteria->addCondition("product_option_id = " . $option->id);
			$productHasOption = $model->isNewRecord ? null : ProductHasOption::model()->find($criteria);
			$price = ($productHasOption == null) ? "" : " value='" . $productHasOption->price . "' ";
			if ($price == " value='0.00' ") $price = "";
	        echo "<td><input style='text-align:right;width:75px;' type='text' name='" . $option->id . "_price' " . $price . " ></td>";


echo "<input type='hidden' name='" . $option->id . "_poa' value='off'>";
	        $checked ="none";
	        if (($productHasOption) && ($productHasOption->is_poa == 1))
	        	$checked = " checked='checked' ";
	        echo "<td><input style='text-align:right' type='checkbox' name='" . $option->id . "_poa' " . $checked . " ></td>";


	        $checked ="";
	        if (($productHasOption) && ($productHasOption->is_default == 1))
	        	$checked = " checked='checked' ";
	        echo "<td><input style='text-align:right' type='radio' name='default' onChange=setDefault('" . $option->id . "');" . $checked . " ></td>";
	        echo "</tr>";

		endforeach; ?>

        </tbody>
    </table>
	</div>
</div>

	<div class="form-actions">
		<?php $this->widget('bootstrap.widgets.TbButton', array(
			'buttonType'=>'submit',
			'type'=>'primary',
			'label'=>$model->isNewRecord ? 'Create' : 'Save',
		)); ?>
	</div>

<script>
function setDefault(optionId)
{
	document.getElementById('defaultOption').value = optionId;
	//alert(document.getElementById('defaultOption').value);
}
</script>
