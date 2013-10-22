<?php
$this->breadcrumbs=array(
	'Accordion Blocks'=>array('index'),
	$model->title,
);

$this->menu=array(
	array('label'=>'List AccordionBlock','url'=>array('index')),
	array('label'=>'Create AccordionBlock','url'=>array('create')),
	array('label'=>'Update AccordionBlock','url'=>array('update','id'=>$model->id)),
	array('label'=>'Delete AccordionBlock','url'=>'#','linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage AccordionBlock','url'=>array('admin')),
);
?>

<h1>View AccordionBlock #<?php echo $model->id; ?></h1>

<?php $this->widget('bootstrap.widgets.TbDetailView',array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'sequence',
		'title',
		'url',
		'content',
		'image',
	),
)); ?>
