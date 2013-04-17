<?php

$this->menu=array(
	array('label'=>'Manage OccupancyType','url'=>array('admin')),
);
?>

<h1>Update Occupancy Type <?php echo $model->id; ?></h1>

<?php echo $this->renderPartial('_form',array('model'=>$model)); ?>