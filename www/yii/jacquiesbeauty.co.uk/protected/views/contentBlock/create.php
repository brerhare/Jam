<?php
$this->breadcrumbs=array(
	'Content Blocks'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List ContentBlock','url'=>array('index')),
	array('label'=>'Manage ContentBlock','url'=>array('admin')),
);
?>

<h1>Create ContentBlock</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>