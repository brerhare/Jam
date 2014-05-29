<?php

$this->menu=array(
	//array('label'=>'List Program','url'=>array('index')),
	array('label'=>'Manage Program','url'=>array('admin')),
);
?>

<h4>Create Program</h4>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>
