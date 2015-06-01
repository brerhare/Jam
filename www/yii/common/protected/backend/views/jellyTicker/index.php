<?php
$this->breadcrumbs=array(
	'Jelly Tickers',
);

$this->menu=array(
	array('label'=>'Create JellyTicker','url'=>array('create')),
	array('label'=>'Manage JellyTicker','url'=>array('admin')),
);
?>

<h1>Jelly Tickers</h1>

<?php $this->widget('bootstrap.widgets.TbListView',array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
