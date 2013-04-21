<?php
$this->breadcrumbs=array(
	'Ticket Types'=>array('index'),
	$model->id,
);

$this->menu=array(
	array('label'=>'List TicketType','url'=>array('index')),
	array('label'=>'Create TicketType','url'=>array('create')),
	array('label'=>'Update TicketType','url'=>array('update','id'=>$model->id)),
	array('label'=>'Delete TicketType','url'=>'#','linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage TicketType','url'=>array('admin')),
);
?>

<h1>View TicketType #<?php echo $model->id; ?></h1>

<?php $this->widget('bootstrap.widgets.TbDetailView',array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'uid',
		'description',
		'price',
		'places_per_ticket',
		'max_tickets_per_order',
		'ticket_event_id',
	),
)); ?>
