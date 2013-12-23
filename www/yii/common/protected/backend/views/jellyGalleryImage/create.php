<?php

$this->menu=array(
	array('label'=>'Manage Gallery Images','url'=>array('admin')),
);
?>

<h2>Create Gallery Image</h2>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>
