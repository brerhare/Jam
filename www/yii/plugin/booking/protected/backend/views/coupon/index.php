<?php
$this->breadcrumbs=array(
	'Coupons',
);

$this->menu=array(
	array('label'=>'Create Coupon','url'=>array('create')),
	array('label'=>'Manage Coupon','url'=>array('admin')),
);
?>

<h1>Coupons</h1>

<?php $this->widget('bootstrap.widgets.TbListView',array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
