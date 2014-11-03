<?php
$this->breadcrumbs=array(
	'Ticket Types',
);

$this->menu=array(
	array('label'=>'Create TicketType','url'=>array('create')),
	array('label'=>'Manage TicketType','url'=>array('admin')),
);
?>

<h4>Ticket Types</h4>

<?php $this->widget('bootstrap.widgets.TbListView',array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
