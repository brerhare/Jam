<?php
$this->breadcrumbs=array(
	'Program Fields'=>array('index'),
	$model->id=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List ProgramFields','url'=>array('index')),
	array('label'=>'Create ProgramFields','url'=>array('create')),
	array('label'=>'View ProgramFields','url'=>array('view','id'=>$model->id)),
	array('label'=>'Manage ProgramFields','url'=>array('admin')),
);
?>

<h1>Update ProgramFields <?php echo $model->id; ?></h1>

<?php echo $this->renderPartial('_form',array('model'=>$model)); ?>