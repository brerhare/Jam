<?php

$this->menu=array(
	array('label'=>'Manage Gallery Images','url'=>array('admin')),
);
?>

<h2>Update GalleryImage</h2>

<?php echo $this->renderPartial('_form',array('model'=>$model)); ?>
