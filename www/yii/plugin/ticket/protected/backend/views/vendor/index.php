<?php
$this->breadcrumbs=array(
	'Vendors',
);

$this->menu=array(
	array('label'=>'Create Vendor','url'=>array('create')),
	array('label'=>'Manage Vendor','url'=>array('admin')),
);
?>

<h1>Vendors</h1>

<?php $this->widget('bootstrap.widgets.TbListView',array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
