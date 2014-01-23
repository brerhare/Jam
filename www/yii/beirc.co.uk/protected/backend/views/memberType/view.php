<?php
$this->breadcrumbs=array(
	'Member Types'=>array('index'),
	$model->id,
);

$this->menu=array(
	array('label'=>'List MemberType','url'=>array('index')),
	array('label'=>'Create MemberType','url'=>array('create')),
	array('label'=>'Update MemberType','url'=>array('update','id'=>$model->id)),
	array('label'=>'Delete MemberType','url'=>'#','linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage MemberType','url'=>array('admin')),
);
?>

<h1>View MemberType #<?php echo $model->id; ?></h1>

<?php $this->widget('bootstrap.widgets.TbDetailView',array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'description',
		'slots',
		'days',
	),
)); ?>
