<?php
$this->breadcrumbs=array(
	'Ws'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List Ws','url'=>array('index')),
	array('label'=>'Manage Ws','url'=>array('admin')),
);
?>

<h4>Create Ws</h4>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>
