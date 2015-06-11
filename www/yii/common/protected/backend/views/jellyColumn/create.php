<?php

$this->menu=array(
	array('label'=>'Manage Column Content','url'=>array('admin')),
);
?>

<h1>Create Column Content</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>
