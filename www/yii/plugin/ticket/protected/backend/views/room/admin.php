<?php

$this->menu=array(
	array('label'=>'Create Room','url'=>array('create')),
);

?>

<h1>Manage Rooms</h1>

<?php $this->widget('bootstrap.widgets.TbGridView',array(
	'id'=>'room-grid',
	'dataProvider'=>$model->search(),
	// 'filter'=>$model,
	'columns'=>array(
		'id',
		// 'uid',
		'title',
		'description',
		'qty',
		/*
		'max_adult',
		'max_child',
		'max_total',
		*/
		array(
			'class'=>'bootstrap.widgets.TbButtonColumn',
			'template'=>'{update}{delete}',
		),
	),
)); ?>
