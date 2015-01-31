<?php

$this->menu=array(
	array('label'=>'Manage Events','url'=>array('admin')),
);
?>

<h4>Create Event</h4>
<?php echo $this->renderPartial('_form', array('model'=>$model, 'model2'=>$model2, 'updateMode'=>$updateMode, 'ticketUid' => $ticketUid  )); ?>
