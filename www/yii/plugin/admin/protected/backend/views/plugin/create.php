<?php
$this->breadcrumbs=array(
	'Plugins'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List Plugin','url'=>array('index')),
	array('label'=>'Manage Plugin','url'=>array('admin')),
);
?>

<h1>Create Plugin</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>