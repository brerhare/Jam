<?php

if ($updateMode == "update")
{
	$this->menu=array(
		array('label'=>'Manage Events','url'=>array('admin')),
	);
	echo "<h4>Update Event " . $model->title . "</h4>";
}
else
	echo "<h4>View Event " . $model->title . "</h4>";
?>

<?php echo $this->renderPartial('_form',array('model'=>$model, 'model2'=>$model2, 'updateMode'=>$updateMode, 'ticketUid' => $ticketUid  )); ?>
