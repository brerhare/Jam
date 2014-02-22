<?php

$this->menu=array(
	array('label'=>'Manage Download Files','url'=>array('admin')),
);
?>

<h2>Update Download File <?php echo $model->filename; ?></h2>

<?php echo $this->renderPartial('_form',array('model'=>$model)); ?>
