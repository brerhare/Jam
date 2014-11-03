<?php

$this->menu=array(
	array('label'=>'Create Vendor','url'=>array('create')),
	array('label'=>'Manage Vendor','url'=>array('admin')),
);
?>

<h4>Vendors</h4>

<?php $this->widget('bootstrap.widgets.TbListView',array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
