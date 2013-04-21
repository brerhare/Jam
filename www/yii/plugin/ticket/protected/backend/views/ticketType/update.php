<?php
/*$this->breadcrumbs=array(
	'Ticket Types'=>array('index'),
	$model->id=>array('view','id'=>$model->id),
	'Update',
);*/

$this->menu=array(
//	array('label'=>'List TicketType','url'=>array('index')),
//	array('label'=>'Create TicketType','url'=>array('create')),
//	array('label'=>'View TicketType','url'=>array('view','id'=>$model->id)),
	array('label'=>'Manage Ticket Types','url'=>array('admin')),
);
?>

<h2>Update TicketType <?php echo $model->description; ?></h2>

<?php echo $this->renderPartial('_form',array('model'=>$model)); ?>