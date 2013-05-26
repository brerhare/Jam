<?php

$this->menu=array(
	array('label'=>'Create Coupon','url'=>array('create')),
);

?>

<h1>Manage Coupons</h1>

<?php $this->widget('bootstrap.widgets.TbGridView',array(
	'id'=>'coupon-grid',
	'dataProvider'=>$model->search(),
	//'filter'=>$model,
	'columns'=>array(
		'id',
		//'uid',
		'code',
		'description',
		'type',
		'value',
		array(
			'class'=>'bootstrap.widgets.TbButtonColumn',
		),
	),
)); ?>
