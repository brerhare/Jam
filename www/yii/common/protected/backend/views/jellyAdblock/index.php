<?php
$this->breadcrumbs=array(
	'Jelly Adblocks',
);

$this->menu=array(
	array('label'=>'Create JellyAdblock','url'=>array('create')),
	array('label'=>'Manage JellyAdblock','url'=>array('admin')),
);
?>

<h1>Jelly Adblocks</h1>

<?php $this->widget('bootstrap.widgets.TbListView',array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
