<?php
/* @var $this VendorController */
/* @var $model Vendor */

/*
$this->breadcrumbs=array(
	'Vendors'=>array('index'),
	$model->name=>array('view','id'=>$model->id),
	'Update',
);*/

/*
$this->menu=array(
	array('label'=>'List Vendor', 'url'=>array('index')),
	array('label'=>'Create Vendor', 'url'=>array('create')),
	array('label'=>'View Vendor', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage Vendor', 'url'=>array('admin')),
); */
?>

<h3>Update Vendor</h3>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>