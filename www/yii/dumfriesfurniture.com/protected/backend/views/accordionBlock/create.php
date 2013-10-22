<?php
$this->breadcrumbs=array(
	'Accordion Blocks'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List AccordionBlock','url'=>array('index')),
	array('label'=>'Manage AccordionBlock','url'=>array('admin')),
);
?>

<h1>Create AccordionBlock</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>