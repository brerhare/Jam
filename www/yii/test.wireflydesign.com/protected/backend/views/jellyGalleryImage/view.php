<?php
$this->breadcrumbs=array(
	'Jelly Gallery Images'=>array('index'),
	$model->id,
);

$this->menu=array(
	array('label'=>'List JellyGalleryImage','url'=>array('index')),
	array('label'=>'Create JellyGalleryImage','url'=>array('create')),
	array('label'=>'Update JellyGalleryImage','url'=>array('update','id'=>$model->id)),
	array('label'=>'Delete JellyGalleryImage','url'=>'#','linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage JellyGalleryImage','url'=>array('admin')),
);
?>

<h1>View JellyGalleryImage #<?php echo $model->id; ?></h1>

<?php $this->widget('bootstrap.widgets.TbDetailView',array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'sequence',
		'text',
		'image',
		'url',
		'jelly_gallery_id',
	),
)); ?>
