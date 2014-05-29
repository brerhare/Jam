<?php
$this->breadcrumbs=array(
	'Price Bands'=>array('index'),
	$model->name=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List PriceBand','url'=>array('index')),
	array('label'=>'Create PriceBand','url'=>array('create')),
	array('label'=>'View PriceBand','url'=>array('view','id'=>$model->id)),
	array('label'=>'Manage PriceBand','url'=>array('admin')),
);
?>

<h4>Update PriceBand <?php echo $model->id; ?></h4>

<?php echo $this->renderPartial('_form',array('model'=>$model)); ?>
