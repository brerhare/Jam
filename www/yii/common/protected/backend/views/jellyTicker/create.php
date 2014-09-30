<?php
$this->breadcrumbs=array(
	'Jelly Tickers'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List JellyTicker','url'=>array('index')),
	array('label'=>'Manage JellyTicker','url'=>array('admin')),
);
?>

<h1>Create JellyTicker</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>