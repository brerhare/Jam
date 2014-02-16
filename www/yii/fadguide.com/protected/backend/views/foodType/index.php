<?php
$this->breadcrumbs=array(
	'Food Types',
);

$this->menu=array(
	array('label'=>'Create FoodType','url'=>array('create')),
	array('label'=>'Manage FoodType','url'=>array('admin')),
);
?>

<h1>Food Types</h1>

<?php $this->widget('bootstrap.widgets.TbListView',array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
