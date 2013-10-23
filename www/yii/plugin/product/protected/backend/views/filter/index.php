<?php
$this->breadcrumbs=array(
	'Filters',
);

$this->menu=array(
	array('label'=>'Create Filter','url'=>array('create')),
	array('label'=>'Manage Filter','url'=>array('admin')),
);
?>

<h1>Filters</h1>

<?php $this->widget('bootstrap.widgets.TbListView',array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
