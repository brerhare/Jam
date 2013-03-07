<?php
$this->breadcrumbs=array(
	'Plugins',
);

$this->menu=array(
	array('label'=>'Create Plugin','url'=>array('create')),
	array('label'=>'Manage Plugin','url'=>array('admin')),
);
?>

<h1>Plugins</h1>

<?php $this->widget('bootstrap.widgets.TbListView',array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
