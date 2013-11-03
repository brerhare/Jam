<?php

$this->menu=array(
	array('label'=>'Manage Slider Content','url'=>array('admin')),
);
?>

<h1>Create Slider Content</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>
