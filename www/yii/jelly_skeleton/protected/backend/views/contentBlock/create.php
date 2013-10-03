<?php

$this->menu=array(
	array('label'=>'Manage Page Content','url'=>array('admin')),
);
?>

<h1>Create Page Content</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>
