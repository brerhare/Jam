<?php
$this->breadcrumbs=array(
	'Facilities',
);

$this->menu=array(
	array('label'=>'Create Facility','url'=>array('create')),
	array('label'=>'Manage Facility','url'=>array('admin')),
);
?>

<h4>Facilities</h4>

<?php $this->widget('bootstrap.widgets.TbListView',array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
