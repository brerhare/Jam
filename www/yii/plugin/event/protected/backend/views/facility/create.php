<?php
$this->breadcrumbs=array(
	'Facilities'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List Facility','url'=>array('index')),
	array('label'=>'Manage Facility','url'=>array('admin')),
);
?>

<h4>Create Facility</h4>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>
