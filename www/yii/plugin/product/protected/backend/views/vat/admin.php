<?php

$this->menu=array(
	array('label'=>'Create Vat Rate','url'=>array('create')),
);

?>

<h2>Manage Vat Rates</h2>


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
			'template'=>'{update}{delete}',
		),
	),
)); ?>
