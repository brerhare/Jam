<?php

$this->menu=array(
	array('label'=>'Manage Products','url'=>array('admin')),
);
?>

<h2>Update Product <?php echo $model->name; ?></h2>

<?php echo $this->renderPartial('_form',array('model'=>$model)); ?>
