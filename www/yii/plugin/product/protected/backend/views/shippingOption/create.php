<?php

$this->menu=array(
	array('label'=>'Manage Shipping Options','url'=>array('admin')),
);
?>

<h2>Create Shipping Option</h2>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>