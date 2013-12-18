<?php
$this->breadcrumbs=array(
	'Jelly Galleries',
);

$this->menu=array(
	array('label'=>'Create JellyGallery','url'=>array('create')),
	array('label'=>'Manage JellyGallery','url'=>array('admin')),
);
?>

<h1>Jelly Galleries</h1>

<?php $this->widget('bootstrap.widgets.TbListView',array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
