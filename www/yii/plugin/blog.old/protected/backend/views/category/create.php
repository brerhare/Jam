<?php

$this->menu=array(
	array('label'=>'Manage Categories','url'=>array('admin')),
);
?>

<h2>Create Category</h2>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>
