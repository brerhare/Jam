<?php

$this->menu=array(
	array('label'=>'Manage Albums','url'=>array('admin')),
);
?>

<h2>Update Gallery Album <?php echo $model->title; ?></h2>

<?php echo $this->renderPartial('_form',array('model'=>$model)); ?>
