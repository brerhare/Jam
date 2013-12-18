<?php
$this->breadcrumbs=array(
	'Jelly Gallery Images'=>array('index'),
	$model->id=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List JellyGalleryImage','url'=>array('index')),
	array('label'=>'Create JellyGalleryImage','url'=>array('create')),
	array('label'=>'View JellyGalleryImage','url'=>array('view','id'=>$model->id)),
	array('label'=>'Manage JellyGalleryImage','url'=>array('admin')),
);
?>

<h1>Update JellyGalleryImage <?php echo $model->id; ?></h1>

<?php echo $this->renderPartial('_form',array('model'=>$model)); ?>