<div class="form">
    <?php $form=$this->beginWidget('bootstrap.widgets.TbActiveForm',array(
        'id'=>'product-form',
        'enableAjaxValidation'=>false,
        'type'=>'horizontal',
    )); ?>

<?php

$this->menu=array(
	array('label'=>'Manage Products','url'=>array('admin')),
);
?>

<?php
$criteria = new CDbCriteria;
$criteria->addCondition("uid = " . Yii::app()->session['uid']);
$criteria->addCondition("id = " . Yii::app()->session['department_id']);
$department = Department::model()->find($criteria);
?>

<h2>Change Department - ALL Products in <?php echo $department->name;?></h2>

	<div style='color:red'>
	<b>NB: Order Options and Features are specific to each department<br>
	   After moving all these products to another department those will have to be input again</b>
	</div>
	<p>

	<!-- @@EG How to line up custom content -->
	<div class="control-group "><label class="control-label" for="New_department">New Department <span class="required">*</span></label>
		<div class="controls">
			<!-- @@EG Dropdownlist based on some other model (best example) -->
			<?php // retrieve all the departments
				$criteria = new CDbCriteria;
				$criteria->addCondition("uid = " . Yii::app()->session['uid']);
				$criteria->addCondition("id != " . Yii::app()->session['department_id']);
				$criteria->order='name';
				$departments = Department::model()->findAll($criteria);
				// format models as $key=>$value with listData
				$list = CHtml::listData($departments, 'id', 'name');
				//                        name          selector for 'selected='       value/display
				echo CHtml::dropDownList('departments', 'select', $list);
			?>
		</div>
	</div>

	<div class="form-actions">
		<?php $this->widget('bootstrap.widgets.TbButton', array(
			'buttonType'=>'submit',
			'type'=>'primary',
			'label'=>'Save',
		)); ?>
	</div>

    <?php $this->endWidget(); ?>
</div><!-- form -->

