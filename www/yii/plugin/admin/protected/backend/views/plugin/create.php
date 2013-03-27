<?php

$this->menu=array(
	array('label'=>'Manage Plugin','url'=>array('admin')),
);
?>

<h1>Create Plugin</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>
