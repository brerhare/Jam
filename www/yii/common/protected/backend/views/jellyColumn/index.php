<?php
$this->menu=array(
	array('label'=>'Create Column Content','url'=>array('create')),
	array('label'=>'Manage Column Content','url'=>array('admin')),
);
?>

<h1>Column Content</h1>

<?php $this->widget('bootstrap.widgets.TbListView',array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
