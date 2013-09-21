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

<h1>Create PriceBand</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>