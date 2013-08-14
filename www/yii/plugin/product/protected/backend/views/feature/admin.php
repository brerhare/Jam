<?php
$this->menu=array(
	array('label'=>'Create Feature','url'=>array('create')),
);
?>

<h2>Manage Features</h2>

<?php $this->widget('bootstrap.widgets.TbGridView',array(
	'id'=>'feature-grid',
	'dataProvider'=>$model->search(),
	//'filter'=>$model,
	'columns'=>array(
		//'id',
		//'uid',
		'name',
		'product_department_id',
		array(
			'class'=>'bootstrap.widgets.TbButtonColumn',
			'template'=>'{update}{delete}',
		),
	),
)); ?>
