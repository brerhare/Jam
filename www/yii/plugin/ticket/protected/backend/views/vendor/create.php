<?php
/*$this->breadcrumbs=array(
	'Vendors'=>array('index'),
	'Create',
);*/

$this->menu=array(
	array('label'=>'List Vendor','url'=>array('index')),
	array('label'=>'Manage Vendor','url'=>array('admin')),
);
?>

<h2>Vendor Details</h2>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>
