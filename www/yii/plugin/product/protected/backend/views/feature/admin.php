<?php
$this->menu=array(
	array('label'=>'Create Feature','url'=>array('create')),
);
?>

<h1>Manage Features</h1>

<?php $this->widget('bootstrap.widgets.TbGridView',array(
	'id'=>'feature-grid',
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
