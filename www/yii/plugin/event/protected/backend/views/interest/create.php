<?php
$this->breadcrumbs=array(
	'Interests'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List Interest','url'=>array('index')),
	array('label'=>'Manage Interest','url'=>array('admin')),
);
?>

<h4>Create Interest</h4>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>
