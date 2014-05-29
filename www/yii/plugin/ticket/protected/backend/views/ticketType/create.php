<?php
/*$this->breadcrumbs=array(
	'Ticket Types'=>array('index'),
	'Create',
);*/

$this->menu=array(
//	array('label'=>'List TicketType','url'=>array('index')),
	array('label'=>'Manage Ticket Types','url'=>array('admin')),
);
?>

<h4>Create Ticket Type</h4>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>
