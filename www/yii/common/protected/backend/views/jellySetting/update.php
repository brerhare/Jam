<?php
$this->breadcrumbs=array(
	'Jelly Settings'=>array('index'),
	$model->id=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List JellySetting','url'=>array('index')),
	array('label'=>'Create JellySetting','url'=>array('create')),
	array('label'=>'View JellySetting','url'=>array('view','id'=>$model->id)),
	array('label'=>'Manage JellySetting','url'=>array('admin')),
);
?>

<h1>Update JellySetting <?php echo $model->id; ?></h1>

<?php echo $this->renderPartial('_form',array('model'=>$model)); ?>