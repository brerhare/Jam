<?php

$this->menu=array(
	array('label'=>'Manage Carousel Content','url'=>array('admin')),
);
?>

<h1>Create Carousel Content</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>
