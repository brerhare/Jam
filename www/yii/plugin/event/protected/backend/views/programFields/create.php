<?php
$this->breadcrumbs=array(
	'Program Fields'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List ProgramFields','url'=>array('index')),
	array('label'=>'Manage ProgramFields','url'=>array('admin')),
);
?>

<h4>Create ProgramFields</h4>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>
