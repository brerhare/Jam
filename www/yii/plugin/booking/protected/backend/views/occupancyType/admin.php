<?php

$this->menu=array(
	array('label'=>'Create OccupancyType','url'=>array('create')),
);

?>

<h1>Manage Occupancy Types</h1>

<?php $this->widget('bootstrap.widgets.TbGridView',array(
	'id'=>'occupancy-type-grid',
	'dataProvider'=>$model->search(),
	// 'filter'=>$model,
	'columns'=>array(
		'id',
		// 'uid',
		'description',
		'is_default:boolean',	// @@EG: show 'Yes/No' for an INT field
		array(
			'class'=>'bootstrap.widgets.TbButtonColumn',
			'template'=>'{update}{delete}',
		),
	),
)); ?>
