<?php
$this->breadcrumbs=array(
	'Vats'=>array('index'),
	$model->id=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List Vat','url'=>array('index')),
	array('label'=>'Create Vat','url'=>array('create')),
	array('label'=>'View Vat','url'=>array('view','id'=>$model->id)),
	array('label'=>'Manage Vat','url'=>array('admin')),
);
?>

<h1>Update Vat <?php echo $model->id; ?></h1>

<?php echo $this->renderPartial('_form',array('model'=>$model)); ?>