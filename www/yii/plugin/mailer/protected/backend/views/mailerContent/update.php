<?php

$this->menu=array(
	array('label'=>'Manage Mailing List Content','url'=>array('admin')),
);
?>

<h2>Update Mailing List Content</h2>

<?php echo $this->renderPartial('_form',array('model'=>$model)); ?>
