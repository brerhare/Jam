<?php
$this->breadcrumbs=array(
	'Carousel Blocks',
);

$this->menu=array(
	array('label'=>'Create CarouselBlock','url'=>array('create')),
	array('label'=>'Manage CarouselBlock','url'=>array('admin')),
);
?>

<h1>Carousel Blocks</h1>

<?php $this->widget('bootstrap.widgets.TbListView',array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
