<?php
$this->menu=array(
	array('label'=>'Manage Tab Content','url'=>array('admin')),
);
?>

<h1>Update Tab Content <?php echo $model->id; ?></h1>

<?php echo $this->renderPartial('_form',array('model'=>$model)); ?>
