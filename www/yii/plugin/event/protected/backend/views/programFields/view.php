<?php
$this->breadcrumbs=array(
	'Program Fields'=>array('index'),
	$model->id,
);

$this->menu=array(
	array('label'=>'List ProgramFields','url'=>array('index')),
	array('label'=>'Create ProgramFields','url'=>array('create')),
	array('label'=>'Update ProgramFields','url'=>array('update','id'=>$model->id)),
	array('label'=>'Delete ProgramFields','url'=>'#','linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage ProgramFields','url'=>array('admin')),
);
?>

<h4>View ProgramFields #<?php echo $model->id; ?></h4>

<?php $this->widget('bootstrap.widgets.TbDetailView',array(
	'data'=>$model,
	'attributes'=>array(
		'id',
	),
)); ?>
