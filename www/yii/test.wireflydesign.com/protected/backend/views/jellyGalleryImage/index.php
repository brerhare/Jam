<?php
$this->breadcrumbs=array(
	'Jelly Gallery Images',
);

$this->menu=array(
	array('label'=>'Create JellyGalleryImage','url'=>array('create')),
	array('label'=>'Manage JellyGalleryImage','url'=>array('admin')),
);
?>

<h1>Jelly Gallery Images</h1>

<?php $this->widget('bootstrap.widgets.TbListView',array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
