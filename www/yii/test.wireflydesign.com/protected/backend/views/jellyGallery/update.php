<?php
$this->breadcrumbs=array(
	'Jelly Galleries'=>array('index'),
	$model->title=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List JellyGallery','url'=>array('index')),
	array('label'=>'Create JellyGallery','url'=>array('create')),
	array('label'=>'View JellyGallery','url'=>array('view','id'=>$model->id)),
	array('label'=>'Manage JellyGallery','url'=>array('admin')),
);
?>

<h1>Update JellyGallery <?php echo $model->id; ?></h1>

<?php echo $this->renderPartial('_form',array('model'=>$model)); ?>