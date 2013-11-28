<?php
$this->breadcrumbs=array(
	'Slider Content',
);

$this->menu=array(
	array('label'=>'Create Slider Content','url'=>array('create')),
	array('label'=>'Manage Slider Content','url'=>array('admin')),
);
?>

<h1>Sliders</h1>

<?php $this->widget('bootstrap.widgets.TbListView',array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
