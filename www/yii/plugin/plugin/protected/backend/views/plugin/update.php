<?php
/* @var $this PluginController */
/* @var $model Plugin */

$this->breadcrumbs=array(
	'Plugins'=>array('index'),
	$model->id=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List Plugin', 'url'=>array('index')),
	array('label'=>'Create Plugin', 'url'=>array('create')),
	array('label'=>'View Plugin', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage Plugin', 'url'=>array('admin')),
);
?>

<h1>Update Plugin <?php echo $model->id; ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>