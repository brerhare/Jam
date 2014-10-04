<?php

$this->menu=array(
	array('label'=>'Create Ticker','url'=>array('create')),
);
?>

<h2>Manage Tickers</h2>

<?php $this->widget('bootstrap.widgets.TbGridView',array(
	'id'=>'jelly-ticker-grid',
	'dataProvider'=>$model->search(),
	//'filter'=>$model,
	'columns'=>array(
		//'id',
		//'ticker',
		'heading',
		'text',
		array(
			'class'=>'bootstrap.widgets.TbButtonColumn',
		),
	),
)); ?>
