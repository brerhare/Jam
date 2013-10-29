<?php

$this->menu=array(
	array('label'=>'Manage Shipping Options','url'=>array('admin')),
);
?>

<h2>Update Shipping Option <?php echo $model->description; ?></h1>

<?php echo $this->renderPartial('_form',array('model'=>$model)); ?>