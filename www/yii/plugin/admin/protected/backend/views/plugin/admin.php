<?php

$this->menu=array(
	array('label'=>'Create Plugin','url'=>array('create')),
);
?>

<h1>Manage Plugins</h1>

<?php $this->widget('bootstrap.widgets.TbGridView',array(
	'id'=>'plugin-grid',
	'dataProvider'=>$model->search(),
	// 'filter'=>$model,
	'columns'=>array(
		'id',
		'description',
		'container_url',
		'container_width',
		'container_height',
		array(
			'class'=>'bootstrap.widgets.TbButtonColumn',
			'template'=>'{update}{delete}',
		),
	),
)); ?>
