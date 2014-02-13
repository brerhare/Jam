<?php

$this->menu=array(
	array('label'=>'Create Food Type','url'=>array('create')),
);

<h2>Manage Food Types</h2>

<?php $this->widget('bootstrap.widgets.TbGridView',array(
	'id'=>'food-type-grid',
	'dataProvider'=>$model->search(),
	//'filter'=>$model,
	'columns'=>array(
		'id',
		'name',
		array(
			'class'=>'bootstrap.widgets.TbButtonColumn',
		),
	),
)); ?>
