<?php

$this->menu=array(
	array('label'=>'Manage Carousel Blocks','url'=>array('admin')),
);
?>

<h1>Update Carousel Block <?php //echo $model->id; ?></h1>

<?php echo $this->renderPartial('_form',array('model'=>$model)); ?>
