<?php

$this->menu=array(
	array('label'=>'Manage Food Types','url'=>array('admin')),
);
?>

<h2>Update Food Type <?php echo $model->name; ?></h2>

<?php echo $this->renderPartial('_form',array('model'=>$model)); ?>
