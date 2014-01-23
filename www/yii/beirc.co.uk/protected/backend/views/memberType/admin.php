<?php

$this->menu=array(
	array('label'=>'Create Member Type','url'=>array('create')),
);

?>

<h2>Manage Member Types</h2>

<?php $this->widget('bootstrap.widgets.TbGridView',array(
	'id'=>'member-type-grid',
	'dataProvider'=>$model->search(),
	//'filter'=>$model,
	'columns'=>array(
		//'id',
		'description',
		'slots',
		'days',
		array(
			'class'=>'bootstrap.widgets.TbButtonColumn',
		),
	),
)); ?>
