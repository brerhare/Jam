<?php
$this->breadcrumbs=array(
	'Interests',
);

$this->menu=array(
	array('label'=>'Create Interest','url'=>array('create')),
	array('label'=>'Manage Interest','url'=>array('admin')),
);
?>

<h4>Interests</h4>

<?php $this->widget('bootstrap.widgets.TbListView',array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
