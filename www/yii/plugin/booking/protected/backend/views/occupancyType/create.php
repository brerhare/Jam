<?php

$this->menu=array(
	array('label'=>'Manage OccupancyType','url'=>array('admin')),
);
?>

<h1>Create Occupancy Type</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>