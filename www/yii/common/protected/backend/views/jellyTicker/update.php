<?php
$this->breadcrumbs=array(
	'Jelly Tickers'=>array('index'),
	$model->id=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List JellyTicker','url'=>array('index')),
	array('label'=>'Create JellyTicker','url'=>array('create')),
	array('label'=>'View JellyTicker','url'=>array('view','id'=>$model->id)),
	array('label'=>'Manage JellyTicker','url'=>array('admin')),
);
?>

<h1>Update JellyTicker <?php echo $model->id; ?></h1>

<?php echo $this->renderPartial('_form',array('model'=>$model)); ?>