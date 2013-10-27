<?php

$this->menu=array(
	array('label'=>'Manage Events','url'=>array('admin')),
);
?>

<h1>Update Event <?php echo $model->title; ?></h1>
<?php echo $this->renderPartial('_form',array('model'=>$model, 'model2'=>$model2, 'ticketUid' => $ticketUid  )); ?>