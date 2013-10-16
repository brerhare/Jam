<?php
$this->breadcrumbs=array(
	'Duration Bands',
);

$this->menu=array(
	array('label'=>'Create DurationBand','url'=>array('create')),
	array('label'=>'Manage DurationBand','url'=>array('admin')),
);
?>

<h1>Duration Bands</h1>

<?php $this->widget('bootstrap.widgets.TbListView',array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
