<?php

$this->menu=array(
	array('label'=>'Manage List Members','url'=>array('admin')),
);
?>

<h2>Update List Member <?php echo $model->id; ?></h2>

<?php echo $this->renderPartial('_form',array('model'=>$model)); ?>
