<?php
$this->breadcrumbs=array(
	'Vats'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List Vat','url'=>array('index')),
	array('label'=>'Manage Vat','url'=>array('admin')),
);
?>

<h1>Create Vat</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>