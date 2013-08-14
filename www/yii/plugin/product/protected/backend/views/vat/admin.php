<?php

$this->menu=array(
	array('label'=>'Create Vat Rate','url'=>array('create')),
);

?>

<h1>Manage Vat Rates</h1>


<?php $this->widget('bootstrap.widgets.TbGridView',array(
	'id'=>'vat-grid',
	'dataProvider'=>$model->search(),
	//'filter'=>$model,
	'columns'=>array(
		//id',
		//'uid',
		'description',
		'rate',
		array(
			'class'=>'bootstrap.widgets.TbButtonColumn',
		),
	),
)); ?>
