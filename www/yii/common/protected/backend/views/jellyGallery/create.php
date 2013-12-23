<?php

$this->menu=array(
	array('label'=>'Manage Galleries','url'=>array('admin')),
);
?>

<h2>Create Gallery</h2>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>
