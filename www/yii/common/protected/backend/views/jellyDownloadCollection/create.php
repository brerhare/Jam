<?php

$this->menu=array(
	array('label'=>'Manage Download Collections','url'=>array('admin')),
);
?>

<h2>Create Download Collection</h2>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>
