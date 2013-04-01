<?php

$this->menu=array(
	array('label'=>'Manage Rooms','url'=>array('admin')),
);
?>

<h1>Update Room <?php echo $model->id; ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>
