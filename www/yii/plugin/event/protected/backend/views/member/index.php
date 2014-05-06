<?php
$this->breadcrumbs=array(
	'Members',
);

$this->menu=array(
	array('label'=>'Create Member','url'=>array('create')),
	array('label'=>'Manage Member','url'=>array('admin')),
);
?>

<h4>Members</h4>

<?php $this->widget('bootstrap.widgets.TbListView',array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
