<?php

$this->menu=array(
	array('label'=>'Manage Options','url'=>array('admin')),
);
?>

<h2>Create Option</h2>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>
