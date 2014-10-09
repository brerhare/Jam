<?php
$this->breadcrumbs=array(
	'Jelly Adblocks'=>array('index'),
	$model->id=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List JellyAdblock','url'=>array('index')),
	array('label'=>'Create JellyAdblock','url'=>array('create')),
	array('label'=>'View JellyAdblock','url'=>array('view','id'=>$model->id)),
	array('label'=>'Manage JellyAdblock','url'=>array('admin')),
);
?>

<h1>Update JellyAdblock <?php echo $model->id; ?></h1>

<?php echo $this->renderPartial('_form',array('model'=>$model)); ?>