<?php
$this->breadcrumbs=array(
	'Ws',
);

$this->menu=array(
	array('label'=>'Create Ws','url'=>array('create')),
	array('label'=>'Manage Ws','url'=>array('admin')),
);
?>

<h4>Ws</h4>

<?php $this->widget('bootstrap.widgets.TbListView',array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
