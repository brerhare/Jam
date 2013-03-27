<?php

$this->menu=array(
	array('label'=>'Manage Extra','url'=>array('admin')),
);
?>

<h1>Update Extra <?php echo $model->id; ?></h1>

<?php echo $this->renderPartial('_form',array('model'=>$model)); ?>