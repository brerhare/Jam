<?php
$this->breadcrumbs=array(
	'Price Bands',
);

$this->menu=array(
	array('label'=>'Create PriceBand','url'=>array('create')),
	array('label'=>'Manage PriceBand','url'=>array('admin')),
);
?>

<h4>Price Bands</h4>

<?php $this->widget('bootstrap.widgets.TbListView',array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
