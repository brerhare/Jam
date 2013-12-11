<?php

$this->menu=array(
	array('label'=>'Manage Mailing Lists','url'=>array('admin')),
);
?>

<h2>Update Mailing List <?php echo $model->id; ?></h2>

<?php echo $this->renderPartial('_form',array('model'=>$model)); ?>
