<?php
$this->breadcrumbs=array(
	'Formats'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List Format','url'=>array('index')),
	array('label'=>'Manage Format','url'=>array('admin')),
);
?>

<h4>Create Format</h4>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>
