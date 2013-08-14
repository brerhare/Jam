<?php

$this->menu=array(
	array('label'=>'Create Department','url'=>array('create')),
);
?>


<h2>Manage Departments</h2>

<?php $this->widget('bootstrap.widgets.TbGridView',array(
	'id'=>'department-grid',
	'dataProvider'=>$model->search(),
	//'filter'=>$model,
	'columns'=>array(
		//'id',
		//'uid',
		'name',
		array(
			'class'=>'bootstrap.widgets.TbButtonColumn',
		),
	),
)); ?>
