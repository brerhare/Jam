<?php
$this->breadcrumbs=array(
	'Price Bands'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List PriceBand','url'=>array('index')),
	array('label'=>'Manage PriceBand','url'=>array('admin')),
);
?>

<h4>Create PriceBand</h4>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>
