<?php

$this->menu=array(
	array('label'=>'Manage Facility','url'=>array('admin')),
);
?>

<h1>Update Facility <?php echo $model->id; ?></h1>

<?php echo $this->renderPartial('_form',array('model'=>$model)); ?>