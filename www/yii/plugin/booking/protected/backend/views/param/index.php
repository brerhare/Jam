<?php
$this->breadcrumbs=array(
	'Params',
);

$this->menu=array(
	array('label'=>'Create Param','url'=>array('create')),
	array('label'=>'Manage Param','url'=>array('admin')),
);
?>

<h1>Params</h1>

<?php $this->widget('bootstrap.widgets.TbListView',array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
