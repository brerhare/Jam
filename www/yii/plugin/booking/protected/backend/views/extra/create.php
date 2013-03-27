<?php

$this->menu=array(
	array('label'=>'Manage Extra','url'=>array('admin')),
);
?>

<h1>Create Extra</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>