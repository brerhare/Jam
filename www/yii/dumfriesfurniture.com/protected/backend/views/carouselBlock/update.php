<?php

$this->menu=array(
	array('label'=>'Manage Slider Content','url'=>array('admin')),
);
?>

<h1>Update Slider Content <?php //echo $model->id; ?></h1>

<?php echo $this->renderPartial('_form',array('model'=>$model)); ?>
