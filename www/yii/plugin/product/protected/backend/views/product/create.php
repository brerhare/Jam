<?php

$this->menu=array(
	array('label'=>'Manage Products','url'=>array('admin')),
);
?>

<h2>Create Product</h2>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>
