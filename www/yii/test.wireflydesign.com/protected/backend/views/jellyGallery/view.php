<?php
$this->breadcrumbs=array(
	'Jelly Galleries'=>array('index'),
	$model->title,
);

$this->menu=array(
	array('label'=>'List JellyGallery','url'=>array('index')),
	array('label'=>'Create JellyGallery','url'=>array('create')),
	array('label'=>'Update JellyGallery','url'=>array('update','id'=>$model->id)),
	array('label'=>'Delete JellyGallery','url'=>'#','linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage JellyGallery','url'=>array('admin')),
);
?>

<h1>View JellyGallery #<?php echo $model->id; ?></h1>

<?php $this->widget('bootstrap.widgets.TbDetailView',array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'sequence',
		'title',
		'image',
	),
)); ?>
