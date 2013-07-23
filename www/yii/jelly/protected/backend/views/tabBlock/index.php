<?php
$this->breadcrumbs=array(
	'Tab Content',
);

$this->menu=array(
	array('label'=>'Create Tab Content','url'=>array('create')),
	array('label'=>'Manage Tab Content','url'=>array('admin')),
);
?>

<h1>Tab Content</h1>

<?php $this->widget('bootstrap.widgets.TbListView',array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
