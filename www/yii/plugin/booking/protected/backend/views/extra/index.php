<?php
$this->breadcrumbs=array(
	'Extras',
);

$this->menu=array(
	array('label'=>'Create Extra','url'=>array('create')),
	array('label'=>'Manage Extra','url'=>array('admin')),
);
?>

<h1>Extras</h1>

<?php $this->widget('bootstrap.widgets.TbListView',array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
