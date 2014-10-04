<?php

$this->menu=array(
	array('label'=>'Manage Slider Content','url'=>array('admin')),
);
?>

<h2>Create Slider Content</h2>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>
