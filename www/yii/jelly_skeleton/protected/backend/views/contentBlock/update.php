<?php

$this->menu=array(
	array('label'=>'Manage Page Content','url'=>array('admin')),
);
?>

<h1>Update Page Content <?php //echo $model->id; ?></h1>

<?php echo $this->renderPartial('_form',array('model'=>$model)); ?>
