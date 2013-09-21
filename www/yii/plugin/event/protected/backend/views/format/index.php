<?php
$this->breadcrumbs=array(
	'Formats',
);

$this->menu=array(
	array('label'=>'Create Format','url'=>array('create')),
	array('label'=>'Manage Format','url'=>array('admin')),
);
?>

<h1>Formats</h1>

<?php $this->widget('bootstrap.widgets.TbListView',array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
