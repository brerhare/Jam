<?php

$this->menu=array(
	array('label'=>'Manage Types','url'=>array('admin')),
);
?>

<h2>Create Type</h2>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>
