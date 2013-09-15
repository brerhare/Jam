<?php

$this->menu=array(
	array('label'=>'Manage Images','url'=>array('admin')),
);
?>

<h2>Create Image</h2>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>
