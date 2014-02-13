<?php

$this->menu=array(
	array('label'=>'Create Category','url'=>array('create')),
);

<h2>Manage Categories</h2>

<?php $this->widget('bootstrap.widgets.TbGridView',array(
	'id'=>'category-grid',
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
