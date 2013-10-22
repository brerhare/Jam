<?php
$this->breadcrumbs=array(
	'Accordion Blocks'=>array('index'),
	$model->title=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List AccordionBlock','url'=>array('index')),
	array('label'=>'Create AccordionBlock','url'=>array('create')),
	array('label'=>'View AccordionBlock','url'=>array('view','id'=>$model->id)),
	array('label'=>'Manage AccordionBlock','url'=>array('admin')),
);
?>

<h1>Update AccordionBlock <?php echo $model->id; ?></h1>

<?php echo $this->renderPartial('_form',array('model'=>$model)); ?>