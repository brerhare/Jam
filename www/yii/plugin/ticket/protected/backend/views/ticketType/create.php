<?php
$this->breadcrumbs=array(
	'Ticket Types'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List TicketType','url'=>array('index')),
	array('label'=>'Manage TicketType','url'=>array('admin')),
);
?>

<h1>Create TicketType</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>