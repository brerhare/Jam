<?php

$this->menu=array(
	array('label'=>'Manage Food Types','url'=>array('admin')),
);
?>

<h2>Create Food Type</h2>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>
