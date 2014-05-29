<?php
$this->breadcrumbs=array(
	'Programs',
);

$this->menu=array(
	array('label'=>'Create Program','url'=>array('create')),
	array('label'=>'Manage Program','url'=>array('admin')),
);
?>

<h4>Programs</h4>

<?php $this->widget('bootstrap.widgets.TbListView',array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
