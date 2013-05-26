<?php

$this->menu=array(
	array('label'=>'Manage Extras','url'=>array('admin')),
);
?>

<h1>Create Extra</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>