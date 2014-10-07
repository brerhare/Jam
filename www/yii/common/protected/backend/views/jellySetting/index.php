<?php
$this->breadcrumbs=array(
	'Jelly Settings',
);

$this->menu=array(
	array('label'=>'Create JellySetting','url'=>array('create')),
	array('label'=>'Manage JellySetting','url'=>array('admin')),
);
?>

<h1>Jelly Settings</h1>

<?php $this->widget('bootstrap.widgets.TbListView',array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
