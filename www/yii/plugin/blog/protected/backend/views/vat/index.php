<?php
$this->breadcrumbs=array(
	'Vats',
);

$this->menu=array(
	array('label'=>'Create Vat','url'=>array('create')),
	array('label'=>'Manage Vat','url'=>array('admin')),
);
?>

<h1>Vats</h1>

<?php $this->widget('bootstrap.widgets.TbListView',array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
