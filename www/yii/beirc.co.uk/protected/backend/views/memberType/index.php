<?php

$this->menu=array(
	array('label'=>'Create MemberType','url'=>array('create')),
	array('label'=>'Manage MemberType','url'=>array('admin')),
);
?>

<h1>Member Types</h1>

<?php $this->widget('bootstrap.widgets.TbListView',array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
