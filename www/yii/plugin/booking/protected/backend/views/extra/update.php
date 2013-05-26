<?php

$this->menu=array(
	array('label'=>'Manage Extras','url'=>array('admin')),
);
?>

<h1>Update Extra <?php echo $model->id; ?></h1>

<?php echo $this->renderPartial('_form',array('model'=>$model)); ?>