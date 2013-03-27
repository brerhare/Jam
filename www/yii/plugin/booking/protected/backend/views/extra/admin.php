<?php

$this->menu=array(
	array('label'=>'Create Extra','url'=>array('create')),
);

?>

<h1>Manage Extras</h1>

<?php $this->widget('bootstrap.widgets.TbGridView',array(
	'id'=>'extra-grid',
	'dataProvider'=>$model->search(),
	//'filter'=>$model,
	'columns'=>array(
		'id',
		// 'uid',
		'description',
		'daily_rate',
		'once_rate',
		array(
			'class'=>'bootstrap.widgets.TbButtonColumn',
			'template'=>'{update}{delete}',
		),
	),
)); ?>
