<?php
$this->menu=array(
	array('label'=>'Back to Manage Departments', 'url'=>array('department/admin')),
	array('label'=>'Create Option','url'=>array('create')),
);
?>

<h2>Manage Options for
<?php
    $model_department = Department::model()->findByPk(Yii::app()->session['department_id']);
    echo $model_department->name;
?>
</h2>

<?php $this->widget('bootstrap.widgets.TbGridView',array(
	'id'=>'option-grid',
	'dataProvider'=>$model->search(),
	//'filter'=>$model,
	'columns'=>array(
		//'id',
		//'uid',
		'name',
		//'product_department_id',
		array(
			'class'=>'bootstrap.widgets.TbButtonColumn',
			'template'=>'{update}{delete}',
		),
	),
)); ?>