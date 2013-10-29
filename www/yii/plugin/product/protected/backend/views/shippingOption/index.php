<?php
$this->breadcrumbs=array(
	'Shipping Options',
);

$this->menu=array(
	array('label'=>'Create ShippingOption','url'=>array('create')),
	array('label'=>'Manage ShippingOption','url'=>array('admin')),
);
?>

<h1>Shipping Options</h1>

<?php $this->widget('bootstrap.widgets.TbListView',array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
