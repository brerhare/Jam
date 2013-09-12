<?php

$this->menu=array(
	array('label'=>'Manage Carousel Content','url'=>array('admin')),
);
?>

<h1>Update Carousel Content <?php //echo $model->id; ?></h1>

<?php echo $this->renderPartial('_form',array('model'=>$model)); ?>
