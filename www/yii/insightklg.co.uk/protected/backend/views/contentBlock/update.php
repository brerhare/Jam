<?php

$this->menu=array(
	array('label'=>'Manage Content Blocks','url'=>array('admin')),
);
?>

<h1>Update Content Block <?php //echo $model->id; ?></h1>

<?php echo $this->renderPartial('_form',array('model'=>$model)); ?>
