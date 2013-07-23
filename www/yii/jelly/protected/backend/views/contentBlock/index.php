<?php
$this->menu=array(
	array('label'=>'Create Page Content','url'=>array('create')),
	array('label'=>'Manage Page Content','url'=>array('admin')),
);
?>

<h1>Page Content</h1>

<?php $this->widget('bootstrap.widgets.TbListView',array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
