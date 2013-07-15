<?php

$this->menu=array(
	array('label'=>'Manage Carousel Blocks','url'=>array('admin')),
);
?>

<h1>Create Carousel Block</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>
