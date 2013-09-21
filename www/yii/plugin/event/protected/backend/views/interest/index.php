<?php
$this->breadcrumbs=array(
	'Interests',
);

$this->menu=array(
	array('label'=>'Create Interest','url'=>array('create')),
	array('label'=>'Manage Interest','url'=>array('admin')),
);
?>

<h1>Interests</h1>

<?php $this->widget('bootstrap.widgets.TbListView',array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
