	<div style='color:red'>
	<b>NB: Order Options and Features are specific to each department<br>
	   Moving a product to another department will erase that information</b> 
	</div>
	<p>

	<!-- @@EG How to line up custom content -->
	<div class="control-group "><label class="control-label" for="New_department">New Department <span class="required">*</span></label>
		<div class="controls">
			<!-- @@EG Dropdownlist based on some other model (best example) -->
			<?php // retrieve all the departments
				$criteria = new CDbCriteria;
				$criteria->addCondition("uid = " . Yii::app()->session['uid']);
				$criteria->order='name';
				$departments = Department::model()->findAll($criteria);
				// format models as $key=>$value with listData
				$list = CHtml::listData($departments, 'id', 'name');
				//                        name          selector for 'selected='       value/display
				echo CHtml::dropDownList('departments', $model->product_department_id, $list);
			?>
		</div>
	</div>

	<div class="form-actions">
		<?php $this->widget('bootstrap.widgets.TbButton', array(
			'buttonType'=>'submit',
			'type'=>'primary',
			'label'=>$model->isNewRecord ? 'Create' : 'Save',
		)); ?>
	</div>

