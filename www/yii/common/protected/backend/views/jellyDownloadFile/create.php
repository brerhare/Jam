<?php

$this->menu=array(
	array('label'=>'Manage Download Files','url'=>array('admin')),
);
?>

<h2>Create Download File</h2>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>
