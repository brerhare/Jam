<?php

$this->menu=array(
	array('label'=>'Manage Accordion Content','url'=>array('admin')),
);
?>

<h1>Update Accordion Content</h1>

<?php echo $this->renderPartial('_form',array('model'=>$model)); ?>