<?php
$this->menu=array(
	array('label'=>'Manage Durations','url'=>array('admin')),
);
?>

<h2>Create Duration</h2>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>