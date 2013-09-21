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

<h1>Create Ws</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>