<?php

$this->menu=array(
	array('label'=>'Create Facility','url'=>array('create')),
);
?>

<h1>Manage Facilities</h1>

<?php $this->widget('bootstrap.widgets.TbGridView',array(
	'id'=>'facility-grid',
	'dataProvider'=>$model->search(),
	// 'filter'=>$model,
	'columns'=>array(
		'id',
		// 'uid',
		'description',
		array(
			'class'=>'bootstrap.widgets.TbButtonColumn',
			'template'=>'{update}{delete}',
		),
	),
)); ?>
