<?php
/*
$this->breadcrumbs=array(
	'Areas'=>array('index'),
	'Create',
);
*/

$this->menu=array(
//	array('label'=>'List Area','url'=>array('index')),
	array('label'=>'Manage Areas','url'=>array('admin')),
);
?>

<h2>Create Area</h2>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>