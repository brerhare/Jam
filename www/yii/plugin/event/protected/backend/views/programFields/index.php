<?php
$this->breadcrumbs=array(
	'Program Fields',
);

$this->menu=array(
	array('label'=>'Create ProgramFields','url'=>array('create')),
	array('label'=>'Manage ProgramFields','url'=>array('admin')),
);
?>

<h4>Program Fields</h4>

<?php $this->widget('bootstrap.widgets.TbListView',array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
