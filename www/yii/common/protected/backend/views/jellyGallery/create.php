<?php

$this->menu=array(
	array('label'=>'Manage Albums','url'=>array('admin')),
);
?>

<h2>Create Gallery Album</h2>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>
